<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class RowItem extends Model
{
    use HasTranslations;

    public array $translatable = ['title', 'subtitle', 'body'];

    protected $fillable = ['row_id', 'position', 'image', 'image_height', 'title', 'subtitle', 'body', 'link', 'align', 'width'];

    public function row()
    {
        return $this->belongsTo(Row::class);
    }
}
