<?php

namespace Database\Seeders;

use App\Models\User;
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
             RolesAndPermissionsSeeder::class,
             UserSeeder::class,
             BillSeeder::class,
             OrderSeeder::class,
             CostumerProfileSeeder::class,
             AdminProfileSeeder::class,
         ]);
    }
}
