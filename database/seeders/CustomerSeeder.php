<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        Customer::insert([
            [
                'name' => 'PT Maju Jaya',
                'phone' => '081234567890',
                'address' => 'Jl. Ahmad Yani No. 1, Cilegon',
                'city' => 'Cilegon',
                'province' => 'Banten',
                'postal_code' => '40738',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'CV Mitra Sejahtera',
                'phone' => '087812345678',
                'address' => 'Jl. Gatot Subroto No. 12, Bekasi',
                'city' => 'Cilegon',
                'province' => 'Banten',
                'postal_code' => '40383',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}