<?php

use App\Http\Controllers\Admin\AdminPermissionController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\Costumer\CostumerPermissionController;
use App\Http\Controllers\Costumer\CostumerProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\VatController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function($api) {

                ///////////AUTH////////////
    $api->group(['prefix' => 'auth'], function ($api) {
        $api->post('/signup', [AuthController::class, 'signup']);
        $api->post('/login', [AuthController::class, 'login']);

        $api->group(['middleware' => 'api.auth'], function ($api) {
            $api->post('/token/refresh', [AuthController::class, 'refresh']);
            $api->post('/logout', [AuthController::class, 'logout']);
        });
    });

                ////////////////COSTUMER/////////////////
    $api->group(['middleware' => ['role:costumer'], 'prefix' => 'costumer'], function ($api) {

                     ////////Costumer Profile//////////////
        $api->get('/getprofile', [CostumerProfileController::class, 'getProfile']);
        $api->post('/editprofile', [CostumerProfileController::class, 'editProfile']);
        $api->put('/updateprofile', [CostumerProfileController::class, 'updateProfile']);
        $api->delete('/deleteAccount', [CostumerProfileController::class, 'deleteAccount']);

                         //////////Requests////////////
        $api->post('/approve/{friend_id}', [CostumerPermissionController::class, 'approve']);
        $api->post('/reject/{friend_id}', [CostumerPermissionController::class, 'reject']);
        $api->get('/getRequests', [CostumerPermissionController::class, 'getRequests']);
        $api->get('/friends', [CostumerPermissionController::class, 'friends']);
        $api->get('/rejectRequest', [CostumerPermissionController::class, 'rejectRequest']);

                         //////////Bills//////////////
        $api->get('/index', [CostumerPermissionController::class, 'index']);
        $api->get('/searchBill/{business_name}', [CostumerPermissionController::class, 'searchBill']);
        $api->delete('/destroy/{bill_id}', [CostumerPermissionController::class, 'destroy']);
        $api->get('/invoicePaid', [CostumerPermissionController::class, 'invoicePaid']);
        $api->get('/invoiceUnPaid', [CostumerPermissionController::class, 'invoiceUnPaid']);
        $api->get('/invoicePartial', [CostumerPermissionController::class, 'invoicePartial']);

    });
                /////////////////ADMIN/////////////////
    $api->group(['middleware' => ['role:admin'], 'prefix' => 'admin'], function ($api) {

                        /////////Admin profile///////////
        $api->get('/getprofile', [AdminProfileController::class, 'getProfile']);
        $api->post('/updateProfile', [AdminProfileController::class, 'updateProfile']);
        $api->put('/editprofile', [AdminProfileController::class, 'editProfile']);
        $api->delete('/deleteAccount', [AdminProfileController::class, 'deleteAccount']);

                     ////////Costumer, Request//////////
        $api->get('/searchCostumer/{user_name}', [AdminPermissionController::class, 'searchCostumer']);
        $api->post('/addCostumer/{id}', [AdminPermissionController::class, 'addCostumer']);
        $api->post('/sendBill/{costumer_id}/bill/{bill_id}', [AdminPermissionController::class, 'sendBill']);

                        /////////////Get bills///////////
        $api->get('/searchBill/{user_name}', [AdminPermissionController::class, 'searchBill']);
        $api->get('/invoicePaid', [AdminPermissionController::class, 'invoicePaid']);
        $api->get('/invoiceUnPaid', [AdminPermissionController::class, 'invoiceUnPaid']);
        $api->get('/invoicePartial', [AdminPermissionController::class, 'invoicePartial']);


        $api->post('users/{id}/suspend', [AdminPermissionController::class, 'suspend']);
        $api->post('users/{id}/activate', [AdminPermissionController::class, 'activate']);


        /*   $api->resource('users', AdminController::class);
           $api->get('users/{id}/roles', [AdminRoleController::class, 'show']);
           $api->get('users/{id}/permissions', [AdminPermissionController::class, 'show']);
           $api->post('users/{id}/roles', [AdminRoleController::class, 'changeRole']);*/

                    //////////Create bill/////////////
        $api->post('bills', [BillController::class, 'store']);
        $api->get('bills', [BillController::class, 'index']);
        $api->group(['middleware' => 'isBillAdmin'], function ($api) {
            $api->resource('bills', BillController::class, ['except' => ['store', 'index']]);
            $api->resource('bills/{bills}/orders', OrderController::class);
            $api->post('bills/{bills}/vats', [VatController::class, 'calculator']);
        });

    });
});
