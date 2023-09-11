<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    use HasFactory;

    protected $fillable=[
        'admin_id',
        'costumer_id',
        'status'
    ];

    public function adminUser()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function costumerUser()
    {
        return $this->belongsTo(User::class, 'costumer_id');
    }
}
