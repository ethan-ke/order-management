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
                'username'         => 'Ethan',
                'commission_ratio' => 0.1,
                'password'         => '$2y$10$q522NdcDbp8UsEvmV1.IV.2At/vBvxi6Jmjr6jsYv3o5K88oMdx9O',
            ]
        ]);
    }
}
