<?php

namespace Database\Seeders;

use App\Models\SystemDomain;
use Illuminate\Database\Seeder;

class SystemDomainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SystemDomain::insert([
            [
                'type'   => 'merchant-api',
                'domain' => 'api.order.riyadh-massagevip.com',
            ],
            [
                'type'   => 'admin-api',
                'domain' => 'api.order.admin.riyadh-massagevip.com',
            ],
        ]);
    }
}
