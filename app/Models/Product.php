<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'productId';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'product';

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->productId = 'prod_' . Str::ulid();
        });
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItems::class, 'productId', 'productId')->withDefault();
    }
}
