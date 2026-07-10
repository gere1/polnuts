<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">გვერდის რედაქტირება</h2>
    </x-slot>

    @php $defaultLocale = \App\Models\Setting::current()->default_locale; @endphp

    <div class="max-w-xl mx-auto">
        <form action="{{ route('admin.pages.update', $page) }}" method="POST" class="admin-card p-6 space-y-5">
            @csrf @method('PUT')
            <div>
                <x-input-label value="სათაური" />
                <x-i18n-tabs>
                    <x-slot:ka><x-text-input name="title[ka]" class="mt-1 block w-full" value="{{ old('title.ka', $page->getTranslation('title', 'ka', false)) }}" :required="$defaultLocale === 'ka'" autofocus /></x-slot:ka>
                    <x-slot:en><x-text-input name="title[en]" class="mt-1 block w-full" value="{{ old('title.en', $page->getTranslation('title', 'en', false)) }}" :required="$defaultLocale === 'en'" /></x-slot:en>
                    <x-slot:de><x-text-input name="title[de]" class="mt-1 block w-full" value="{{ old('title.de', $page->getTranslation('title', 'de', false)) }}" :required="$defaultLocale === 'de'" /></x-slot:de>
                </x-i18n-tabs>
                <x-input-error :messages="$errors->get('title.'.$defaultLocale)" class="mt-2" />
            </div>
            <div>
                <x-input-label for="slug" value="Slug" />
                <x-text-input id="slug" name="slug" class="mt-1 block w-full" value="{{ old('slug', $page->slug) }}" required />
                <x-input-error :messages="$errors->get('slug')" class="mt-2" />
            </div>
            <div>
                <x-input-label value="მეტა აღწერა (SEO, Google-ის ძებნის შედეგებში ჩნდება)" />
                <x-i18n-tabs>
                    <x-slot:ka><textarea name="meta_description[ka]" rows="3" maxlength="500" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('meta_description.ka', $page->getTranslation('meta_description', 'ka', false)) }}</textarea></x-slot:ka>
                    <x-slot:en><textarea name="meta_description[en]" rows="3" maxlength="500" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('meta_description.en', $page->getTranslation('meta_description', 'en', false)) }}</textarea></x-slot:en>
                    <x-slot:de><textarea name="meta_description[de]" rows="3" maxlength="500" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('meta_description.de', $page->getTranslation('meta_description', 'de', false)) }}</textarea></x-slot:de>
                </x-i18n-tabs>
                <x-input-error :messages="$errors->get('meta_description.'.$defaultLocale)" class="mt-2" />
            </div>
            <label class="flex items-center gap-2">
                <input type="checkbox" name="is_home" value="1" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" @checked($page->is_home)>
                <span class="text-sm text-slate-700">გამოიყენე როგორც მთავარი გვერდი</span>
            </label>
            <div class="flex items-center gap-4 pt-2">
                <button class="admin-btn-primary">შენახვა</button>
                <a href="{{ route('admin.pages.index') }}" class="text-sm text-slate-500 hover:text-slate-800">გაუქმება</a>
            </div>
        </form>
    </div>
</x-admin-layout>
