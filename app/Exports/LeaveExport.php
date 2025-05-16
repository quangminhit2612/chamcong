<?php

namespace App\Exports;

use App\Models\LeaveRequest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LeaveExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return LeaveRequest::select('user_id', 'type', 'reason', 'date', 'status', 'approver_id', 'approved_at')
                           ->with('user', 'approver') // Liên kết với User để lấy thông tin người dùng và người duyệt
                           ->get();
    }

    public function headings(): array
    {
        return [
            'User ID',
            'Loại Nghỉ',
            'Lý Do',
            'Ngày Nghỉ',
            'Trạng Thái',
            'Approver ID',
            'Ngày Duyệt'
        ];
    }
}
