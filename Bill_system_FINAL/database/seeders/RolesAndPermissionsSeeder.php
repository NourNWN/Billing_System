<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        //Admin’s permissions
        $viewUser='view all users';
        $addBill='create new bill';
        $sendBill='send bill';
        $editBill='edit bill';
        $searchBill='search about bill'; //also available for costumer
        $searchCostumer='search about costumer';
        $sendRequest='send a request for costumer';

        //Costumer’s permissions
        $deleteBill='delete paid bills';
        $approveRequest='approve or ignore the request';
        $browseBills='browse all received bills';
        $viewBillDetails='view bill’s details';
        $uploadBill='upload bill';

        //Create permissions for my system
        Permission::create(['name'=>$viewUser]);
        Permission::create(['name'=>$addBill]);
        Permission::create(['name'=>$sendBill]);
        Permission::create(['name'=>$editBill]);
        Permission::create(['name'=>$searchBill]);
        Permission::create(['name'=>$searchCostumer]);
        Permission::create(['name'=>$sendRequest]);

        Permission::create(['name'=>$deleteBill]);
        Permission::create(['name'=>$approveRequest]);
        Permission::create(['name'=>$browseBills]);
        Permission::create(['name'=>$viewBillDetails]);
        Permission::create(['name'=>$uploadBill]);

        //Define roles available
        $admin='admin';
        $costumer='costumer';

        Role::create(['name'=>$admin])
            ->givePermissionTo([
                $viewUser,
                $addBill,
                $sendBill,
                $editBill,
                $searchBill,
                $searchCostumer,
                $sendRequest,
            ]);

        Role::create(['name'=>$costumer])
            ->givePermissionTo([
                $deleteBill,
                $searchBill,
                $approveRequest,
                $browseBills,
                $viewBillDetails,
                $uploadBill,
            ]);


    }
}
