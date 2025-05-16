<?php

// database/seeders/RequestsTableSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Faker\Factory as Faker;

class RequestsTableSeeder extends Seeder
{
    public function run()
    {
        // Tạo đối tượng Faker
        $faker = Faker::create();

        // Mảng lý do (reason) mẫu để chọn ngẫu nhiên
        $reasons = [
            'Em xin nghỉ phép để chăm con',
            'Tôi đi du lịch',
            'Nghỉ để điều trị bệnh',
            'Nghỉ vì lý do gia đình',
            'Đi học nâng cao kỹ năng',
            'Thăm bà con ở xa',
            'Nghỉ phép để giải quyết việc cá nhân',
            'Nghỉ để tham gia đám cưới bạn bè',
            'Nghỉ phép để làm thủ tục hành chính',
            'Đi công tác xa',
            'Đi tham quan học hỏi',
            'Nghỉ phép để chúc Tết',
            'Nghỉ để chuẩn bị kỳ thi',
            'Nghỉ để chăm sóc người thân',
            'Đi du lịch dài ngày',
            'Nghỉ phép để học trực tuyến',
            'Nghỉ để tham gia sự kiện ngoài trời',
            'Nghỉ để giải quyết việc riêng',
            'Đi lễ hội',
            'Nghỉ để tham gia hoạt động tình nguyện',
            'Nghỉ phép để đi khám sức khỏe',
            'Đi tham dự hội nghị',
            'Nghỉ phép để làm thủ tục nhập học',
            'Nghỉ phép để sửa chữa nhà cửa',
            'Nghỉ phép để tiếp đón khách',
            'Nghỉ phép để chuẩn bị sinh nhật',
            'Nghỉ để tham gia khóa học',
            'Đi hội thảo công nghệ',
            'Nghỉ phép để đi đám tang',
        ];

        // Thêm dữ liệu vào bảng requests
        for ($i = 0; $i < 30; $i++) {
            DB::table('requests')->insert([
                'user_id' => rand(9, 10),  // Chọn ngẫu nhiên user_id là 9 hoặc 10
                'type' => 'leave',
                'reason' => $faker->randomElement($reasons),  // Lý do ngẫu nhiên từ mảng
                'date' => Carbon::parse($faker->dateTimeBetween('2025-05-01', '2025-05-30')->format('Y-m-d')),
                'status' => 'pending',  // Trạng thái mặc định là pending
                'approver_id' => null,  // chưa có người duyệt
                'approved_at' => null,  // chưa duyệt
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
