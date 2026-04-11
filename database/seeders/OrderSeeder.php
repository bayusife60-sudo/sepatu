<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\User;
use App\Models\Treatment;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $customer = User::where('phone', '085600267104')->first();
        if (!$customer) return;

        $treatments = Treatment::all();
        if ($treatments->isEmpty()) return;

        $brands = ['Adidas', 'Nike', 'Vans', 'Converse', 'Compass', 'Puma', 'New Balance'];
        $materials = ['Canvas', 'Suede', 'Leather', 'Mesh', 'Knit'];
        $colors = ['Hitam', 'Putih', 'Biru', 'Merah', 'Grey', 'Brown'];
        $statuses = ['Antrian', 'Diterima Toko', 'Dikerjakan', 'Siap Diambil', 'Selesai'];

        for ($i = 0; $i < 30; $i++) {
            $date = Carbon::now()->subDays(rand(0, 30));
            $isPaid = rand(0, 100) > 30 ? 'lunas' : 'belum_lunas';
            $method = rand(0, 1) ? 'datang_langsung' : 'pickup_delivery';
            $pFee = ($method == 'pickup_delivery') ? 40000 : 0;
            
            $orderCode = 'CLZ-S' . rand(1000, 9999);
            
            $itemCount = rand(1, 3);
            $itemsPrice = 0;
            $itemsData = [];

            for ($j = 0; $j < $itemCount; $j++) {
                $treatment = $treatments->random();
                $itemsPrice += $treatment->price;
                $itemsData[] = [
                    'shoe_brand' => $brands[array_rand($brands)],
                    'shoe_material' => $materials[array_rand($materials)],
                    'shoe_color' => $colors[array_rand($colors)],
                    'treatment_id' => $treatment->id,
                    'price' => $treatment->price,
                ];
            }

            $order = Order::create([
                'order_code' => $orderCode,
                'customer_id' => $customer->id,
                'customer_name' => $customer->name,
                'customer_phone' => $customer->phone,
                'service_method' => $method,
                'pickup_fee' => $pFee,
                'total_price' => $itemsPrice + $pFee,
                'status' => $statuses[array_rand($statuses)],
                'payment_status' => $isPaid,
                'payment_method' => 'Tunai/Bank Transfer',
                'created_at' => $date,
                'updated_at' => $date,
                'payment_date' => $isPaid == 'lunas' ? $date : null,
            ]);

            foreach ($itemsData as $item) {
                $order->items()->create($item);
            }
        }
    }
}
