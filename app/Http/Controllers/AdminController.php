<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\OtRequest;
use App\Models\User;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $users = User::all();

        // Khởi tạo query
        $query = Attendance::with('user');

        // Lọc theo khoảng thời gian nếu có
        if ($request->filled('start_date')) {
            $query->whereDate('checked_in_at', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $query->whereDate('checked_in_at', '<=', $request->input('end_date'));
        }

        // Lọc theo nhân viên nếu có
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        // Lấy kết quả đã lọc
        $attendances = $query->latest()->get();

        // Lấy các bản ghi liên quan
        $leaves = LeaveRequest::with('user')->latest()->get();
        $ots = OtRequest::with('user')->latest()->get();

        // Trả về view
        return view('admin.panel', compact('attendances', 'leaves', 'ots', 'users'));
    }

}