<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OrderCost extends Model
{
    use HasFactory;

    protected $primaryKey = 'amazonOrderId';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'order_cost';

    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo(Order::class, 'amazonOrderId', 'amazonOrderId')->withDefault();
    }
}
