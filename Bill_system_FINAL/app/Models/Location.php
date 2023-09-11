<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable=[
        'region_name'
    ];

    ////Relations/////////
    public function adminProfiles(){
        return $this->hasMany(AdminProfile::class);
    }

    public function State(){
        return $this->belongsTo(State::class);
    }
}
