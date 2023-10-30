<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;

    protected $primaryKey = 'amazonOrderId';
    public $incrementing = false;
    protected $keyType = 'string';

    public function customer()
    {
        return $this->hasOne(Customer::class, 'customerId', 'customerId')->withDefault();
    }

    public function addresses()
    {
        return $this->hasOne(Addresses::class, 'addressId', 'addressId')->withDefault();
    }

    public function orderCost()
    {
        return $this->hasOne(OrderCost::class, 'amazonOrderId', 'amazonOrderId')->withDefault();
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItems::class, 'OrderItemId', 'OrderItemId')->withDefault();
    }

    public function packages()
    {
        return $this->hasMany(Packages::class, 'PackageId', 'PackageId')->withDefault();
    }

    public function returns()
    {
        return $this->hasMany(Returns::class, 'amazonOrderId', 'amazonOrderId')->withDefault();
    }
}
