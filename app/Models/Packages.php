<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Packages extends Model
{
    use HasFactory;

    protected $primaryKey = 'PackId';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [];

    protected $casts = [
        'ShipDate' => 'timestamp',
        'pickupDate' => 'timestamp',
        'delivereyDate' => 'timestamp',
        'estimatedTimeOfDelivery' => 'timestamp',
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->packId = 'pack_' . Str::ulid();
        });
    }

    public function order()
    {
        return $this->belongsTo(Orders::class, 'amazonOrderId', 'amazonOrderId')->withDefault();
    }
}
