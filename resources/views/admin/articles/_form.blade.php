@php
    $article = $article ?? null;
    $defaultLocale = \App\Models\Setting::current()->default_locale;
@endphp

<div>
    <x-input-label value="სათაური" />
    <x-i18n-tabs>
        <x-slot:ka><x-text-input name="title[ka]" class="mt-1 block w-full" value="{{ old('title.ka', $article?->getTranslation('title', 'ka', false)) }}" :required="$defaultLocale === 'ka'" autofocus /></x-slot:ka>
        <x-slot:en><x-text-input name="title[en]" class="mt-1 block w-full" value="{{ old('title.en', $article?->getTranslation('title', 'en', false)) }}" :required="$defaultLocale === 'en'" /></x-slot:en>
        <x-slot:de><x-text-input name="title[de]" class="mt-1 block w-full" value="{{ old('title.de', $article?->getTranslation('title', 'de', false)) }}" :required="$defaultLocale === 'de'" /></x-slot:de>
    </x-i18n-tabs>
    <x-input-error :messages="$errors->get('title.'.$defaultLocale)" class="mt-2" />
</div>

<div>
    <x-input-label for="slug" value="Slug (არასავალდებულო)" />
    <x-text-input id="slug" name="slug" class="mt-1 block w-full" value="{{ old('slug', $article->slug ?? '') }}" />
    <x-input-error :messages="$errors->get('slug')" class="mt-2" />
</div>

<div>
    <x-input-label value="მოკლე აღწერა" />
    <x-i18n-tabs>
        <x-slot:ka><textarea name="excerpt[ka]" rows="2" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('excerpt.ka', $article?->getTranslation('excerpt', 'ka', false)) }}</textarea></x-slot:ka>
        <x-slot:en><textarea name="excerpt[en]" rows="2" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('excerpt.en', $article?->getTranslation('excerpt', 'en', false)) }}</textarea></x-slot:en>
        <x-slot:de><textarea name="excerpt[de]" rows="2" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('excerpt.de', $article?->getTranslation('excerpt', 'de', false)) }}</textarea></x-slot:de>
    </x-i18n-tabs>
</div>

<div>
    <x-input-label value="სრული ტექსტი" />
    <x-i18n-tabs>
        <x-slot:ka><x-rich-editor name="body[ka]" :value="old('body.ka', $article?->getTranslation('body', 'ka', false))" /></x-slot:ka>
        <x-slot:en><x-rich-editor name="body[en]" :value="old('body.en', $article?->getTranslation('body', 'en', false))" /></x-slot:en>
        <x-slot:de><x-rich-editor name="body[de]" :value="old('body.de', $article?->getTranslation('body', 'de', false))" /></x-slot:de>
    </x-i18n-tabs>
</div>

<div>
    <x-input-label for="published_at" value="გამოქვეყნების თარიღი" />
    <input type="datetime-local" id="published_at" name="published_at" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        value="{{ old('published_at', optional($article->published_at ?? null)->format('Y-m-d\TH:i')) }}">
</div>

<div>
    <x-input-label for="image" value="სურათი" />
    @if ($article?->image)
        <img src="{{ asset('storage/' . $article->image) }}" class="w-32 h-20 object-cover rounded-lg my-2">
    @endif
    <input type="file" id="image" name="image" class="mt-1 block w-full text-sm text-slate-600">
</div>
