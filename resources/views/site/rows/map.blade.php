@php $bare = $bare ?? false; @endphp
<section class="{{ $bare ? '' : 'px-4' }}" style="{{ $bare ? (($groupTextColor ?? null) ? 'color:'.$groupTextColor : $row->textColorStyle()) : $row->styleAttr() }}">
    <div class="{{ $bare ? '' : 'max-w-7xl mx-auto' }}">
        <x-animated-heading :row="$row" class="font-display italic text-3xl md:text-4xl text-center mb-2" />
        @if ($row->subtitle)
            <p class="text-center opacity-70 mb-6">{{ $row->subtitle }}</p>
        @endif

        <div class="rounded-lg overflow-hidden shadow-sm border border-black/5">
            <iframe
                src="https://www.google.com/maps?q={{ $row->mapLat() }},{{ $row->mapLng() }}&z={{ $row->mapZoom() }}&output=embed"
                width="100%"
                height="450"
                style="border:0"
                allowfullscreen
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                class="w-full block"
            ></iframe>
        </div>
    </div>
</section>
