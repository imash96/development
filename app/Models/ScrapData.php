<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ScrapData extends Model
{
    use HasFactory;

    protected $primaryKey = 'scrap_id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'scrap_data';

    protected $guarded = [];

    protected $casts = [
        'last_scrap' => 'timestamp',
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->scrap_id = 'scrp_' . Str::ulid();
        });
    }
}
