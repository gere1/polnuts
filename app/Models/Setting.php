<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    const FONTS = [
        'inter' => 'Inter',
        'poppins' => 'Poppins',
        'roboto' => 'Roboto',
        'nunito' => 'Nunito',
        'source-sans' => 'Source Sans 3',
    ];

    // Fonts offered inside the rich-text editor (row/product/article body content), as a
    // per-selection style rather than the site-wide body font above — includes elegant
    // italic-leaning serifs and handwriting/script faces alongside the standard set.
    // Each entry's 'weights' is the exact Google Fonts wght list actually published for that
    // family (null = single static weight, request the bare family with no wght axis).
    const RICH_TEXT_FONTS = [
        'inter' => ['label' => 'Inter', 'weights' => '400;500;600;700'],
        'poppins' => ['label' => 'Poppins', 'weights' => '400;500;600;700'],
        'roboto' => ['label' => 'Roboto', 'weights' => '400;500;600;700'],
        'nunito' => ['label' => 'Nunito', 'weights' => '400;500;600;700'],
        'source-sans' => ['label' => 'Source Sans 3', 'weights' => '400;500;600;700'],
        'playfair' => ['label' => 'Playfair Display', 'weights' => '400;600;700'],
        'cormorant' => ['label' => 'Cormorant Garamond', 'weights' => '400;500;600;700'],
        'italiana' => ['label' => 'Italiana', 'weights' => null],
        'caveat' => ['label' => 'Caveat', 'weights' => '400;600;700'],
        'dancing-script' => ['label' => 'Dancing Script', 'weights' => '400;600;700'],
        'pacifico' => ['label' => 'Pacifico', 'weights' => null],
        'great-vibes' => ['label' => 'Great Vibes', 'weights' => null],
    ];

    const LOCALES = [
        'ka' => 'ქართული',
        'en' => 'English',
        'de' => 'Deutsch',
        'pl' => 'Polski',
        'es' => 'Español',
    ];

    const BACKGROUND_MODES = [
        'gradient' => 'სქროლ-გრადიენტი',
        'solid' => 'ერთი ფერი',
    ];

    protected $fillable = [
        'site_name', 'phone', 'email', 'logo', 'show_top_bar', 'logo_height', 'favicon',
        'primary_color', 'text_color', 'header_bg_color', 'footer_bg_color', 'content_bg_color', 'font',
        'top_bar_bg_color', 'top_bar_text_color',
        'background_mode', 'gradient_top_start', 'gradient_top_end', 'gradient_bottom_start', 'gradient_bottom_end',
        'locales', 'default_locale',
    ];

    protected $casts = [
        'show_top_bar' => 'boolean',
        'locales' => 'array',
    ];

    public static function current(): self
    {
        return static::firstOrCreate(['id' => 1], [
            'site_name' => config('app.name', 'Site'),
            'default_locale' => 'ka',
            'locales' => array_diff(array_keys(self::LOCALES), ['ka']),
        ]);
    }

    public function fontFamily(): string
    {
        return self::FONTS[$this->font] ?? self::FONTS['inter'];
    }

    public static function richTextFontsQuery(): string
    {
        return collect(self::RICH_TEXT_FONTS)
            ->map(function ($font) {
                $family = str_replace(' ', '+', $font['label']);

                return 'family=' . $family . ($font['weights'] ? ':wght@' . $font['weights'] : '');
            })
            ->implode('&');
    }

    public function isLocaleEnabled(string $code): bool
    {
        return $code === $this->default_locale || in_array($code, $this->locales ?? [], true);
    }

    public function isGradientBackground(): bool
    {
        return $this->background_mode !== 'solid';
    }
}
