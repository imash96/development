<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemCost extends Model
{
    use HasFactory;

    protected $primaryKey = 'OrderItemId';
    public $incrementing = false;
    protected $keyType = 'string';

    public function orderItem()
    {
        return $this->belongsTo(OrderItems::class, 'OrderItemId', 'OrderItemId')->withDefault();
    }
}
