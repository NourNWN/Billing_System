<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $fillable=[
        'city'
    ];

    use HasFactory;

    public function locations(){
        return $this->hasMany(Location::class);
    }
}
