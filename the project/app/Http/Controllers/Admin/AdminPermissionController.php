<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\CostumerProfile;
use App\Models\User;
use App\Models\User_Bill;
use App\Transformers\BillTransformer;
use App\Transformers\CostumerTransformer;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminPermissionController extends Controller
{
    //Search about costumer by his username
    //localhost:8000/api/admin/searchCostumer/{user_name}  -GET-
    function searchCostumer($user_name)
    {
        $result = CostumerProfile::where('user_name', 'LIKE',  $user_name)->get();

        if(count($result)){
            return $this->response->item($result, new CostumerTransformer);
        }
        else
        {
            throw new NotFoundHttpException('User not found with username: '.$user_name);
        }
    }

    //Search about bill by username
    //localhost:8000/api/admin/searchBill/{user_name}  -GET-
    function searchBill($user_name)
    {
        if (!$user=auth()->user()){
            throw new NotFoundHttpException('User not found');
        }

        $result = $user->bills()->where('user_name', 'LIKE',  $user_name)->get();

        if(count($result)){
            return $this->response->item($result, new BillTransformer);
        }
        else
        {
            throw new NotFoundHttpException('Invoice does not found with username: '.$user_name);
        }
    }

    //Fetch all invoice paid
    //localhost:8000/api/admin/invoicePaid  -GET-
    function invoicePaid()
    {
        if (!auth()->user()){
            throw new NotFoundHttpException('User not found');
        }

        $invoices = Bill::where('Status','paid')->get();

        if (count($invoices))
        {
            return $this->response->collection($invoices, (new BillTransformer)->setDefaultIncludes(['Orders']));
        }
        else
        {
            throw new NotFoundHttpException('No invoices paid');
        }

    }

    //Fetch all invoice unpaid
    //localhost:8000/api/admin/invoiceUnPaid  -GET-
    public function invoiceUnPaid()
    {
        $invoices = Bill::where('Status', 'unpaid')->get();

        if (count($invoices))
        {
            return $this->response->collection($invoices, (new BillTransformer)->setDefaultIncludes(['Orders']));
        }
        else
        {
            throw new NotFoundHttpException('No invoices unpaid');
        }
    }

    //Fetch all invoice partially paid
    //localhost:8000/api/admin/invoicePartial  -GET-
    public function invoicePartial()
    {
        $invoices = Bill::where('Status','partially_paid')->get();

        if (count($invoices))
        {
            return $this->response->collection($invoices, (new BillTransformer)->setDefaultIncludes(['Orders']));
        }
        else
        {
            throw new NotFoundHttpException('No invoices partially paid');
        }
    }

    //Add costumer
    //localhost:8000/api/admin/addCostumer/{user_name}  -POST-
    public function addCostumer($user_name)
    {
        if (!$user=auth()->user()){
            throw new NotFoundHttpException('User not found');
        }

        $result = DB::table('costumer_profiles')
                    ->where('user_name', 'LIKE',  $user_name)
                    ->value('user_id');

        if($result){
            $request=$user->admins()->create(['costumer_id'=>$result]);
            $request->save();
            return response()->json([
                'message'=>'Costumer added successfully',
            'id'=>$request->id,
            ], 200);
        }
        else
        {
            throw new NotFoundHttpException('User not found with username: '.$user_name);
        }

    }

    //Send bill
    //localhost:8000/api/admin/sendBill/{costumer_id}/bill/{bill_id}  -POST-
    public function sendBill($user_name, $billId)
    {
        if (!$user=auth()->user()){
            throw new NotFoundHttpException('User not found');
        }

        $costumerId = DB::table('costumer_profiles')
            ->where('user_name', 'LIKE',  $user_name)
            ->value('user_id');

        $result= $user->admins();
        $result1= $result->where('costumer_id', '=', $costumerId)
                        ->where('status', 'LIKE', 'approve')->get();

        if (count($result1)) {
            $admin=new User();
            $admin->bills()->attach(['bill_id'=>$billId], ['user_id'=>$costumerId]);

            return response()->json([
                'massage'=>'invoice send successfully',
                'invoice_id'=>$billId,
            ], 200);
        }
        throw new NotFoundHttpException('You cannot send invoices to the costumer with id:: '.$costumerId);
    }


            ///////not used////////
    //Delete user
    //localhost:8000/api/admin/users/{id}  - DELETE
    public function destroy($id)
    {
        if (!$user=User::find($id)){
            return new NotFoundHttpException('User not found');
        }

        try {

            $user->delete();

        }catch (HttpException $e){
            throw $e;
        }

        return response()->json(['message'=>'User deleted successfully', 'id'=>$id], 200);
    }

    //Inactivate User
    //localhost:8000/api/admin/users/{id}/suspend  - POST
    public function suspend($id)
    {
        if (!$user=User::find($id)){
            return new NotFoundHttpException('User not found');
        }
        try {
            $user->userProfile->UpdateOrCreate(['user_id'=>$id], [
                'active'=>false
            ]);
        }catch (HttpException $e){
            throw $e;
        }

        $response=[
            'message'=>'User suspended successfully',
            'id'=>$id
        ];

        return response()->json($response, 200);
    }

    //Activate user
    //localhost:8000/api/admin/users/{id}/activate  - POST
    public function activate($id)
    {
        if (!$user=User::find($id)){
            return new NotFoundHttpException('User not found');
        }
        try {
            $user->userProfile->UpdateOrCreate(['user_id'=>$id], [
                'active'=>true
            ]);
        }catch (HttpException $e){
            throw $e;
        }

        $response=[
            'message'=>'User created successfully',
            'id'=>$id
        ];

        return response()->json($response, 200);
    }
}
