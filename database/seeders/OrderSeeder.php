<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run()
    {
        if (DB::table('order')->exists()) {
            return;
        }

        $now = now();

        $customers = [
            ['userName' => 'Nguyễn Văn An', 'userPhone' => '0901234567', 'userEmail' => 'an.nguyen@gmail.com', 'userAddress' => '123 Lê Lợi, Quận 1, TP.HCM'],
            ['userName' => 'Trần Thị Bình', 'userPhone' => '0912345678', 'userEmail' => 'binh.tran@gmail.com', 'userAddress' => '45 Nguyễn Huệ, Quận 1, TP.HCM'],
            ['userName' => 'Lê Hoàng Cường', 'userPhone' => '0923456789', 'userEmail' => 'cuong.le@yahoo.com', 'userAddress' => '78 Hai Bà Trưng, Hoàn Kiếm, Hà Nội'],
            ['userName' => 'Phạm Minh Đức', 'userPhone' => '0934567890', 'userEmail' => 'duc.pham@gmail.com', 'userAddress' => '12 Trần Phú, Hải Châu, Đà Nẵng'],
            ['userName' => 'Hoàng Thị Em', 'userPhone' => '0945678901', 'userEmail' => 'em.hoang@gmail.com', 'userAddress' => '56 Lê Văn Sỹ, Quận 3, TP.HCM'],
            ['userName' => 'Vũ Quốc Huy', 'userPhone' => '0956789012', 'userEmail' => 'huy.vu@gmail.com', 'userAddress' => '89 Phan Xích Long, Phú Nhuận, TP.HCM'],
            ['userName' => 'Đỗ Thị Lan', 'userPhone' => '0967890123', 'userEmail' => 'lan.do@gmail.com', 'userAddress' => '34 Võ Văn Tần, Quận 3, TP.HCM'],
            ['userName' => 'Bùi Văn Nam', 'userPhone' => '0978901234', 'userEmail' => 'nam.bui@gmail.com', 'userAddress' => '21 Lý Thường Kiệt, Hoàn Kiếm, Hà Nội'],
            ['userName' => 'Ngô Thị Oanh', 'userPhone' => '0989012345', 'userEmail' => 'oanh.ngo@gmail.com', 'userAddress' => '67 Nguyễn Thị Minh Khai, Quận 1, TP.HCM'],
            ['userName' => 'Dương Văn Phúc', 'userPhone' => '0990123456', 'userEmail' => 'phuc.duong@gmail.com', 'userAddress' => '90 Cách Mạng Tháng 8, Quận 10, TP.HCM'],
        ];

        $userIds = [];
        foreach ($customers as $c) {
            $userIds[] = DB::table('user')->insertGetId(array_merge($c, [
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        $variants = DB::select('
            SELECT ProVariant.*, product.proName AS product_name
            FROM ProVariant
            INNER JOIN product ON ProVariant.proID = product.proID
            WHERE ProVariant.status = 1
            ORDER BY ProVariant.proVarID ASC
        ');

        if (!$variants) {
            return;
        }

        $orderStatuses = [0, 0, 0, 1, 1, 2, 2, 3, 3, 4];
        $paymentStatuses = [1, 2, 2, 1, 2, 2, 1, 2, 2, 1];
        $discounts = [0, 5, 10, 0, 15, 5, 0, 10, 5, 0];

        for ($i = 0; $i < 20; $i++) {
            $userId = $userIds[array_rand($userIds)];
            $user = DB::table('user')->where('userID', $userId)->first();
            $discount = $discounts[$i % count($discounts)];
            $orderStatus = $orderStatuses[$i % count($orderStatuses)];
            $paymentStatus = $paymentStatuses[$i % count($paymentStatuses)];
            $itemCount = random_int(1, 3);

            $subtotal = 0;
            $lines = [];

            for ($j = 0; $j < $itemCount; $j++) {
                $var = $variants[array_rand($variants)];
                $qty = random_int(1, 2);
                $lineTotal = (int) ($var->price * $qty);
                $subtotal += $lineTotal;
                $salePrice = (int) ($var->price * (100 - $discount) / 100);

                $lines[] = [
                    'proID' => $var->proID,
                    'proVarID' => $var->proVarID,
                    'proName' => $var->product_name,
                    'quantity' => $qty,
                    'basePrice' => $var->price,
                    'salePrice' => $salePrice,
                    'discount' => $discount,
                    'suborder_status' => $orderStatus >= 2 ? 1 : 0,
                ];
            }

            $total = (int) ($subtotal * (100 - $discount) / 100);
            $createdAt = $now->copy()->subDays(random_int(0, 30))->subHours(random_int(0, 23));

            $orderId = DB::table('order')->insertGetId([
                'userID' => $userId,
                'ordDate' => $createdAt,
                'ordPhone' => $user->userPhone,
                'ordReceiver' => $user->userName,
                'ordAddress' => $user->userAddress,
                'totalPrice' => $total,
                'staValue' => $orderStatus,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'order_type' => 0,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            DB::table('payment')->insert([
                'ordID' => $orderId,
                'payDate' => $createdAt,
                'payStatus' => $paymentStatus,
                'amount' => $total,
                'payMethod' => $paymentStatus === 2 ? 'ONLINE' : 'COD',
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            foreach ($lines as $line) {
                DB::table('orderDetail')->insert(array_merge($line, [
                    'ordID' => $orderId,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]));
            }
        }
    }
}
