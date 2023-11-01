<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return $this->belongsTo(Orders::class, 'amazonOrderId', 'amazonOrderId')->withDefault();
    }
}
