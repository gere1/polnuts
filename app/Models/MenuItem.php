<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class MenuItem extends Model
{
    use HasTranslations;

    public array $translatable = ['label'];

    protected $fillable = ['page_id', 'label', 'url', 'file', 'open_in_new_tab', 'position'];

    protected $casts = [
        'open_in_new_tab' => 'boolean',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function href(): string
    {
        if ($this->file) {
            return asset('storage/' . $this->file);
        }

        if ($this->page) {
            return $this->page->is_home ? localizedRoute('home') : localizedRoute('page.show', $this->page);
        }

        return $this->url ?: '#';
    }

    public function opensInNewTab(): bool
    {
        return (bool) $this->file || $this->open_in_new_tab;
    }
}
