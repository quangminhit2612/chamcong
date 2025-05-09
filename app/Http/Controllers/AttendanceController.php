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
        $user = auth()->user();
        $userIp = $this->getPublicIp($request);

        \Log::info('Client IP: ' . $userIp);

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

    public function exportExcel()
    {
        // Kiểm tra nếu người dùng là Admin hoặc Giám đốc
        if (in_array(strtolower(auth()->user()->role), ['admin', 'gd'])) {
            return Excel::download(new AttendanceExport, 'lich-su-cham-cong.xlsx');
        } else {
            // Nếu không phải admin hoặc giám đốc, trả về thông báo lỗi hoặc redirect
            return redirect()->back()->with('error', 'Bạn không có quyền truy cập chức năng này.');
        }
    }
}
