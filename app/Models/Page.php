<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Page extends Model
{
    use HasTranslations;

    public array $translatable = ['title', 'meta_description'];

    protected $fillable = ['title', 'slug', 'meta_description', 'is_home'];

    protected $casts = [
        'is_home' => 'boolean',
    ];

    public function rows()
    {
        return $this->hasMany(Row::class)->orderBy('position');
    }

    public function sharedRows()
    {
        return $this->belongsToMany(Row::class, 'page_row')->withPivot('position')->withTimestamps();
    }

    public function displayRows()
    {
        $own = $this->rows()->with('items')->get();
        $shared = $this->sharedRows()->with('items')->get();

        return $own->concat($shared)->sortBy(function ($row) {
            return $row->pivot->position ?? $row->position;
        })->values();
    }
}
