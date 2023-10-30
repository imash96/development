<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Customer extends Model
{
    use HasFactory;

    protected $primaryKey = 'customerId';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'customer';

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->customerId = 'cust_' . Str::ulid();
        });
    }

    public function order()
    {
        return $this->belongsTo(Orders::class, 'customerId', 'customerId')->withDefault();
    }
}
