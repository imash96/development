<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addresses extends Model
{
    use HasFactory;

    protected $primaryKey = 'addressId';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo(Order::class, 'addressId', 'addressId')->withDefault();
    }
}
