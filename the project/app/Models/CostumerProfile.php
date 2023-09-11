<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CostumerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_name',
        'first_name',
        'last_name',
        'phone_number',
        //'active',
    ];


    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
