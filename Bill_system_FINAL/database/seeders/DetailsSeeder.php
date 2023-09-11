<?php

namespace Database\Seeders;

use App\Models\AdminProfile;
use App\Models\Bill;
use App\Models\CostumerProfile;
use App\Models\Details;
use App\Models\User;
use Illuminate\Database\Seeder;

class DetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* //Bill -2-   //Dails -3-
        $waterBillDetails=Details::factory(1);
        Bill::factory(1)
            ->has($waterBillDetails)
            ->create();


                 //Details
                 $internetBillDetails=Details::factory(1);
                 $internetBillDetails2=Details::factory(1);

                //Admin1
                $admin1Bill1=Bill::factory()
                    ->has($internetBillDetails)
                    ->has($internetBillDetails2);
                $admin1Bill2=Bill::factory()
                    ->has($internetBillDetails);
                AdminProfile::factory()->count(1)
                    ->has($admin1Bill1)
                    ->has($admin1Bill2)
                    ->create();

                //Admin2
                $admin2Bill=Bill::factory()
                    ->has($internetBillDetails2);
                AdminProfile::factory()->count(1)
                    ->has($admin2Bill)
                    ->create();

                //Costumer1
                CostumerProfile::factory()->count(1)
                    ->has($admin1Bill1)
                    ->has($admin2Bill)
                    ->create();

                //Costumer2
                CostumerProfile::factory()->count(1)
                    ->has($admin1Bill2)
                    ->create();


                  //Bill -2-   //Dails -3-
                  $waterBillDetails=Details::factory(1);
                  $waterBill=Bill::factory(1)
                                  ->has($waterBillDetails)
                                  ->hasAttached(
                                          User::factory()->count(1)
                                              ->has(CostumerProfile::factory(1))
                                              ->create()
                                              ->each(
                                                  function ($user){
                                                      $user->assignRole('costumer');
                                                  }
                                              )
                                  );

                  //Bill -3-   /Details  -4&5-
                  $mixBillDetails=Details::factory(1);//->hasAttached($waterBill);
                  $electricityBillDetails=Details::factory(1);
                  $electricityBill=Bill::factory(1)
                                          ->has($electricityBillDetails)
                                          ->has($mixBillDetails)
                                          ->hasAttached(
                                                  User::factory()->count(1)
                                                      ->has(CostumerProfile::factory(1))
                                                      ->create()
                                                      ->each(
                                                          function ($user){
                                                              $user->assignRole('costumer');
                                                          }
                                                      )
                                          );

                  //Admin of 1 & 2 bills
                  User::factory()->count(1)
                      ->has(AdminProfile::factory(1))
                      ->has($waterBill)
                      ->has($electricityBill)
                      ->create()
                      ->each(
                          function ($user){
                              $user->assignRole('admin');
                          }
                      );






                /*  $waterDetailsBill=Details::factory(1);
                  $electricityDetailsBill=Details::factory(1);
                  Bill::factory()->count(1)
                                 ->has($waterDetailsBill)
                                 ->has($electricityDetailsBill)
                                 ->create();

                 /* $electricityDetailsBill=Details::factory(1);
                  Bill::factory(1)
                                  ->count(1)
                                  ->has($electricityDetailsBill)
                                  ->create();*/

    }
}
