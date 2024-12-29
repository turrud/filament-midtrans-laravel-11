<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Package extends Model
{
    public $incrementing = false;
    protected $casts = ['id' => 'string'];
    protected $keyType = 'string';

    use SoftDeletes;


    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = (string) Str::uuid();
            }
        });
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function facilities()
    {
        return $this->hasMany(Facility::class);
    }
}