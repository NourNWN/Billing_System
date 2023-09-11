<?php

namespace Database\Seeders;

use App\Models\AdminProfile;
use App\Models\CostumerProfile;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       /* AdminProfile::factory()->count(1)
            ->has(AdminProfile::factory(1))
            ->create()
            ->each(
                function ($user){
                    $user->assignRole('admin');
                }
            );*/
    }
}
