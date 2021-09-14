<?php

namespace Database\Seeders;

use App\Models\Merchant;
use Illuminate\Database\Seeder;

class MerchantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Merchant::insert([
            [
                'username'        => 'Ethan',
                'commission_rate' => 0.1,
                'password'        => '$2y$10$q522NdcDbp8UsEvmV1.IV.2At/vBvxi6Jmjr6jsYv3o5K88oMdx9O',
                'created_at'      => '2021-09-14 12:25:42'
            ],
            [
                'username'        => 'Riyadh',
                'commission_rate' => 0.1,
                'password'        => '$2y$10$q522NdcDbp8UsEvmV1.IV.2At/vBvxi6Jmjr6jsYv3o5K88oMdx9O',
                'created_at'      => '2021-09-14 12:25:42'
            ],
            [
                'username'        => 'Doha',
                'commission_rate' => 0.1,
                'password'        => '$2y$10$q522NdcDbp8UsEvmV1.IV.2At/vBvxi6Jmjr6jsYv3o5K88oMdx9O',
                'created_at'      => '2021-09-14 12:25:42'
            ],
            [
                'username'        => 'Future',
                'commission_rate' => 0.1,
                'password'        => '$2y$10$q522NdcDbp8UsEvmV1.IV.2At/vBvxi6Jmjr6jsYv3o5K88oMdx9O',
                'created_at'      => '2021-09-14 12:25:42'
            ], [
                'username'        => 'Vika',
                'commission_rate' => 0.15,
                'password'        => '$2y$10$q522NdcDbp8UsEvmV1.IV.2At/vBvxi6Jmjr6jsYv3o5K88oMdx9O',
                'created_at'      => '2021-09-14 12:25:42'
            ],
            [
                'username'        => 'Lina',
                'commission_rate' => 0.15,
                'password'        => '$2y$10$q522NdcDbp8UsEvmV1.IV.2At/vBvxi6Jmjr6jsYv3o5K88oMdx9O',
                'created_at'      => '2021-09-14 12:25:42'
            ],
            [
                'username'        => 'Kitty',
                'commission_rate' => 0.15,
                'password'        => '$2y$10$q522NdcDbp8UsEvmV1.IV.2At/vBvxi6Jmjr6jsYv3o5K88oMdx9O',
                'created_at'      => '2021-09-14 12:25:42'
            ],
        ]);
    }
}
