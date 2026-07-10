@php
    $settings = \App\Models\Setting::current();
    $bodyFamily = $settings->fontFamily();
    $bodyFamilyParam = str_replace(' ', '+', $bodyFamily);
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? $settings->site_name }}</title>
    @php
        $metaDescription = $description ?? $settings->site_name;
        $metaImage = $image ?? ($settings->logo ? asset('storage/' . $settings->logo) : null);
    @endphp
    <meta name="description" content="{{ $metaDescription }}">
    <link rel="canonical" href="{{ url()->current() }}">
    @if ($settings->favicon)
        <link rel="icon" href="{{ asset('storage/' . $settings->favicon) }}">
    @endif

    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $title ?? $settings->site_name }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="{{ $settings->site_name }}">
    @if ($metaImage)
        <meta property="og:image" content="{{ $metaImage }}">
    @endif
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title ?? $settings->site_name }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    @if ($metaImage)
        <meta name="twitter:image" content="{{ $metaImage }}">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital@1&family={{ $bodyFamilyParam }}:wght@400;500;600;700&display=swap" rel="stylesheet">
    {{-- Superset of fonts a row/product/article body can pick per-selection in the rich-text
         editor — loaded here too so those choices actually render on the public pages. --}}
    <link href="https://fonts.googleapis.com/css2?{{ \App\Models\Setting::richTextFontsQuery() }}&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

    <style>
        :root {
            --brand-primary: {{ $settings->primary_color }};
            --brand-text: {{ $settings->text_color }};
            --header-bg: {{ $settings->header_bg_color }};
            --footer-bg: {{ $settings->footer_bg_color }};
            --top-bar-bg: {{ $settings->top_bar_bg_color }};
            --top-bar-text: {{ $settings->top_bar_text_color }};
            --content-bg: {{ $settings->content_bg_color }};
        }
        body { font-family: '{{ $bodyFamily }}', sans-serif; color: var(--brand-text); }
        .font-display { font-family: 'Playfair Display', serif; }
        {{-- Rich-text content (row/product/article bodies) can pick a font per selection via the
             admin editor, written out as a ql-font-{slug} class — define the matching font-family
             here so it actually renders on the public pages, not just inside the editor. --}}
        @foreach (\App\Models\Setting::RICH_TEXT_FONTS as $slug => $font)
            .ql-font-{{ $slug }} { font-family: '{{ $font['label'] }}', sans-serif; }
        @endforeach

        {{-- The rich-text editor saves lists as <ol data-list="bullet|ordered|checked|unchecked">
             with an empty marker <span class="ql-ui"> per <li> — Quill's own CSS only styles this
             inside the editor (.ql-editor-scoped), so without this the marker span renders nothing
             and the browser falls back to default <ol> numbering for every list, bullets included. --}}
        ol > li[data-list] { list-style: none; position: relative; padding-left: 1.5em; }
        ol > li[data-list] > .ql-ui { position: absolute; }
        ol > li[data-list] > .ql-ui::before { display: inline-block; margin-left: -1.5em; margin-right: .3em; text-align: right; white-space: nowrap; width: 1.2em; }
        ol > li[data-list="bullet"] > .ql-ui::before { content: '\2022'; }
        ol > li[data-list="checked"] > .ql-ui::before { content: '\2611'; color: #777; }
        ol > li[data-list="unchecked"] > .ql-ui::before { content: '\2610'; color: #777; }
        ol:has(> li[data-list="ordered"]) { counter-reset: list-item; }
        ol > li[data-list="ordered"] { counter-increment: list-item; }
        ol > li[data-list="ordered"] > .ql-ui::before { content: counter(list-item) '. '; }
    </style>
</head>
<body @class(['bg-content' => ! $settings->isGradientBackground()])>
    @if ($settings->isGradientBackground())
        <div id="scroll-gradient-bg"
             data-top-start="{{ $settings->gradient_top_start }}"
             data-top-end="{{ $settings->gradient_top_end }}"
             data-bottom-start="{{ $settings->gradient_bottom_start }}"
             data-bottom-end="{{ $settings->gradient_bottom_end }}"
             style="position:fixed;inset:0;z-index:-1;pointer-events:none;background-image:linear-gradient(135deg, {{ $settings->gradient_top_start }}, {{ $settings->gradient_top_end }});"></div>
    @endif

    @if ($settings->show_top_bar)
        <div class="bg-top-bar text-top-bar text-sm">
            <div class="max-w-7xl mx-auto px-4 py-2 flex justify-end gap-6">
                @if ($settings->phone)
                    <a href="tel:{{ $settings->phone }}" class="flex items-center gap-1.5 hover:opacity-80">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                        </svg>
                        {{ $settings->phone }}
                    </a>
                @endif
                @if ($settings->email)
                    <a href="mailto:{{ $settings->email }}" class="flex items-center gap-1.5 hover:opacity-80">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                        </svg>
                        {{ $settings->email }}
                    </a>
                @endif
            </div>
        </div>
    @endif

    @php
        $currentRouteName = \Illuminate\Support\Facades\Route::currentRouteName();
        $localeBaseRoute = $currentRouteName && str_starts_with($currentRouteName, 'loc.') ? substr($currentRouteName, 4) : $currentRouteName;
        $localeRouteParams = collect(request()->route()?->parameters() ?? [])->except('locale')->all();
        $locales = collect(\App\Models\Setting::LOCALES)
            ->mapWithKeys(fn ($label, $code) => [$code => strtoupper($code)])
            ->filter(fn ($label, $code) => $settings->isLocaleEnabled($code));
    @endphp

    <header class="bg-header border-b" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 h-20 flex items-center justify-between">
            <a href="{{ localizedRoute('home') }}" class="text-xl font-semibold tracking-wide flex items-center gap-2">
                @if ($settings->logo)
                    <img src="{{ asset('storage/' . $settings->logo) }}" alt="{{ $settings->site_name }}" style="height: {{ $settings->logo_height }}px" class="object-contain">
                @else
                    {{ $settings->site_name }}
                @endif
            </a>
            <nav class="hidden md:flex items-center gap-8 text-sm font-medium">
                @foreach (\App\Models\MenuItem::orderBy('position')->get() as $menuItem)
                    <a href="{{ $menuItem->href() }}"
                       @if ($menuItem->opensInNewTab()) target="_blank" rel="noopener" @endif
                       class="hover:text-brand {{ url()->current() === $menuItem->href() ? 'text-brand' : '' }}">
                        {{ $menuItem->label }}
                    </a>
                @endforeach
            </nav>

            <div class="hidden md:flex items-center gap-1 text-xs font-semibold shrink-0 ml-4">
                @if ($localeBaseRoute && $locales->count() > 1)
                    @foreach ($locales as $code => $label)
                        <a href="{{ localizedRoute($localeBaseRoute, $localeRouteParams, $code) }}"
                           class="px-2 py-1 rounded transition {{ app()->getLocale() === $code ? 'bg-brand text-white' : 'opacity-60 hover:opacity-100' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                @endif
            </div>

            <button
                type="button"
                @click="mobileMenuOpen = !mobileMenuOpen"
                :aria-expanded="mobileMenuOpen"
                aria-label="{{ __('მენიუს გახსნა') }}"
                class="md:hidden inline-flex items-center justify-center p-2 -mr-2 rounded-md hover:text-brand focus:outline-none"
            >
                <svg x-show="!mobileMenuOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-show="mobileMenuOpen" x-cloak class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <nav
            x-show="mobileMenuOpen"
            x-cloak
            x-transition
            @click.outside="mobileMenuOpen = false"
            class="md:hidden border-t bg-header px-4 py-3 space-y-1 text-sm font-medium"
        >
            @foreach (\App\Models\MenuItem::orderBy('position')->get() as $menuItem)
                <a href="{{ $menuItem->href() }}"
                   @if ($menuItem->opensInNewTab()) target="_blank" rel="noopener" @endif
                   @click="mobileMenuOpen = false"
                   class="block py-2.5 hover:text-brand {{ url()->current() === $menuItem->href() ? 'text-brand' : '' }}">
                    {{ $menuItem->label }}
                </a>
            @endforeach

            @if ($localeBaseRoute && $locales->count() > 1)
                <div class="flex items-center gap-1 pt-2 mt-2 border-t text-xs font-semibold">
                    @foreach ($locales as $code => $label)
                        <a href="{{ localizedRoute($localeBaseRoute, $localeRouteParams, $code) }}"
                           class="px-2 py-1 rounded transition {{ app()->getLocale() === $code ? 'bg-brand text-white' : 'opacity-60 hover:opacity-100' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            @endif
        </nav>
    </header>

    <main>
        {{ $slot }}
    </main>

    <footer class="bg-footer text-gray-400">
        <div class="max-w-7xl mx-auto px-4 py-6 text-sm text-center">
            &copy; {{ now()->year > 2022 ? '2022–' . now()->year : '2022' }} {{ $settings->site_name }}. {{ __('All rights reserved.') }}
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
