<?php

namespace App\Http\Controllers\Costumer;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Transformers\BillTransformer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CostumerPermissionController extends Controller
{
    //Approve on the request
    //localhost:8000/api/costumer/approve/{request_id} -Post-
    public function approve($id)
    {
        if (!$user=auth()->user()){
            throw new NotFoundHttpException('Uer not found');
        }

        $result=$user->costumers();

            if ($result->find($id)) {
                $result->where('costumer_id', $user->id)
                ->update(['status'=>'approve']);
                return response()->json(['message'=>'Request approved successfully'], 200);
            }
            throw new NotFoundHttpException('N0 requests!');
        }

    //Reject on the request
    //localhost:8000/api/costumer/reject/{request_id} -POST-
    public function reject($id)
    {
        if (!$user=auth()->user()){
            throw new NotFoundHttpException('Uer not found');
        }

        $result=$user->costumers();

        if ($result->find($id)) {
            $result->where('costumer_id', $user->id)
                ->update(['status'=>'reject']);
            return response()->json(['message'=>'Request rejected successfully'], 200);
        }
        throw new NotFoundHttpException('No requests!');
    }

    //Get pending requests
    //localhost:8000/api/costumer/getRequests    -GET-
    public function getRequests()
    {
        $user=Auth::user();
        $result= $user->costumers()->where('status', 'LIKE',  'pending')->get();//->pluck('admin_id');

      /*  $businessName=DB::table('admin_profiles')
           ->whereIn('user_id', $result->pluck('admin_id'))
           ->pluck('business_name');*/

        if (count($result)){
            return response()->json(['Request'=>$result /*, 'Sender requests'=>$businessName*/], 200);
        }
        throw new NotFoundHttpException('You do not have any request!');
    }

    //Get friends
    //localhost:8000/api/costumer/friends    -GET-
    public function friends()
    {
        $result= Auth::user()->costumers()->where('status', 'LIKE',  'approve')->get();
        if (count($result)){
            return response()->json(['Requests: '=>$result], 200);
        }
        throw new NotFoundHttpException('You do not have any friend!');
    }

    //Get reject requests
    //localhost:8000/api/costumer/rejectRequest    -GET-
    public function rejectRequest()
    {
        $result= Auth::user()->costumers()->where('status', 'LIKE',  'reject')->get();
        if (count($result)){
            return response()->json(['Requests: '=>$result], 200);
        }
        throw new NotFoundHttpException('You do not reject any request!');
    }

    //Get all costumers’s bills
    //localhost:8000/api/costumer/index  -GET-
    public function index()
    {
        if (!$user=auth()->user()){
            throw new NotFoundHttpException('User not found');
        }

        $userId=$user->id;
        $bills=Bill::whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->get();

        return $this->response->collection($bills, (new BillTransformer)->setDefaultIncludes(['Orders']));
    }

    //Search about bill by business_name
    //localhost:8000/api/costumer/searchBill/{business_name}  -GET-
    function searchBill($business_name)
    {
        if (!$user=auth()->user()){
            throw new NotFoundHttpException('User not found');
        }

        $result = $user->bills()->where('business_name', 'LIKE',  $business_name)->get();

        if(count($result)){
            return $this->response->item($result, new BillTransformer);
        }
        else
        {
            throw new NotFoundHttpException('Invoice does not found with business name: '.$business_name);
        }
    }

    //Delete bill
    //localhost:8000/api/costumer/destroy/{bill}   -DELETE-
    public function destroy( $id)
    {
        if (!$user=auth()->user()) {
            throw new NotFoundHttpException('User not found');
        }

        $bill=$user->bills();//->where('user_id','=', $user->id);//->find($id);

        if (!$bill->find($id)) {
            throw new NotFoundHttpException('Bill does not exits');
        }

        $invoice = $bill->where('Status','LIKE', 'paid')->get();

        if (count($invoice)) {
            try {
                $bill->delete();

                $response=[
                    'message'=>'Bill deleted successfully',
                    'id'=>$id
                ];
                return response()->json($response, 200);
            }catch (HttpException $e) {
                throw $e;
            }
        }
        throw new NotFoundHttpException('You do not pay this invoice yet!');
    }

    //Fetch all invoice paid
    //localhost:8000/api/costumer/invoicePaid  -GET-
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
    //localhost:8000/api/costumer/invoiceUnPaid  -GET-
    public function invoiceUnPaid()
    {
        $invoices = Bill::where('Status', 'unspaid')->get();

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
    //localhost:8000/api/costumer/invoicePartial  -GET-
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
}
