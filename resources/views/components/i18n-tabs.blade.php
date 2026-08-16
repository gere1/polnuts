@props(['id' => 'i18n-'.\Illuminate\Support\Str::random(6)])
@php
    $setting = \App\Models\Setting::current();
    $defaultLocale = $setting->default_locale;
    $locales = collect(\App\Models\Setting::LOCALES)->filter(fn ($label, $code) => $setting->isLocaleEnabled($code));
@endphp
<div x-data="{ tab: '{{ $defaultLocale }}' }">
    <div class="flex gap-1 mb-2 border-b border-slate-200">
        @foreach ($locales as $code => $label)
            <button type="button" @click="tab = '{{ $code }}'" :class="tab === '{{ $code }}' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700'" class="px-3 py-1.5 text-xs font-medium border-b-2 -mb-px transition">
                {{ $label }} @if ($defaultLocale === $code) <span class="text-slate-400">*</span> @endif
            </button>
        @endforeach
    </div>
    @foreach ($locales as $code => $label)
        <div x-show="tab === '{{ $code }}'" @if ($defaultLocale !== $code) x-cloak @endif>{{ $$code ?? '' }}</div>
    @endforeach
</div>
