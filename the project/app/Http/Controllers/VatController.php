<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;

class VatController extends Controller
{
    //Calculation the subTotal, value vat and total price
    public function calculator(Request $request, $billId)
    {
        $order=Order::whereHas('bills', function ($query) use ($billId) {
            $query->where('bill_id', $billId);
        });

        $rules=[
          'subTotal',
          'value_vat',
          'totalPrice',
        ];

        $validator=Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['validation_errors'=>$validator->errors()], 400);
        }

        $bill=Bill::find($billId);
        try {
            $vat=Bill::find($billId)
                ->vat()
                ->Create([
                    'subTotal'=>$order->sum('total'),
                    'value_vat'=>($order->sum('total')/100) * $bill->rate_vat,
                    'totalPrice'=>$order->sum('total') + (($order->sum('total')/100) * $bill->rate_vat),
                ]);
        }catch (HttpException $e) {
            throw $e;
        }

        $response=[
            'bill_number'=>$bill->bill_number,
            'subTotal'=>$vat->subTotal,
            'value_vat'=>$vat->value_vat,
            'total price'=>$vat->totalPrice,
        ];

        return response()->json($response, 200);

    }
}
