<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{

    use HasFactory;

    // In Category model
    public $incrementing = true;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'description'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (! $model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class);
        
    }
   
}
