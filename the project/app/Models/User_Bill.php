<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Concerns\AsPivot;

class User_Bill extends Model
{
    use HasFactory, AsPivot;

    protected $fillable=[
        'user_id',
        'bill_id'
    ];
}
