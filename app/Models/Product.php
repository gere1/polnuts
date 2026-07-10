<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Product extends Model
{
    use HasTranslations;

    public array $translatable = ['name', 'excerpt', 'body'];

    protected $fillable = ['name', 'slug', 'price', 'image', 'excerpt', 'body', 'position'];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function scopeOrdered($query)
    {
        return $query->orderBy('position')->orderBy('id');
    }
}
