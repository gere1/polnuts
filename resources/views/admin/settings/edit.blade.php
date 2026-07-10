<x-admin-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">საიტის პარამეტრები</h2>
            <p class="text-sm text-slate-500 mt-0.5">გლობალური ბრენდინგი — ავტომატურად ვრცელდება მთელ საიტზე</p>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        @if (session('status'))
            <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg text-sm">{{ session('status') }}</div>
        @endif

        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="admin-card p-6 space-y-6">
            @csrf @method('PUT')

            <fieldset class="space-y-4">
                <legend class="text-sm font-semibold text-slate-700 mb-2">ზოგადი ინფორმაცია</legend>
                <div>
                    <x-input-label for="site_name" value="საიტის სახელი" />
                    <x-text-input id="site_name" name="site_name" class="mt-1 block w-full" value="{{ old('site_name', $setting->site_name) }}" />
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="phone" value="ტელეფონი" />
                        <x-text-input id="phone" name="phone" class="mt-1 block w-full" value="{{ old('phone', $setting->phone) }}" />
                    </div>
                    <div>
                        <x-input-label for="email" value="ელ. ფოსტა" />
                        <x-text-input id="email" name="email" class="mt-1 block w-full" value="{{ old('email', $setting->email) }}" />
                    </div>
                </div>
                <div>
                    <x-input-label for="logo" value="ლოგო" />
                    @if ($setting->logo)
                        <div class="flex items-center gap-2 my-2">
                            <img src="{{ asset('storage/' . $setting->logo) }}" style="height: {{ $setting->logo_height }}px" class="object-contain">
                            <label class="text-xs text-red-600 flex items-center gap-1">
                                <input type="checkbox" name="remove_logo" value="1"> წაშლა
                            </label>
                        </div>
                    @endif
                    <input type="file" id="logo" name="logo" class="mt-1 block w-full text-sm text-slate-600">
                    <div class="mt-2">
                        <x-input-label for="logo_height" value="ლოგოს სიმაღლე (px)" />
                        <x-text-input type="number" min="16" max="200" id="logo_height" name="logo_height" class="mt-1 block w-32" value="{{ old('logo_height', $setting->logo_height) }}" />
                    </div>
                </div>
                <div>
                    <label class="flex items-center gap-2 text-sm text-slate-700">
                        <input type="checkbox" name="show_top_bar" value="1" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" @checked(old('show_top_bar', $setting->show_top_bar))>
                        ზედა ხაზის ჩვენება (ტელეფონი/ელფოსტა, ჰედერის ზემოთ)
                    </label>
                </div>
                <div>
                    <x-input-label for="favicon" value="Favicon (ბრაუზერის ტაბის აიქონი)" />
                    @if ($setting->favicon)
                        <div class="flex items-center gap-2 my-2">
                            <img src="{{ asset('storage/' . $setting->favicon) }}" class="h-8 w-8 object-contain">
                            <label class="text-xs text-red-600 flex items-center gap-1">
                                <input type="checkbox" name="remove_favicon" value="1"> წაშლა
                            </label>
                        </div>
                    @endif
                    <input type="file" id="favicon" name="favicon" class="mt-1 block w-full text-sm text-slate-600">
                    <p class="text-xs text-slate-500 mt-1">რეკომენდირებულია კვადრატული სურათი (მაგ. 32x32 ან 512x512 px).</p>
                </div>
            </fieldset>

            <fieldset class="border-t border-slate-100 pt-5 space-y-4">
                <legend class="text-sm font-semibold text-slate-700 mb-2">ფერები და შრიფტი</legend>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="primary_color" value="მთავარი ფერი (ღილაკები, აქტიური ბმულები)" />
                        <input type="color" id="primary_color" name="primary_color" value="{{ old('primary_color', $setting->primary_color) }}" class="mt-1 h-10 w-20 rounded-lg border-slate-300">
                    </div>
                    <div>
                        <x-input-label for="text_color" value="ტექსტის ძირითადი ფერი" />
                        <input type="color" id="text_color" name="text_color" value="{{ old('text_color', $setting->text_color) }}" class="mt-1 h-10 w-20 rounded-lg border-slate-300">
                    </div>
                    <div>
                        <x-input-label for="header_bg_color" value="ჰედერის ფონი" />
                        <input type="color" id="header_bg_color" name="header_bg_color" value="{{ old('header_bg_color', $setting->header_bg_color) }}" class="mt-1 h-10 w-20 rounded-lg border-slate-300">
                    </div>
                    <div>
                        <x-input-label for="footer_bg_color" value="ფუტერის ფონი" />
                        <input type="color" id="footer_bg_color" name="footer_bg_color" value="{{ old('footer_bg_color', $setting->footer_bg_color) }}" class="mt-1 h-10 w-20 rounded-lg border-slate-300">
                    </div>
                    <div>
                        <x-input-label for="top_bar_bg_color" value="ზედა ზოლის ფონი (ტელეფონი/მეილი)" />
                        <input type="color" id="top_bar_bg_color" name="top_bar_bg_color" value="{{ old('top_bar_bg_color', $setting->top_bar_bg_color) }}" class="mt-1 h-10 w-20 rounded-lg border-slate-300">
                    </div>
                    <div>
                        <x-input-label for="top_bar_text_color" value="ზედა ზოლის ტექსტის ფერი" />
                        <input type="color" id="top_bar_text_color" name="top_bar_text_color" value="{{ old('top_bar_text_color', $setting->top_bar_text_color) }}" class="mt-1 h-10 w-20 rounded-lg border-slate-300">
                    </div>
                </div>
                <div>
                    <x-input-label for="font" value="შრიფტი" />
                    <select id="font" name="font" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach (\App\Models\Setting::FONTS as $key => $label)
                            <option value="{{ $key }}" @selected(old('font', $setting->font) === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </fieldset>

            <fieldset class="border-t border-slate-100 pt-5 space-y-4" x-data="{ mode: '{{ old('background_mode', $setting->background_mode ?? 'gradient') }}' }">
                <legend class="text-sm font-semibold text-slate-700 mb-2">გვერდის ფონი (გვერდები, პროდუქცია, სიახლეები)</legend>

                <div class="flex gap-4">
                    @foreach (\App\Models\Setting::BACKGROUND_MODES as $key => $label)
                        <label class="flex items-center gap-2 text-sm text-slate-700">
                            <input type="radio" name="background_mode" value="{{ $key }}" x-model="mode" class="border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            {{ $label }}
                        </label>
                    @endforeach
                </div>

                <div x-show="mode === 'gradient'" x-cloak class="space-y-2">
                    <p class="text-xs text-slate-500">ფონი თანდათან იცვლება ზედა ფერებიდან ქვედა ფერებზე, გვერდის ჩამოსქროლვის კვალდაკვალ.</p>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="gradient_top_start" value="ზედა — დასაწყისი" />
                            <input type="color" id="gradient_top_start" name="gradient_top_start" value="{{ old('gradient_top_start', $setting->gradient_top_start) }}" class="mt-1 h-10 w-20 rounded-lg border-slate-300">
                        </div>
                        <div>
                            <x-input-label for="gradient_top_end" value="ზედა — დასასრული" />
                            <input type="color" id="gradient_top_end" name="gradient_top_end" value="{{ old('gradient_top_end', $setting->gradient_top_end) }}" class="mt-1 h-10 w-20 rounded-lg border-slate-300">
                        </div>
                        <div>
                            <x-input-label for="gradient_bottom_start" value="ბოლო — დასაწყისი" />
                            <input type="color" id="gradient_bottom_start" name="gradient_bottom_start" value="{{ old('gradient_bottom_start', $setting->gradient_bottom_start) }}" class="mt-1 h-10 w-20 rounded-lg border-slate-300">
                        </div>
                        <div>
                            <x-input-label for="gradient_bottom_end" value="ბოლო — დასასრული" />
                            <input type="color" id="gradient_bottom_end" name="gradient_bottom_end" value="{{ old('gradient_bottom_end', $setting->gradient_bottom_end) }}" class="mt-1 h-10 w-20 rounded-lg border-slate-300">
                        </div>
                    </div>
                </div>

                <div x-show="mode === 'solid'" x-cloak>
                    <x-input-label for="content_bg_color" value="ფონის ფერი" />
                    <input type="color" id="content_bg_color" name="content_bg_color" value="{{ old('content_bg_color', $setting->content_bg_color) }}" class="mt-1 h-10 w-20 rounded-lg border-slate-300">
                </div>
            </fieldset>

            <fieldset class="border-t border-slate-100 pt-5 space-y-3" x-data="{ def: '{{ old('default_locale', $setting->default_locale ?? 'ka') }}' }">
                <legend class="text-sm font-semibold text-slate-700 mb-2">ენები</legend>

                <div>
                    <x-input-label value="დეფოლტური ენა (უნიშნო URL-ზე, მაგ. /products)" />
                    <select name="default_locale" x-model="def" class="mt-1 block w-full max-w-xs rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach (\App\Models\Setting::LOCALES as $code => $label)
                            <option value="{{ $code }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-slate-500 mt-1">ეს ენა გამოჩნდება პრეფიქსის გარეშე (მაგ. /products). დანარჩენები ავტომატურად გადავა /en, /de და ა.შ. მისამართზე.</p>
                </div>

                <div class="space-y-2">
                    <x-input-label value="დამატებით აქტიური ენები" />
                    <p class="text-xs text-slate-500 mb-1">გამორთული ენის URL-ი დაბლოკილი იქნება (404), სანამ ისევ არ ჩართავთ.</p>
                    @foreach (\App\Models\Setting::LOCALES as $code => $label)
                        <label class="flex items-center gap-2 text-sm" :class="def === '{{ $code }}' ? 'text-slate-400' : 'text-slate-700'">
                            <input type="checkbox" name="locales[]" value="{{ $code }}"
                                   :disabled="def === '{{ $code }}'"
                                   class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                   @checked($setting->default_locale === $code || in_array($code, old('locales', $setting->locales ?? []), true))>
                            {{ $label }}
                            <span x-show="def === '{{ $code }}'" x-cloak class="text-xs text-slate-400">(დეფოლტური — ყოველთვის ჩართული)</span>
                        </label>
                    @endforeach
                </div>
            </fieldset>

            <button class="admin-btn-primary">შენახვა</button>
        </form>
    </div>
</x-admin-layout>
