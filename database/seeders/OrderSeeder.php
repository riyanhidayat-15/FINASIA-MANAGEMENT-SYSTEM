<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = Customer::all();

        if ($customers->isEmpty()) {
            $this->command->warn('Tidak ada data customer. Jalankan CustomerSeeder dulu sebelum OrderSeeder.');
            return;
        }

        $orders = [
            [
                'name' => 'Snack Meeting Januari 2026',
                'delivery_date' => '2026-01-15',
                'delivery_time' => '09:00:00', // Dipisah dari notes
                'status' => 'process',
                'notes' => 'Tolong dikirim tepat waktu',
                'items' => [
                    ['name' => 'Pastel Telur', 'quantity' => 50, 'unit' => 'pcs', 'unit_price' => 4000],
                    ['name' => 'Kue Sus', 'quantity' => 50, 'unit' => 'pcs', 'unit_price' => 4000],
                    ['name' => 'Risol Sayuran', 'quantity' => 50, 'unit' => 'pcs', 'unit_price' => 4000],
                ],
            ],
            [
                'name' => 'Snack Visitor Februari 2026',
                'delivery_date' => '2026-02-10',
                'delivery_time' => '10:00:00',
                'status' => 'done',
                'notes' => null,
                'items' => [
                    ['name' => 'Bika Ambon', 'quantity' => 20, 'unit' => 'box', 'unit_price' => 35000],
                    ['name' => 'Gorengan Mix', 'quantity' => 20, 'unit' => 'pcs', 'unit_price' => 3000],
                ],
            ],
            [
                'name' => 'Catering Rapat Direksi',
                'delivery_date' => '2026-02-20',
                'delivery_time' => '12:00:00',
                'status' => 'done',
                'notes' => 'Disajikan dengan kotak nasi',
                'items' => [
                    ['name' => 'Nasi Box Ayam Bakar', 'quantity' => 30, 'unit' => 'box', 'unit_price' => 28000],
                    ['name' => 'Air Mineral', 'quantity' => 30, 'unit' => 'pcs', 'unit_price' => 3000],
                ],
            ],
            [
                'name' => 'Snack Gathering Maret 2026',
                'delivery_date' => '2026-03-05',
                'delivery_time' => '14:00:00',
                'status' => 'process',
                'notes' => null,
                'items' => [
                    ['name' => 'Lemon Import', 'quantity' => 3, 'unit' => 'kg', 'unit_price' => 47000],
                    ['name' => 'Kue Lapis', 'quantity' => 15, 'unit' => 'box', 'unit_price' => 30000],
                ],
            ],
            [
                'name' => 'Pesanan Buah Lemon Import',
                'delivery_date' => '2026-01-10',
                'delivery_time' => '08:00:00',
                'status' => 'done',
                'notes' => 'Sesuai PO 4900023988',
                'items' => [
                    ['name' => 'Lemon Import', 'quantity' => 3, 'unit' => 'kg', 'unit_price' => 47000],
                ],
            ],
            [
                'name' => 'Snack Townhall April 2026',
                'delivery_date' => '2026-04-12',
                'delivery_time' => '13:00:00',
                'status' => 'done',
                'notes' => 'Dibatalkan karena acara diundur',
                'items' => [
                    ['name' => 'Donat Mini', 'quantity' => 40, 'unit' => 'box', 'unit_price' => 25000],
                ],
            ],
            [
                'name' => 'Catering Workshop Mei 2026',
                'delivery_date' => '2026-05-08',
                'delivery_time' => '11:30:00',
                'status' => 'process',
                'notes' => 'Untuk 25 peserta',
                'items' => [
                    ['name' => 'Nasi Box Vegetarian', 'quantity' => 25, 'unit' => 'box', 'unit_price' => 26000],
                    ['name' => 'Teh Kotak', 'quantity' => 25, 'unit' => 'pcs', 'unit_price' => 4000],
                ],
            ],
            [
                'name' => 'Snack MBF Juni 2026',
                'delivery_date' => '2026-06-17',
                'delivery_time' => '15:00:00',
                'status' => 'process',
                'notes' => null,
                'items' => [
                    ['name' => 'Pastel Telur', 'quantity' => 35, 'unit' => 'pcs', 'unit_price' => 4000],
                    ['name' => 'Kue Sus', 'quantity' => 35, 'unit' => 'pcs', 'unit_price' => 4000],
                    ['name' => 'Risol Sayuran', 'quantity' => 35, 'unit' => 'pcs', 'unit_price' => 4000],
                ],
            ],
        ];

        foreach ($orders as $index => $orderData) {
            $items = $orderData['items'];
            unset($orderData['items']);

            $orderData['customer_id'] = $customers[$index % $customers->count()]->id;
            $orderData['order_number'] = $this->generateOrderNumber($orderData['delivery_date'], $index + 1);

            // Menyimpan order menggunakan data yang sudah sinkron dengan fillable model
            $order = Order::create($orderData);

            foreach ($items as $item) {
                $order->items()->create($item);
            }
        }

        $this->command->info('OrderSeeder selesai: ' . count($orders) . ' order berhasil dibuat.');
    }

    private function generateOrderNumber(string $deliveryDate, int $sequence): string
    {
        $yearMonth = \Carbon\Carbon::parse($deliveryDate)->format('Ym');

        return sprintf('ORD-%s-%03d', $yearMonth, $sequence);
    }
}