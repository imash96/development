<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Returns extends Model
{
    use HasFactory;

    protected $primaryKey = 'returnRequestId';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo(Order::class, 'amazonOrderId', 'amazonOrderId')->withDefault();
    }
}
