<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable=[
      'product',
      'quantity',
      'price_one',
      'total',
    ];

    /**
     * @template TRelatedModel of \Illuminate\Database\Eloquent\Model
     * @extends Relation<TRelatedModel>
     *      @return BelongsToMany<Users>
     */

    public function bills(): BelongsToMany
    {
        return $this->belongsToMany(Bill::class, 'bill_orders');
    }

}
