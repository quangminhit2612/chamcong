<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OtRequest;

class OtRequestController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ot_date' => 'required|date',
            'ot_start_time' => 'required|date_format:H:i',
            'ot_end_time' => 'required|date_format:H:i|after:ot_start_time',
            'ot_hours' => 'required|numeric|min:0.25',
            'ot_reason' => 'required|string|max:255',
        ]);

        // Thêm user_id từ người dùng đang đăng nhập
        $validated['user_id'] = auth()->id();

        OtRequest::create($validated);

        return response()->json(['message' => 'Yêu cầu OT đã được gửi thành công!']);
    }

    public function approve($id)
    {
        $ot = OtRequest::findOrFail($id);
        $ot->status = 'approved';
        $ot->save();

        return back()->with('success', 'Đã duyệt đơn OT.');
    }
}
