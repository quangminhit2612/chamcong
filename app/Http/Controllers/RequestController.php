<?php

namespace App\Http\Controllers;

use App\Models\Request as LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    public function index()
    {
        $requests = LeaveRequest::with('user')->orderByDesc('created_at')->get();
        return view('requests.index', compact('requests'));
    }

    public function create()
    {
        return view('requests.create');
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

        return redirect()->route('requests.index')->with('success', 'Gửi đơn xin phép thành công!');
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
