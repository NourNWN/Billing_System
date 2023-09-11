<?php

namespace App\Transformers;

use App\Models\Bill;
use League\Fractal\TransformerAbstract;

class BillTransformer extends TransformerAbstract
{
    public function transform(Bill $bill)
    {
       return [
           'id'=>(int) $bill->id,
           'bill_number'=>$bill->bill_number,
           'user_name'=>$bill->user_name,
           'business_name'=>$bill->business_name,
           'logo'=>$bill->logo,
           'date_of_create'=>$bill->date_of_create,
           'due_date'=>$bill->due_date,
           'subTotal'=>$bill->vat->subTotal,
           'rate_vat'=>$bill->rate_vat,
           'value_vat'=>$bill->vat->value_vat,
           'totalPrice'=>$bill->vat->totalPrice,
           'status'=>$bill->status,
       ];
    }

    public function includeOrders (Bill $bill)
    {
       return $this->collection($bill->orders, new OrderTransformer);
    }
}
