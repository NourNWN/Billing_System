<?php

namespace Database\Seeders;

use App\Models\AdminProfile;
use App\Models\User;
use App\Models\CostumerProfile;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    { /*
        User::factory()->count(3)
            ->has(AdminProfile::factory(1))
            ->create()
            ->each(
            function ($user){
                $user->assignRole('admin');
            }
        );

       User::factory()->count(4)
            ->has(CostumerProfile::factory(1))
            ->create()
            ->each(
                function ($user){
                    $user->assignRole('costumer');
                }
            ); */
    }
}
