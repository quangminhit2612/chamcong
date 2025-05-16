<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    public function run()
    {
        $users = [9, 10]; // ID của 2 user
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now();

        foreach ($users as $userId) {
            $date = $start->copy();

            while ($date->lte($end)) {
                // Bỏ qua thứ Bảy, Chủ Nhật nếu muốn
                if (in_array($date->dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY])) {
                    $date->addDay();
                    continue;
                }

                $checkedIn = $date->copy()->setTime(rand(8, 8), rand(0, 59), 0); // ví dụ 08:00 ~ 08:59
                $checkedOut = $checkedIn->copy()->addHours(9)->addMinutes(rand(0, 30)); // ~ 17:00 - 17:30

                Attendance::create([
                    'user_id' => $userId,
                    'checked_in_at' => $checkedIn,
                    'checked_out_at' => $checkedOut,
                    'ip_address' => '127.0.0.1',
                    'status' => 'checked_out',
                    'created_at' => $checkedIn,
                    'updated_at' => $checkedOut,
                ]);

                $date->addDay();
            }
        }
    }
}
