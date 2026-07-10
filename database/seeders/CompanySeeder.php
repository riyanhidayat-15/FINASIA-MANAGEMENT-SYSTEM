<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::create([
            'logo_path' => null,
            'name' => 'PT. Contoh Perusahaan',
            'address' => 'Jl. Contoh Alamat No. 123, Jakarta',
            'city' => 'Jakarta',
            'province' => 'DKI Jakarta',
            'phone' => '021-12345678',
            'director_name' => 'Budi Santoso',
            'bank_name' => 'Bank Contoh',
            'bank_account_name' => 'PT. Contoh Perusahaan',
            'bank_account_number' => '1234567890',
        ]);
    }
}
