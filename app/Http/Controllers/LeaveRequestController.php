<?php

namespace App\Http\Controllers;

use App\Models\Request as LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveRequestController extends Controller
{

    public function dashboard()
    {
        $user = auth()->user();

        if (strtolower($user->role) === 'admin') {
            // admin xem tất cả yêu cầu
            $requests = LeaveRequest::with('user')->latest()->get();
        } elseif (strtolower($user->role) === 'gd') {
            // giám đốc chỉ xem yêu cầu của các user ngoài employee2
            $requests = LeaveRequest::with('user')
                ->whereHas('user', function ($query) {
                    $query->where('role', '!=', 'employee2');
                })
                ->latest()
                ->get();
        } elseif (strtolower($user->role) === 'tp') {
            // trưởng phòng xem tất cả đơn của employee2
            $requests = LeaveRequest::with('user')
                ->whereHas('user', function ($query) {
                    $query->where('role', 'employee2');
                })
                ->latest()
                ->get();
        } else {
            // các user khác chỉ xem đơn của chính họ
            $requests = LeaveRequest::with('user')
                ->where('user_id', $user->id)
                ->latest()
                ->get();
        }

        return view('dashboard', compact('requests'));
    }

    public function index()
    {
        $requests = LeaveRequest::with('user')->orderByDesc('created_at')->get();
        return view('requests.index', compact('requests'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:leave,late,remote,ot',
            'date' => 'required|date',
            'reason' => 'nullable|string',
        ]);
    
        LeaveRequest::create([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'date' => $request->date,
            'reason' => $request->reason,
        ]);
    
        // Trả về trang dashboard thay vì requests.index
        return redirect()->route('dashboard')->with('success', 'Gửi đơn xin phép thành công!');
    }    

    public function approve(LeaveRequest $request)
    {
        $request->update([
            'status' => 'approved',
            'approver_id' => Auth::id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Đã duyệt đơn.');
    }

    public function reject(LeaveRequest $request)
    {
        $request->update([
            'status' => 'rejected',
            'approver_id' => Auth::id(),
            'approved_at' => now(),
        ]);

        return back()->with('error', 'Đã từ chối đơn.');
    }
}
