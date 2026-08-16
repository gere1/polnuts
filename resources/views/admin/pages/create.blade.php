<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">ახალი გვერდი</h2>
    </x-slot>

    @php $defaultLocale = \App\Models\Setting::current()->default_locale; @endphp

    <div class="max-w-xl mx-auto">
        <form action="{{ route('admin.pages.store') }}" method="POST" class="admin-card p-6 space-y-5">
            @csrf
            <div>
                <x-input-label value="სათაური" />
                <x-i18n-tabs>
                    <x-slot:ka><x-text-input name="title[ka]" class="mt-1 block w-full" value="{{ old('title.ka') }}" :required="$defaultLocale === 'ka'" autofocus /></x-slot:ka>
                    <x-slot:en><x-text-input name="title[en]" class="mt-1 block w-full" value="{{ old('title.en') }}" :required="$defaultLocale === 'en'" /></x-slot:en>
                    <x-slot:de><x-text-input name="title[de]" class="mt-1 block w-full" value="{{ old('title.de') }}" :required="$defaultLocale === 'de'" /></x-slot:de>
                    <x-slot:pl><x-text-input name="title[pl]" class="mt-1 block w-full" value="{{ old('title.pl') }}" :required="$defaultLocale === 'pl'" /></x-slot:pl>
                    <x-slot:es><x-text-input name="title[es]" class="mt-1 block w-full" value="{{ old('title.es') }}" :required="$defaultLocale === 'es'" /></x-slot:es>
                </x-i18n-tabs>
                <x-input-error :messages="$errors->get('title.'.$defaultLocale)" class="mt-2" />
            </div>
            <div>
                <x-input-label for="slug" value="Slug (არასავალდებულო)" />
                <x-text-input id="slug" name="slug" class="mt-1 block w-full" value="{{ old('slug') }}" placeholder="ავტომატურად სათაურიდან" />
                <x-input-error :messages="$errors->get('slug')" class="mt-2" />
            </div>
            <label class="flex items-center gap-2">
                <input type="checkbox" name="is_home" value="1" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                <span class="text-sm text-slate-700">გამოიყენე როგორც მთავარი გვერდი</span>
            </label>
            <div class="flex items-center gap-4 pt-2">
                <button class="admin-btn-primary">შექმნა</button>
                <a href="{{ route('admin.pages.index') }}" class="text-sm text-slate-500 hover:text-slate-800">გაუქმება</a>
            </div>
        </form>
    </div>
</x-admin-layout>
