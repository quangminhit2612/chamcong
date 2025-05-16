<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceExport;
use App\Exports\LeaveExport;
use App\Exports\OTExport;

class AttendanceController extends Controller
{
    public function store(Request $request)
    {
        $user = auth()->user();
        $userIp = $this->getPublicIp($request);

        \Log::info('Client IP: ' . $userIp);

        // Chặn nếu IP không nằm trong dải 104.28.205.xxx
        if (
            $userIp !== '127.0.0.1' &&
            !str_starts_with($userIp, '104.28.')
        ) {
            return response()->json([], 403);
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

    // Phương thức lấy trạng thái chấm công của người dùng
    public function status()
    {
        $userId = auth()->id();

        $latest = Attendance::where('user_id', $userId)->latest()->first();

        if (!$latest) {
            return response()->json(['status' => 'not_checked_in']);
        }

        $createdDate = Carbon::parse($latest->created_at)->startOfDay();
        $today = Carbon::now()->startOfDay();

        if ($createdDate->lt($today)) {
            return response()->json(['status' => 'not_checked_in']);
        }

        if ($latest->status === 'checked_in') {
            return response()->json(['status' => 'checked_in']);
        } elseif ($latest->status === 'checked_out') {
            return response()->json(['status' => 'checked_out']);
        }

        return response()->json(['status' => 'not_checked_in']);
    }

    public function getAttendanceHistory(Request $request)
    {
        $user = auth()->user();

        $attendances = Attendance::where('user_id', $user->id)
                                ->orderBy('checked_in_at', 'desc')
                                ->get();

        return response()->json($attendances);
    }

    public function exportExcel(Request $request)
    {
        $type = $request->input('history_type');  // Nhận giá trị từ select

        if (!in_array(strtolower(auth()->user()->role), ['admin', 'gd'])) {
            return redirect()->back()->with('error', 'Bạn không có quyền truy cập chức năng này.');
        }

        switch ($type) {
            case 'leave':
                return Excel::download(new LeaveExport, 'don-xin-nghi.xlsx');
            case 'ot':
                return Excel::download(new OTExport, 'lam-them-gio.xlsx');
            case 'attendance':
            default:
                return Excel::download(new AttendanceExport, 'lich-su-cham-cong.xlsx');
        }
    }
    
}
