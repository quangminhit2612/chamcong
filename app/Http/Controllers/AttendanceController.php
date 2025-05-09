<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceExport;



class AttendanceController extends Controller
{
    public function store(Request $request)
    {
        $ip = $request->ip();
        $user = auth()->user();

        // Cho phép nhiều dải IP nội bộ
        $allowedRanges = [
            '192.168.0.0/16',  // Mạng LAN phổ biến
            '10.0.0.0/8',      // Một số công ty dùng
            '127.0.0.1',       // Localhost
        ];

        // Lấy IP người dùng
        $userIp = $this->getPublicIp($request);

        // Ghi log để debug IP nếu cần
        \Log::info('Client IP: ' . $userIp);

        // Kiểm tra xem IP có nằm trong bất kỳ dải nào không
        $isAllowed = false;
        foreach ($allowedRanges as $range) {
            if ($this->isIpInRange($userIp, $range)) {
                $isAllowed = true;
                break;
            }
        }

        if (!$isAllowed) {
            return response()->json(['message' => 'Bạn phải kết nối với Wi-Fi công ty để chấm công!'], 403);
        }

        // Kiểm tra xem người dùng đã chấm công chưa
        $attendance = Attendance::where('user_id', $user->id)->latest()->first();

        if (!$attendance || $attendance->status === 'checked_out') {
            $attendance = new Attendance();
            $attendance->user_id = $user->id;
            $attendance->status = 'checked_in';
            $attendance->checked_in_at = now();
            $attendance->ip_address = $userIp;
            $attendance->save();

            return response()->json(['message' => 'Chấm công thành công!']);
        }

        if ($attendance->status === 'checked_in' && $request->action === 'checkout') {
            $attendance->status = 'checked_out';
            $attendance->checked_out_at = now();
            $attendance->save();

            return response()->json(['message' => 'Rời công ty thành công!']);
        }

        return response()->json(['message' => 'Vui lòng chấm công trước khi rời công ty.']);
    }

    private function getPublicIp(Request $request)
    {
        // Nếu có proxy, ưu tiên lấy từ X-Forwarded-For
        if ($request->headers->has('X-Forwarded-For')) {
            return trim(explode(',', $request->header('X-Forwarded-For'))[0]);
        }

        return $request->ip();
    }

    public function isIpInRange($ip, $range)
    {
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            throw new \Exception("IP không hợp lệ: $ip");
        }

        if (filter_var($range, FILTER_VALIDATE_IP)) {
            return $ip === $range;
        }

        if (strpos($range, '/') !== false) {
            list($subnet, $mask) = explode('/', $range);
            return (ip2long($ip) & ~((1 << (32 - $mask)) - 1)) === (ip2long($subnet) & ~((1 << (32 - $mask)) - 1));
        }

        throw new \Exception("Dải IP không hợp lệ: $range");
    }
        

    // Phương thức lấy trạng thái chấm công của người dùng
    public function status()
    {
        $userId = auth()->id();

        // Bản ghi chấm công mới nhất
        $latest = Attendance::where('user_id', $userId)->latest()->first();

        if (!$latest) {
            return response()->json(['status' => 'not_checked_in']);
        }

        $createdDate = Carbon::parse($latest->created_at)->startOfDay();
        $today = Carbon::now()->startOfDay();

        // Nếu bản ghi chấm công là ngày hôm trước hoặc xa hơn → reset lại để cho chấm công mới
        if ($createdDate->lt($today)) {
            return response()->json(['status' => 'not_checked_in']);
        }

        // Nếu đang trong ngày hiện tại
        if ($latest->status === 'checked_in') {
            return response()->json(['status' => 'checked_in']);
        } elseif ($latest->status === 'checked_out') {
            return response()->json(['status' => 'checked_out']);
        }

        // Mặc định
        return response()->json(['status' => 'not_checked_in']);
    }


    // Lấy lịch sử chấm công
    public function getAttendanceHistory(Request $request)
    {
        $user = auth()->user();

        // Lấy tất cả lịch sử chấm công của người dùng
        $attendances = Attendance::where('user_id', $user->id)
                                ->orderBy('checked_in_at', 'desc')
                                ->get();

        return response()->json($attendances);
    }

    // Tải xuống lịch sử chấm công
    public function exportExcel()
    {
        return Excel::download(new AttendanceExport, 'lich-su-cham-cong.xlsx');
    }



}

