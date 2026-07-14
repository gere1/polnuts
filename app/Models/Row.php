<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Row extends Model
{
    use HasTranslations;

    const TYPES = ['slider', 'news', 'text', 'products', 'map', 'form', 'image'];

    public array $translatable = ['title', 'subtitle', 'body'];

    protected $fillable = ['page_id', 'type', 'position', 'title', 'subtitle', 'body', 'settings'];

    protected $casts = [
        'settings' => 'array',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function sharedPages()
    {
        return $this->belongsToMany(Page::class, 'page_row')->withPivot('position')->withTimestamps();
    }

    public function items()
    {
        return $this->hasMany(RowItem::class)->orderBy('position');
    }

    public function newsLimit(): int
    {
        return (int) ($this->settings['limit'] ?? 3);
    }

    public function itemLimit(): int
    {
        return (int) ($this->settings['limit'] ?? 3);
    }

    public function layout(): string
    {
        return $this->settings['layout'] ?? 'grid';
    }

    const CARD_STYLES = ['bordered', 'frameless', 'flip'];

    public function cardStyle(): string
    {
        $value = $this->settings['card_style'] ?? 'bordered';

        return in_array($value, self::CARD_STYLES, true) ? $value : 'bordered';
    }

    public function productImageHeight(): int
    {
        return (int) ($this->settings['image_height'] ?? 288);
    }

    public function sliderStyle(): string
    {
        return $this->settings['style'] ?? 'center';
    }

    const TEXT_ANIMATIONS = ['none', 'fade-up', 'typewriter', 'stagger', 'shimmer', 'rotate'];

    public function textAnimation(): string
    {
        $value = $this->settings['text_animation'] ?? 'none';

        return in_array($value, self::TEXT_ANIMATIONS, true) ? $value : 'none';
    }

    // Hero slider (columns<=1) entrance effects — see resources/css/app.css's
    // .hero-media[data-effect]/.hero-content[data-effect] rules, gated on .swiper-slide-active.
    const HERO_IMAGE_EFFECTS = ['none', 'zoom-out', 'zoom-in', 'pan-left', 'pan-right'];

    public function heroImageEffect(): string
    {
        $value = $this->settings['hero_image_effect'] ?? 'zoom-out';

        return in_array($value, self::HERO_IMAGE_EFFECTS, true) ? $value : 'zoom-out';
    }

    const HERO_TEXT_EFFECTS = ['none', 'fade-up', 'fade', 'zoom-in', 'slide-left', 'slide-right'];

    public function heroTextEffect(): string
    {
        $value = $this->settings['hero_text_effect'] ?? 'fade-up';

        return in_array($value, self::HERO_TEXT_EFFECTS, true) ? $value : 'fade-up';
    }

    // "rotate" cycles through several short phrases (typed in, paused, erased, next — looping).
    // The phrases reuse the row's items list, but only when that list isn't already spoken for
    // by the row's own type (slider slides, image gallery, multi-column text) — otherwise there's
    // nowhere to store them and it just cycles the single row title.
    public function usesItemsForOwnType(): bool
    {
        return in_array($this->type, ['slider', 'image'], true) || ($this->type === 'text' && $this->columnCount() > 1);
    }

    public function rotatingTexts(): array
    {
        if (! $this->usesItemsForOwnType()) {
            $texts = $this->items->pluck('title')->filter()->values()->all();

            if (count($texts) > 0) {
                return $texts;
            }
        }

        return $this->title ? [$this->title] : [];
    }

    const SLIDER_HEIGHTS = [
        'small' => 'h-[35vh] min-h-[280px]',
        'medium' => 'h-[60vh] min-h-[360px]',
        'large' => 'h-[80vh] min-h-[480px]',
        'full' => 'h-screen',
    ];

    public function sliderHeightClass(): string
    {
        $key = $this->settings['height'] ?? 'medium';

        return self::SLIDER_HEIGHTS[$key] ?? self::SLIDER_HEIGHTS['medium'];
    }

    public function columnCount(): int
    {
        return (int) ($this->settings['columns'] ?? 3);
    }

    const TEXT_HEIGHTS = [
        'auto' => '',
        'small' => 'min-h-[35vh]',
        'medium' => 'min-h-[60vh]',
        'large' => 'min-h-[80vh]',
        'full' => 'min-h-screen',
    ];

    public function textHeightClass(): string
    {
        $key = $this->settings['height'] ?? 'auto';

        return self::TEXT_HEIGHTS[$key] ?? '';
    }

    public function isFluid(): bool
    {
        return (bool) ($this->settings['fluid'] ?? false);
    }

    public function groupSize(): int
    {
        $size = (int) ($this->settings['group_size'] ?? 1);

        return $size >= 2 && $size <= 4 ? $size : 1;
    }

    public function hasSharedGroupBackground(): bool
    {
        return (bool) ($this->settings['group_shared_background'] ?? false);
    }

    public function groupBackgroundStyle(): string
    {
        $s = $this->settings ?? [];
        $style = [];

        if (! empty($s['group_background_gradient']) && ! empty($s['group_background_color'])) {
            $angle = (int) ($s['group_gradient_angle'] ?? 135);
            $style[] = "background-image:linear-gradient({$angle}deg, {$s['group_background_color']}, {$s['group_background_gradient']})";
        } elseif (! empty($s['group_background_color'])) {
            $style[] = 'background-color:' . $s['group_background_color'];
        }

        $style[] = 'padding-top:' . (int) ($s['group_padding_top'] ?? 48) . 'px';
        $style[] = 'padding-bottom:' . (int) ($s['group_padding_bottom'] ?? 48) . 'px';

        return implode(';', $style);
    }

    public function textSide(): string
    {
        return ($this->settings['text_side'] ?? 'left') === 'right' ? 'right' : 'left';
    }

    public function mapLat(): float
    {
        return (float) ($this->settings['lat'] ?? 39.4699);
    }

    public function mapLng(): float
    {
        return (float) ($this->settings['lng'] ?? -0.3763);
    }

    public function mapZoom(): int
    {
        return (int) ($this->settings['zoom'] ?? 15);
    }

    public function styleAttr(): string
    {
        $s = $this->settings ?? [];
        $style = [];

        // Site-wide fixed scroll-gradient background (layouts/site.blade.php) shows through
        // by default, so sections stay transparent here unless an admin explicitly opts a
        // section into its own background (override_background) or uploads an image.
        if (! empty($s['background_image'])) {
            $style[] = "background-image:url('" . asset('storage/' . $s['background_image']) . "')";
            $style[] = 'background-size:cover';
            $style[] = 'background-position:center';
        } elseif (! empty($s['override_background'])) {
            if (! empty($s['background_gradient']) && ! empty($s['background_color'])) {
                $angle = (int) ($s['gradient_angle'] ?? 135);
                $style[] = "background-image:linear-gradient({$angle}deg, {$s['background_color']}, {$s['background_gradient']})";
            } elseif (! empty($s['background_color'])) {
                $style[] = 'background-color:' . $s['background_color'];
            }
        }
        if (! empty($s['text_color'])) {
            $style[] = 'color:' . $s['text_color'];
        }
        $style[] = 'padding-top:' . (int) ($s['padding_top'] ?? 0) . 'px';
        $style[] = 'padding-bottom:' . (int) ($s['padding_bottom'] ?? 0) . 'px';

        return implode(';', $style);
    }
}
