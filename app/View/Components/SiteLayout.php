<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class SiteLayout extends Component
{
    public string $title;

    public ?string $description;

    public ?string $image;

    public function __construct(?string $title = null, ?string $description = null, ?string $image = null)
    {
        $this->title = $title ?? config('site.name');
        $this->description = $description;
        $this->image = $image;
    }

    public function render(): View
    {
        return view('layouts.site');
    }
}
