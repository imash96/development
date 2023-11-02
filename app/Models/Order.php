<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = 'orderId';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'order';

    protected $casts = [
        'cancellationDate' => 'timestamp',
        'earliestDeliveryDate' => 'timestamp',
        'earliestShipDate' => 'timestamp',
        'latestDeliveryDate' => 'timestamp',
        'latestShipDate' => 'timestamp',
        'purchaseDate' => 'timestamp',
    ];

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->orderId = 'ordr_' . Str::ulid();
        });
    }

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

    public function orderItem()
    {
        return $this->hasMany(OrderItem::class, 'orderId', 'orderId');
    }

    public function packages()
    {
        return $this->hasMany(Package::class, 'orderId', 'orderId');
    }

    public function returns()
    {
        return $this->hasMany(Returns::class, 'amazonOrderId', 'amazonOrderId')->withDefault();
    }
}
