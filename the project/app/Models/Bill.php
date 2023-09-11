<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Bill extends Model
{
    use HasFactory, softDeletes;

    protected $fillable= [
        'business_name',
        'user_name',
        'logo',
        'bill_number',
        'date_of_create',
        'due_date',
        'rate_vat',
        'status',
    ];

                ///////////////Relations////////////
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_bill');
    }

    public function orders():BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'bill_orders');
    }

    public function vat()
    {
        return $this->hasOne(Vat::class, 'bill_id');
    }

        ///////Auto increment bill number//////
       public static function boot()
       {
           parent::boot();

           self::creating(function ($bill) {
               $projectCount = Bill::where('bill_number', 'LIKE', '%-'. date('Y'))->count();
               $projectCount++;
               $bill->bill_number = 'INV-'. str_pad($projectCount, 5, '0', STR_PAD_LEFT) .'-'.date('Y');
               return true;
       });
    }

}
