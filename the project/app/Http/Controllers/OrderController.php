<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Order;
use App\Transformers\OrderTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //Get all orders
    //localhost:8000/api/admin/bills/{billId}/orders  -GET-
    public function index($billId)
    {
        $order=Order::whereHas('bills', function ($query) use ($billId) {
           $query->where('bill_id', $billId);
        })
        ->get();

        if (empty($order)){
            throw new NotFoundHttpException('Order dose not exist');
        }

        return $this->response->collection($order, new OrderTransformer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreDetailsRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */

    //Create order
    //localhost:8000/api/admin/bills/{billId}/orders  -POST-
    public function store(Request $request, $billId)
    {
        $rules=[
          'product'=>[
              'required', 'string'
          ]  ,
            'quantity'=>[
                'required'
            ],
            'price_one'=>[
                'required',
            ],
            'total',
        ];

        $validator=Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['validation_errors'=>$validator->errors()], 400);
        }

        try {
            $order=Bill::find($billId)
                        ->orders()
                        ->Create([
                            'product'=>$request->product,
                            'quantity'=>$request->quantity,
                            'price_one'=>$request->price_one,
                            'total'=>$request->quantity * $request->price_one,
                        ]);
        }catch (HttpException $e) {
            throw $e;
        }

        $response=[
            'message'=>'Order creation is successfully',
            'id'=>$order->id,
            'total'=>$order->total,
        ];

        return response()->json($response, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $billId
     * @return \Illuminate\Http\Response
     */

    //Get specific order
    //localhost:8000api/admin/bills/{bills}/orders/{order}  -GET-
    public function show($billId, $id)
    {
        $order=Order::whereHas('bills', function ($query) use ($billId) {
            $query->where('bill_id', $billId);
        })
        ->find($id);

        if (empty($order)){
            throw new NotFoundHttpException('Order dose not exist');
        }

        return $order;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDetailsRequest  $request
     * @param  \App\Models\Order  $billId
     * @return \Illuminate\Http\JsonResponse
     */

    //Update order
    //localhost:8000api/admin/bills/{bills}/orders/{order}  -PUT-
    public function update(Request $request, $billId, $id)
    {
        $order=Order::whereHas('bills', function ($query) use ($billId) {
           $query->where('bill_id', $billId);
        })
        ->find($id);

        if (empty($order)){
            throw new NotFoundHttpException('Order does not found');
        }

        if (!empty($request->product)){
            $validator=Validator::make($request->all(), [
                'product'=>'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['validation_errors'=>$validator->errors()], 400);
            }

            $order->product=$request->product;
        }

        if (!empty($request->quantity)){
            $validator=Validator::make($request->all(), [
                'quantity'=>'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['validation_errors'=>$validator->errors()], 400);
            }

            $order->quantity=$request->quantity;
        }

        if (!empty($request->price_one)){
            $validator=Validator::make($request->all(), [
                'price_one'=>'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['validation_errors'=>$validator->errors()], 400);
            }

            $order->price_one=$request->price_one;
        }

        try {
            if ($order->isDirty()){
                $order->save();

                $response=[
                    'message'=>'Order updation is successfully',
                    'id'=>$id,
                ];

                return response()->json($response, 200);
            }

            return response()->json(['message'=>'Nothing to updating',], 200);
        }catch (HttpException $e) {
            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $details
     * @return \Illuminate\Http\JsonResponse
     */

    //Delete order
    //localhost:8000api/admin/bills/{bills}/orders/{order}  -Delete-
    public function destroy($billId, $id)
    {
        $order=Order::whereHas('bills', function ($query) use ($billId) {
            $query->where('bill_id', $billId);
        })
        ->find($id);

        if (empty($order)){
            throw new NotFoundHttpException('Order not found');
        }

        try {
           $order->delete();
        }catch (HttpException $e) {
            throw $e;
        }

        $response=[
            'message'=>'Order deletion is successfully',
            'id'=>$id,
            'bill_id'=>$billId,
        ];

        return response()->json($response, 200);
    }

}
