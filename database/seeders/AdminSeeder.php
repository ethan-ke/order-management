<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::insert([
            [
                'username' => 'wuli',
                'password' => '$2y$10$6bgGWMCtA51v/1F/gmbAyeqCVyQJEa2qNlQQ3KJhVyj6KKvyEwEqq',
            ],
        ]);
    }
}
