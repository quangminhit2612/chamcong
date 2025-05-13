<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as HttpRequest;
use App\Models\Attendance;
use App\Models\OtRequest;
use App\Models\LeaveRequest; // model nghỉ phép

class HistoryController extends Controller
{
    public function get(HttpRequest $request)
    {
        $type = $request->query('type', 'attendance');
        $userId = auth()->id();

        switch ($type) {
            case 'ot':
                // Trả về dữ liệu overtime cho người dùng
                return OtRequest::where('user_id', $userId)
                    ->latest()
                    ->get(['ot_date', 'ot_start_time','ot_hours','ot_end_time', 'ot_reason']); // Lọc chỉ những cột cần thiết

            case 'leave':
                return LeaveRequest::where('user_id', $userId)->latest()->get(); 

            case 'attendance':
            default:
                return Attendance::where('user_id', $userId)->latest()->get();
        }
    }
}
