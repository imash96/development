<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $primaryKey = 'OrderItemId';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'order_item';

    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo(Order::class, 'orderId', 'order_id')->withDefault();
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
