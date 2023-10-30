<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    use HasFactory;

    protected $primaryKey = 'OrderItemId';
    public $incrementing = false;
    protected $keyType = 'string';

    public function order()
    {
        return $this->belongsTo(Orders::class, 'OrderItemId', 'OrderItemId')->withDefault();
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'productId', 'productId')->withDefault();
    }

    public function itemCost()
    {
        return $this->hasOne(ItemCost::class, 'OrderItemId', 'OrderItemId')->withDefault();
    }
}
