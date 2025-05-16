<?php

namespace App\Exports;

use App\Models\OtRequest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OTExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return OtRequest::select(
                'user_id',
                'ot_date',
                'ot_start_time',
                'ot_end_time',
                'ot_hours',
                'ot_reason'
            )->get();
    }

    public function headings(): array
    {
        return [
            'User ID',
            'Ngày Làm Thêm Giờ',
            'Giờ Bắt Đầu',
            'Giờ Kết Thúc',
            'Số Giờ Làm Thêm',
            'Lý Do Làm Thêm Giờ',
        ];
    }
}
