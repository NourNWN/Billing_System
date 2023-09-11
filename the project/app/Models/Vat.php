<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Vat extends Model
{
    use HasFactory;

    protected $fillable= [
        'subTotal',
        'value_vat',
        'totalPrice',
    ];

    public function bill()
    {
        return $this->belongsTo(Bill::class, 'bill_id');
    }


}
