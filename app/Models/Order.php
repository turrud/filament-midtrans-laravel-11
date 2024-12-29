<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasUuids;
    protected $guarded = [];
    protected $casts = ['id' => 'string'];
    protected $keyType = 'string';

    public $incrementing = false; // Non-incrementing primary key

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
