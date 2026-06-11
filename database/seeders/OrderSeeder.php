<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run()
    {
        if (DB::table('orders')->exists()) {
            return;
        }

        $now = now();

        $customers = [
            ['name' => 'Nguyễn Văn An', 'phone' => '0901234567', 'email' => 'an.nguyen@gmail.com', 'address' => '123 Lê Lợi, Quận 1, TP.HCM'],
            ['name' => 'Trần Thị Bình', 'phone' => '0912345678', 'email' => 'binh.tran@gmail.com', 'address' => '45 Nguyễn Huệ, Quận 1, TP.HCM'],
            ['name' => 'Lê Hoàng Cường', 'phone' => '0923456789', 'email' => 'cuong.le@yahoo.com', 'address' => '78 Hai Bà Trưng, Hoàn Kiếm, Hà Nội'],
            ['name' => 'Phạm Minh Đức', 'phone' => '0934567890', 'email' => 'duc.pham@gmail.com', 'address' => '12 Trần Phú, Hải Châu, Đà Nẵng'],
            ['name' => 'Hoàng Thị Em', 'phone' => '0945678901', 'email' => 'em.hoang@gmail.com', 'address' => '56 Lê Văn Sỹ, Quận 3, TP.HCM'],
            ['name' => 'Vũ Quốc Huy', 'phone' => '0956789012', 'email' => 'huy.vu@gmail.com', 'address' => '89 Phan Xích Long, Phú Nhuận, TP.HCM'],
            ['name' => 'Đỗ Thị Lan', 'phone' => '0967890123', 'email' => 'lan.do@gmail.com', 'address' => '34 Võ Văn Tần, Quận 3, TP.HCM'],
            ['name' => 'Bùi Văn Nam', 'phone' => '0978901234', 'email' => 'nam.bui@gmail.com', 'address' => '21 Lý Thường Kiệt, Hoàn Kiếm, Hà Nội'],
            ['name' => 'Ngô Thị Oanh', 'phone' => '0989012345', 'email' => 'oanh.ngo@gmail.com', 'address' => '67 Nguyễn Thị Minh Khai, Quận 1, TP.HCM'],
            ['name' => 'Dương Văn Phúc', 'phone' => '0990123456', 'email' => 'phuc.duong@gmail.com', 'address' => '90 Cách Mạng Tháng 8, Quận 10, TP.HCM'],
        ];

        $customerIds = [];
        foreach ($customers as $c) {
            $customerIds[] = DB::table('customer')->insertGetId([
                'name' => $c['name'],
                'phone' => $c['phone'],
                'email' => $c['email'],
                'address' => $c['address'],
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $variants = DB::select('
            SELECT product_var.*, product.name AS product_name
            FROM product_var
            INNER JOIN product ON product_var.product_id = product.id
            WHERE product_var.status = 1
            ORDER BY product_var.id ASC
        ');

        if (!$variants) {
            return;
        }

        $orderStatuses = [0, 0, 0, 1, 1, 2, 2, 3, 3, 4];
        $paymentStatuses = [1, 2, 2, 1, 2, 2, 1, 2, 2, 1];
        $discounts = [0, 5, 10, 0, 15, 5, 0, 10, 5, 0];

        for ($i = 0; $i < 20; $i++) {
            $customerId = $customerIds[array_rand($customerIds)];
            $discount = $discounts[$i % count($discounts)];
            $orderStatus = $orderStatuses[$i % count($orderStatuses)];
            $paymentStatus = $paymentStatuses[$i % count($paymentStatuses)];
            $itemCount = random_int(1, 3);

            $subtotal = 0;
            $lines = [];

            for ($j = 0; $j < $itemCount; $j++) {
                $var = $variants[array_rand($variants)];
                $qty = random_int(1, 2);
                $lineTotal = (int) ($var->prices * $qty);
                $subtotal += $lineTotal;

                $lines[] = [
                    'product_id' => $var->product_id,
                    'product_var_id' => $var->id,
                    'product_name' => $var->product_name,
                    'quantity' => $qty,
                    'price' => $var->prices,
                    'discount' => $discount,
                    'total_price' => (int) ($lineTotal * (100 - $discount) / 100),
                    'suborder_status' => $orderStatus >= 2 ? 1 : 0,
                ];
            }

            $total = (int) ($subtotal * (100 - $discount) / 100);
            $createdAt = $now->copy()->subDays(random_int(0, 30))->subHours(random_int(0, 23));

            $orderId = DB::table('orders')->insertGetId([
                'customer_id' => $customerId,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'order_status' => $orderStatus,
                'payment_status' => $paymentStatus,
                'order_type' => 0,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            foreach ($lines as $line) {
                DB::table('order_detail')->insert(array_merge($line, [
                    'order_id' => $orderId,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]));
            }
        }
    }
}
