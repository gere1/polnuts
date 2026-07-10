@php
    $product = $product ?? null;
    $defaultLocale = \App\Models\Setting::current()->default_locale;
@endphp

<div>
    <x-input-label value="სახელი" />
    <x-i18n-tabs>
        <x-slot:ka><x-text-input name="name[ka]" class="mt-1 block w-full" value="{{ old('name.ka', $product?->getTranslation('name', 'ka', false)) }}" :required="$defaultLocale === 'ka'" autofocus /></x-slot:ka>
        <x-slot:en><x-text-input name="name[en]" class="mt-1 block w-full" value="{{ old('name.en', $product?->getTranslation('name', 'en', false)) }}" :required="$defaultLocale === 'en'" /></x-slot:en>
        <x-slot:de><x-text-input name="name[de]" class="mt-1 block w-full" value="{{ old('name.de', $product?->getTranslation('name', 'de', false)) }}" :required="$defaultLocale === 'de'" /></x-slot:de>
    </x-i18n-tabs>
    <x-input-error :messages="$errors->get('name.'.$defaultLocale)" class="mt-2" />
</div>

<div>
    <x-input-label for="slug" value="Slug (არასავალდებულო)" />
    <x-text-input id="slug" name="slug" class="mt-1 block w-full" value="{{ old('slug', $product->slug ?? '') }}" />
    <x-input-error :messages="$errors->get('slug')" class="mt-2" />
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <x-input-label for="price" value="ფასი (₾, არასავალდებულო)" />
        <x-text-input type="number" step="0.01" min="0" id="price" name="price" class="mt-1 block w-full" value="{{ old('price', $product->price ?? '') }}" />
    </div>
    <div>
        <x-input-label for="position" value="ჩვენების რიგითობა" />
        <x-text-input type="number" min="0" id="position" name="position" class="mt-1 block w-full" value="{{ old('position', $product->position ?? 0) }}" />
    </div>
</div>

<div>
    <x-input-label value="მოკლე აღწერა" />
    <x-i18n-tabs>
        <x-slot:ka><textarea name="excerpt[ka]" rows="2" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('excerpt.ka', $product?->getTranslation('excerpt', 'ka', false)) }}</textarea></x-slot:ka>
        <x-slot:en><textarea name="excerpt[en]" rows="2" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('excerpt.en', $product?->getTranslation('excerpt', 'en', false)) }}</textarea></x-slot:en>
        <x-slot:de><textarea name="excerpt[de]" rows="2" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('excerpt.de', $product?->getTranslation('excerpt', 'de', false)) }}</textarea></x-slot:de>
    </x-i18n-tabs>
</div>

<div>
    <x-input-label value="სრული აღწერა" />
    <x-i18n-tabs>
        <x-slot:ka><x-rich-editor name="body[ka]" :value="old('body.ka', $product?->getTranslation('body', 'ka', false))" /></x-slot:ka>
        <x-slot:en><x-rich-editor name="body[en]" :value="old('body.en', $product?->getTranslation('body', 'en', false))" /></x-slot:en>
        <x-slot:de><x-rich-editor name="body[de]" :value="old('body.de', $product?->getTranslation('body', 'de', false))" /></x-slot:de>
    </x-i18n-tabs>
</div>

<div>
    <x-input-label for="image" value="სურათი" />
    @if ($product?->image)
        <img src="{{ asset('storage/' . $product->image) }}" class="w-32 h-20 object-contain bg-gray-50 rounded-lg my-2 p-1">
    @endif
    <input type="file" id="image" name="image" class="mt-1 block w-full text-sm text-slate-600">
</div>
