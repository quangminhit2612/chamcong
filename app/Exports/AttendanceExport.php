<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AttendanceExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Attendance::select('user_id', 'status', 'checked_in_at', 'checked_out_at', 'ip_address')->get();
    }

    public function headings(): array
    {
        return [
            'User ID',
            'Trạng Thái',
            'Giờ Chấm Công',
            'Giờ Rời Công Ty',
            'IP'
        ];
    }
}
