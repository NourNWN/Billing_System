<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AdminProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_name',
        'phone_number',
        'city',
        'region',
        'logo',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

      public function costumers(): BelongsToMany
      {
          return $this->belongsToMany(AdminProfile::class,
              'friends',
              'admin_id', 'costumer_id');
      }
}
