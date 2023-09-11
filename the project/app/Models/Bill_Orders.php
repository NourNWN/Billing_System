<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Concerns\AsPivot;

class Bill_Orders extends Model
{
    use HasFactory, AsPivot;

    protected $fillable=[
        'bill_id',
        'order_id'
    ];
}
