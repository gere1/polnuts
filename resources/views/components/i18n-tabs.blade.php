@props(['id' => 'i18n-'.\Illuminate\Support\Str::random(6)])
@php $defaultLocale = \App\Models\Setting::current()->default_locale; @endphp
<div x-data="{ tab: '{{ $defaultLocale }}' }">
    <div class="flex gap-1 mb-2 border-b border-slate-200">
        <button type="button" @click="tab = 'ka'" :class="tab === 'ka' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700'" class="px-3 py-1.5 text-xs font-medium border-b-2 -mb-px transition">
            ქართული @if ($defaultLocale === 'ka') <span class="text-slate-400">*</span> @endif
        </button>
        <button type="button" @click="tab = 'en'" :class="tab === 'en' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700'" class="px-3 py-1.5 text-xs font-medium border-b-2 -mb-px transition">
            English @if ($defaultLocale === 'en') <span class="text-slate-400">*</span> @endif
        </button>
        <button type="button" @click="tab = 'de'" :class="tab === 'de' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700'" class="px-3 py-1.5 text-xs font-medium border-b-2 -mb-px transition">
            Deutsch @if ($defaultLocale === 'de') <span class="text-slate-400">*</span> @endif
        </button>
    </div>
    <div x-show="tab === 'ka'" @if ($defaultLocale !== 'ka') x-cloak @endif>{{ $ka }}</div>
    <div x-show="tab === 'en'" @if ($defaultLocale !== 'en') x-cloak @endif>{{ $en }}</div>
    <div x-show="tab === 'de'" @if ($defaultLocale !== 'de') x-cloak @endif>{{ $de }}</div>
</div>
