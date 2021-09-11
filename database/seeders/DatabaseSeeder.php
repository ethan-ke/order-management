<?php

namespace Database\Seeders;

use App\Models\SystemDomain;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AdminSeeder::class,
            MerchantSeeder::class,
            SystemDomainSeeder::class,
        ]);
    }
}
