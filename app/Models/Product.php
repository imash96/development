<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'productId';
    public $incrementing = false;
    protected $keyType = 'string';

    public function orderItem()
    {
        return $this->belongsTo(OrderItems::class, 'productId', 'productId')->withDefault();
    }
}
