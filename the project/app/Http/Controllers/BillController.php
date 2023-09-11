<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\CostumerProfile;
use App\Models\User;
use App\Transformers\BillTransformer;
use App\Transformers\CostumerTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Dingo\Api\Http\Response
     */

    //Get all admin’s bills
    //localhost:8000/api/admin/bills  -GET-
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreBillRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */

    //Create bill
    //localhost:8000/ api/admin/bills  -POST-
    public function store(Request $request)
    {
        $rules = [
            'bill_number',
            'business_name',
            'user_name'=>[
               'required', 'string', 'exists:App\Models\CostumerProfile,user_name'
            ],
            'logo',
            'date_of_create' => ['date_format:Y-m-d'],
            'due_date' => ['required','date_format:Y-m-d','after:now'],
            'rate_vat'=>['required'],
            'status' => [
                Rule::in(['unpaid', 'paid', 'partially_paid']),
            ],
        ];
        $validator = Validator::make($request->all(), $rules);

         if ($validator->fails()) {
             return response()->json(['validation_errors'=>$validator->errors()], 400);
         }

        if (!$user = auth()->user()) {
            throw new NotFoundHttpException('User not found');
        }

        $bill=$user->bills()->Create([
                'bill_number'=>Bill::boot(),
                'business_name' => $user->adminProfile->business_name,
                'user_name'=> $request->user_name,
                'logo'=>$user->adminProfile->logo,
                'date_of_create'=>$request->date_of_create,
                'due_date'=>$request->due_date,
                'rate_vat'=>$request->rate_vat,
                'status'=>$request->status,
            ]);

            $response = [
                'message' => 'Bill created successfully',
                'bill_id' => $bill->id,
            ];

            return response()->json($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bill  $bill
     * @return \Illuminate\Http\Response
     */

            ////////////Unused/////////////////
    //localhost:8000/api/admin/bills/{id}  -GET-
    public function show($id)
    {
        if (!$user=auth()->user()){
            throw new NotFoundHttpException('User not found');
        }

        $bills=Bill::where('id', $user->id)->find($id);

        return $this->response->item($bills, new BillTransformer);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBillRequest  $request
     * @param  \App\Models\Bill  $bill
     * @return \Illuminate\Http\JsonResponse
     */

    //Update the bill
    //localhost:8000/api/admin/bills/{bill}  -PUT-
    public function update(Request $request,  $id)
    {
        if (!$user=auth()->user()) {
            throw new NotFoundHttpException('User not found');
        }

        $userId=$user->id;
        $bill=Bill::whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->find($id);

        if (!$bill) {
            throw new NotFoundHttpException('Bill does not exit');
        }

        if (!empty($request->bill_number)) {
            $validator=Validator::make($request->all(), [
                'bill_number'=>'required|unique:bills,bill_number',
            ]);

            if ($validator->fails()) {
                return response()->json(['validation error'=>$validator->errors()], 400);
            }

            $bill->bill_number=$request->bill_number;
        }

        if (!empty($request->user_name)) {
            $validator=Validator::make($request->all(), [
                'user_name'=>'required', 'string', 'exists:App\Models\CostumerProfile,user_name',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $bill->user_name=$request->user_name;
        }

        if (!empty($request->due_date)) {
            $validator=Validator::make($request->all(), [
                'due_date'=>'required','date_format:Y-m-d','after:now',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $bill->due_date=$request->due_date;
        }

        if (!empty($request->rate_vat)) {
            $validator=Validator::make($request->all(), [
                'rate_vat'=>'required',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $bill->rate_vat=$request->rate_vat;
        }

        if (!empty($request->status)) {
            $validator=Validator::make($request->all(), [
                'status'=>['required', Rule::in(['unpaid', 'paid', 'partially_paid'])],
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $bill->status=$request->status;
        }

        try {
            if ($bill->isDirty()) {
                $bill->save();

                $response=[
                    'message'=>'Bill updated successfully',
                    'serial number'=>$bill->bill_number
                ];

                return response()->json($response, 200);
            }

            return response()->json(['message'=>'Nothing to update'], 200);
        } catch (HttpException $e) {
            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bill  $bill
     * @return \Illuminate\Http\JsonResponse
     */

    //Delete bill
    //localhost:8000/api/admin/bills/{bill}   -DELETE-
    public function destroy( $id)
    {

        if (!$user=auth()->user()) {
            throw new NotFoundHttpException('User not found');
        }

        $bill=Bill::where('id', $user->id)->find($id);

        if (!$bill) {
            throw new NotFoundHttpException('Bill does not exits');
        }

        try {
            $bill->delete();

            $response=[
                'message'=>'Bill deleted successfully',
                'serial number'=>$bill->bill_number
            ];
            return response()->json($response, 200);
        }catch (HttpException $e) {
            throw $e;
        }
    }
}
