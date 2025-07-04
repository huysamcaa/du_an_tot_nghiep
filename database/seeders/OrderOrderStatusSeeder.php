<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class OrderOrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $orderStatuses = [
            // Giả lập trạng thái đơn hàng
            [
                'order_id' => 1,  // ID đơn hàng
                'order_status_id' => 1,  // Trạng thái: Pending
                'modified_by' => 1,  // Người thay đổi trạng thái
                'note' => 'Đơn hàng đang được xử lý.',
                'employee_evidence' => null,  // Không có chứng từ
                'customer_confirmation' => null,  // Chưa có xác nhận của khách hàng
                'is_current' => 1,  // Đây là trạng thái hiện tại
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'order_id' => 1,
                'order_status_id' => 2,  // Trạng thái: Completed
                'modified_by' => 1,
                'note' => 'Đơn hàng đã được hoàn thành.',
                'employee_evidence' => 'evidence_file_123.pdf',  // Ví dụ có file chứng từ
                'customer_confirmation' => null,  
                'is_current' => 0,  // Trạng thái trước đó
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'order_id' => 2,
                'order_status_id' => 1,  // Trạng thái: Pending
                'modified_by' => 1,
                'note' => 'Đơn hàng đang chờ xác nhận.',
                'employee_evidence' => null,
                'customer_confirmation' => null,
                'is_current' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'order_id' => 3,
                'order_status_id' => 3,  // Trạng thái: Cancelled
                'modified_by' => 1,
                'note' => 'Đơn hàng đã bị hủy.',
                'employee_evidence' => null,
                'customer_confirmation' => null,  
                'is_current' => 0,  // Trạng thái không hiện tại
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Thêm nhiều dữ liệu giả lập khác nếu cần
        ];

        // Chèn dữ liệu vào bảng `order_order_status`
        DB::table('order_order_status')->insert($orderStatuses);
    }
}
