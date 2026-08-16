@php
    $typeLabels = ['slider' => 'სლაიდერი', 'news' => 'სიახლეები', 'text' => 'ტექსტი', 'products' => 'პროდუქცია', 'map' => 'რუკა', 'form' => 'საკონტაქტო ფორმა', 'image' => 'სურათები'];
    $s = $row->settings ?? [];
    $columns = (int) ($s['columns'] ?? ($row->type === 'news' || $row->type === 'products' ? 3 : 1));
    $columnLabel = match ($row->type) {
        'slider' => 'ერთდროულად ჩვენების რაოდენობა (1 = სრულ სიგანეზე სლაიდერი)',
        'news' => 'სვეტების რაოდენობა ერთ ხაზში',
        'text' => 'სვეტების რაოდენობა (1 = ერთი ტექსტური ბლოკი)',
        'products' => 'სვეტების რაოდენობა ერთ ხაზში',
        'image' => 'სვეტების რაოდენობა ერთ ხაზში (1 = თითო სურათი სრულ სიგანეზე)',
        default => null,
    };
    $showItems = $row->type === 'slider' || $row->type === 'image' || ($row->type === 'text' && $columns > 1);
    $hasColumns = in_array($row->type, ['slider', 'news', 'text', 'products', 'image']);
    $inputCls = 'rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500';
@endphp
<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">
                რიგის რედაქტირება
                <span class="text-base font-normal text-slate-500">({{ $typeLabels[$row->type] }})</span>
            </h2>
            <a href="{{ route('admin.pages.builder', $row->page_id) }}" class="text-sm text-slate-500 hover:text-slate-800">← რიგებზე დაბრუნება</a>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto space-y-6">
        @if (session('status'))
            <div class="p-4 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg text-sm">{{ session('status') }}</div>
        @endif

        <form action="{{ route('admin.rows.update', $row) }}" method="POST" enctype="multipart/form-data" class="admin-card p-6 space-y-5">
            @csrf @method('PUT')

            <div>
                <x-input-label value="სათაური" />
                <x-i18n-tabs>
                    <x-slot:ka><x-text-input name="title[ka]" class="mt-1 block w-full" value="{{ old('title.ka', $row->getTranslation('title', 'ka', false)) }}" /></x-slot:ka>
                    <x-slot:en><x-text-input name="title[en]" class="mt-1 block w-full" value="{{ old('title.en', $row->getTranslation('title', 'en', false)) }}" /></x-slot:en>
                    <x-slot:de><x-text-input name="title[de]" class="mt-1 block w-full" value="{{ old('title.de', $row->getTranslation('title', 'de', false)) }}" /></x-slot:de>
                    <x-slot:pl><x-text-input name="title[pl]" class="mt-1 block w-full" value="{{ old('title.pl', $row->getTranslation('title', 'pl', false)) }}" /></x-slot:pl>
                    <x-slot:es><x-text-input name="title[es]" class="mt-1 block w-full" value="{{ old('title.es', $row->getTranslation('title', 'es', false)) }}" /></x-slot:es>
                </x-i18n-tabs>
            </div>
            <div>
                <x-input-label value="ქვესათაური" />
                <x-i18n-tabs>
                    <x-slot:ka><x-text-input name="subtitle[ka]" class="mt-1 block w-full" value="{{ old('subtitle.ka', $row->getTranslation('subtitle', 'ka', false)) }}" /></x-slot:ka>
                    <x-slot:en><x-text-input name="subtitle[en]" class="mt-1 block w-full" value="{{ old('subtitle.en', $row->getTranslation('subtitle', 'en', false)) }}" /></x-slot:en>
                    <x-slot:de><x-text-input name="subtitle[de]" class="mt-1 block w-full" value="{{ old('subtitle.de', $row->getTranslation('subtitle', 'de', false)) }}" /></x-slot:de>
                    <x-slot:pl><x-text-input name="subtitle[pl]" class="mt-1 block w-full" value="{{ old('subtitle.pl', $row->getTranslation('subtitle', 'pl', false)) }}" /></x-slot:pl>
                    <x-slot:es><x-text-input name="subtitle[es]" class="mt-1 block w-full" value="{{ old('subtitle.es', $row->getTranslation('subtitle', 'es', false)) }}" /></x-slot:es>
                </x-i18n-tabs>
            </div>

            @if ($row->type === 'slider' && $columns <= 1)
                <div x-data="{ heroStyle: '{{ old('style', $row->sliderStyle()) }}' }" class="space-y-4">
                    <div>
                        <x-input-label for="style" value="სლაიდერის სტილი" />
                        <select id="style" name="style" x-model="heroStyle" class="mt-1 block w-full max-w-xs {{ $inputCls }}">
                            @foreach (['center' => 'ცენტრში (კლასიკური)', 'left' => 'მარცხნივ ქვემოთ (რედაქციული)', 'right' => 'მარჯვნივ ქვემოთ (რედაქციული)', 'left-middle' => 'მარცხნივ შუაში', 'right-middle' => 'მარჯვნივ შუაში', 'minimal' => 'მინიმალური წარწერა', 'split' => 'გაყოფილი (სურათი + ფერადი პანელი)'] as $val => $label)
                                <option value="{{ $val }}" @selected(old('style', $row->sliderStyle()) === $val)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-slate-500 mt-1">ეხება მხოლოდ სრულ სიგანეზე სლაიდერს (როცა ერთდროულად ჩვენების რაოდენობა = 1).</p>
                    </div>
                    <div>
                        <x-input-label for="height" value="ბანერის სიმაღლე" />
                        <select id="height" name="height" class="mt-1 block w-full max-w-xs {{ $inputCls }}">
                            @foreach (['small' => 'პატარა', 'medium' => 'საშუალო (ნაგულისხმევი)', 'large' => 'დიდი', 'full' => 'მთელ ეკრანზე'] as $val => $label)
                                <option value="{{ $val }}" @selected(old('height', $row->settings['height'] ?? 'medium') === $val)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-slate-500 mt-1">თუ ბანერს ბევრი ცარიელი ადგილი რჩება, აირჩიეთ "პატარა" ან "საშუალო".</p>
                    </div>
                    <div x-show="heroStyle === 'split'" x-cloak>
                        <x-input-label value="სურათისა და ტექსტის მხარეები" />
                        <select name="text_side" class="mt-1 block w-full max-w-xs {{ $inputCls }}">
                            <option value="left" @selected(old('text_side', $row->textSide()) === 'left')>სურათი მარცხნივ, ტექსტი მარჯვნივ</option>
                            <option value="right" @selected(old('text_side', $row->textSide()) === 'right')>სურათი მარჯვნივ, ტექსტი მარცხნივ</option>
                        </select>
                        <p class="text-xs text-slate-500 mt-1">ეხება მხოლოდ "გაყოფილი" სტილს.</p>
                    </div>
                    <div>
                        <label class="flex items-center gap-2 text-sm text-slate-700">
                            <input type="checkbox" name="fluid" value="1" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" @checked(old('fluid', $row->isFluid()))>
                            ბანერის სრულ სიგანეზე გაშლა (fluid) — გვერდით ცარიელი ადგილის გარეშე
                        </label>
                    </div>
                    <div>
                        <x-input-label for="hero_image_effect" value="სურათის ეფექტი" />
                        <select id="hero_image_effect" name="hero_image_effect" class="mt-1 block w-full max-w-xs {{ $inputCls }}">
                            @foreach ([
                                'none' => 'გამორთული (სტატიკური)',
                                'zoom-out' => 'ნელი დაპატარავება (Ken Burns, ნაგულისხმევი)',
                                'zoom-in' => 'ნელი გადიდება (Ken Burns)',
                                'pan-left' => 'ნელი პანორამა მარჯვნიდან მარცხნივ',
                                'pan-right' => 'ნელი პანორამა მარცხნიდან მარჯვნივ',
                            ] as $val => $label)
                                <option value="{{ $val }}" @selected(old('hero_image_effect', $row->heroImageEffect()) === $val)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-slate-500 mt-1">ირთვება ყოველ სლაიდზე გადასვლისას (loop-შიც), სანამ ეს სლაიდი აქტიურია.</p>
                    </div>
                    <div>
                        <x-input-label for="hero_text_effect" value="ტექსტის გამოჩენის ეფექტი" />
                        <select id="hero_text_effect" name="hero_text_effect" class="mt-1 block w-full max-w-xs {{ $inputCls }}">
                            @foreach ([
                                'none' => 'გამორთული (მყისიერად ჩანს)',
                                'fade-up' => 'ქვემოდან აღმოცენებით (ნაგულისხმევი)',
                                'fade' => 'უბრალო გამოჩენა (fade)',
                                'zoom-in' => 'გადიდებით გამოჩენა',
                                'slide-left' => 'მარჯვნიდან შემოსრიალება',
                                'slide-right' => 'მარცხნიდან შემოსრიალება',
                            ] as $val => $label)
                                <option value="{{ $val }}" @selected(old('hero_text_effect', $row->heroTextEffect()) === $val)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-slate-500 mt-1">ეხება ამ სლაიდის სათაურს, ქვესათაურსა და ღილაკს ერთად.</p>
                    </div>
                </div>
            @endif

            @if ($row->type === 'text' && $columns <= 1)
                <div>
                    <x-input-label value="ტექსტი" />
                    <x-i18n-tabs>
                        <x-slot:ka><x-rich-editor name="body[ka]" :value="old('body.ka', $row->getTranslation('body', 'ka', false))" /></x-slot:ka>
                        <x-slot:en><x-rich-editor name="body[en]" :value="old('body.en', $row->getTranslation('body', 'en', false))" /></x-slot:en>
                        <x-slot:de><x-rich-editor name="body[de]" :value="old('body.de', $row->getTranslation('body', 'de', false))" /></x-slot:de>
                        <x-slot:pl><x-rich-editor name="body[pl]" :value="old('body.pl', $row->getTranslation('body', 'pl', false))" /></x-slot:pl>
                        <x-slot:es><x-rich-editor name="body[es]" :value="old('body.es', $row->getTranslation('body', 'es', false))" /></x-slot:es>
                    </x-i18n-tabs>
                </div>
            @endif

            @if ($row->type === 'news')
                <div>
                    <x-input-label for="limit" value="ბოლო რამდენი სიახლე გამოჩნდეს ავტომატურად" />
                    <x-text-input type="number" min="1" max="12" id="limit" name="limit" class="mt-1 block w-32" value="{{ old('limit', $row->newsLimit()) }}" />
                </div>
                <div>
                    <x-input-label value="ან აირჩიეთ კონკრეტული სიახლეები (თუ არცერთი არაა მონიშნული, ავტომატურად აჩვენებს უახლესებს)" />
                    <div class="mt-2 max-h-56 overflow-y-auto border border-slate-200 rounded-lg divide-y divide-slate-100">
                        @forelse ($articles as $article)
                            <label class="flex items-center gap-2 p-2.5 text-sm hover:bg-slate-50">
                                <input type="checkbox" name="article_ids[]" value="{{ $article->id }}"
                                    class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                    @checked(in_array($article->id, old('article_ids', $s['article_ids'] ?? [])))>
                                {{ $article->title }}
                            </label>
                        @empty
                            <div class="p-2.5 text-sm text-slate-500">სიახლეები ჯერ არ არის. <a href="{{ route('admin.articles.create') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">დაამატეთ სიახლე</a></div>
                        @endforelse
                    </div>
                </div>
            @endif

            @if ($row->type === 'products')
                <div>
                    <x-input-label for="layout" value="ჩვენების სტილი" />
                    <select id="layout" name="layout" class="mt-1 block w-full max-w-xs {{ $inputCls }}">
                        @foreach (['grid' => 'ბარათების ბადე', 'list' => 'კომპაქტური სია', 'carousel' => 'კარუსელი (გადაფურცვლადი)'] as $val => $label)
                            <option value="{{ $val }}" @selected(old('layout', $row->layout()) === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label for="card_style" value="ბარათის სტილი (ბადის რეჟიმისთვის)" />
                    <select id="card_style" name="card_style" class="mt-1 block w-full max-w-xs {{ $inputCls }}">
                        @foreach (['bordered' => 'ჩარჩოიანი ბარათი (ნაგულისხმევი)', 'frameless' => 'უჩარჩო — მომრგვალებული სურათი, ფონის გარეშე', 'flip' => 'შემობრუნებადი — hover-ზე ბრუნდება და უკანა მხარეს აღწერას აჩვენებს'] as $val => $label)
                            <option value="{{ $val }}" @selected(old('card_style', $row->cardStyle()) === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label for="image_height" value="სურათის ზომა" />
                    <select id="image_height" name="image_height" class="mt-1 block w-full max-w-xs {{ $inputCls }}">
                        @foreach (['120' => 'პატარა', '180' => 'საშუალო', '260' => 'დიდი', '360' => 'ძალიან დიდი', '500' => 'მაქსიმალური'] as $val => $label)
                            <option value="{{ $val }}" @selected((string) old('image_height', $row->productImageHeight()) === (string) $val)>{{ $label }} ({{ $val }}px)</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label for="limit" value="ბოლო რამდენი პროდუქტი გამოჩნდეს ავტომატურად" />
                    <x-text-input type="number" min="1" max="12" id="limit" name="limit" class="mt-1 block w-32" value="{{ old('limit', $row->itemLimit()) }}" />
                </div>
                <div>
                    <label class="flex items-center gap-2 text-sm text-slate-700">
                        <input type="checkbox" name="show_all_link" value="1" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" @checked(old('show_all_link', $s['show_all_link'] ?? true))>
                        "ყველა პროდუქტი" ღილაკის ჩვენება ბოლოში
                    </label>
                </div>
                <div>
                    <x-input-label value="ან აირჩიეთ კონკრეტული პროდუქტები (თუ არცერთი არაა მონიშნული, ავტომატურად აჩვენებს ბოლოს დამატებულებს)" />
                    <div class="mt-2 max-h-56 overflow-y-auto border border-slate-200 rounded-lg divide-y divide-slate-100">
                        @forelse ($products as $product)
                            <label class="flex items-center gap-2 p-2.5 text-sm hover:bg-slate-50">
                                <input type="checkbox" name="product_ids[]" value="{{ $product->id }}"
                                    class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                    @checked(in_array($product->id, old('product_ids', $s['product_ids'] ?? [])))>
                                {{ $product->name }}
                            </label>
                        @empty
                            <div class="p-2.5 text-sm text-slate-500">პროდუქტები ჯერ არ არის. <a href="{{ route('admin.products.create') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">დაამატეთ პროდუქტი</a></div>
                        @endforelse
                    </div>
                </div>
            @endif

            @if ($row->type === 'text')
                <div x-data="{ cols: {{ (int) old('columns', $columns) }} }" class="space-y-4">
                    <div>
                        <x-input-label for="columns" value="{{ $columnLabel }}" />
                        <select id="columns" name="columns" x-model.number="cols" class="mt-1 block w-full max-w-xs {{ $inputCls }}">
                            @foreach ([1, 2, 3, 4] as $n)
                                <option value="{{ $n }}" @selected(old('columns', $columns) == $n)>{{ $n }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-slate-500 mt-1">1-ზე მეტი აირჩევთ თუ გინდათ რომ ტექსტი რამდენიმე გვერდიგვერდა ბლოკად დაიყოს (თითოეულს თავისი სურათი/ტექსტი).</p>
                    </div>

                    <div x-show="cols == 2" x-cloak>
                        <x-input-label value="სურათისა და ტექსტის მხარეები" />
                        <select name="text_side" class="mt-1 block w-full max-w-xs {{ $inputCls }}">
                            <option value="left" @selected(old('text_side', $s['text_side'] ?? 'left') === 'left')>პირველი სვეტი მარცხნივ</option>
                            <option value="right" @selected(old('text_side', $s['text_side'] ?? 'left') === 'right')>პირველი სვეტი მარჯვნივ (შებრუნება)</option>
                        </select>
                        <p class="text-xs text-slate-500 mt-1">სვეტების მხარეების შეცვლა სურათის/ტექსტის თანმიმდევრობის ხელახლა დალაგების გარეშე.</p>
                    </div>

                    <div>
                        <x-input-label for="height" value="სექციის სიმაღლე" />
                        <select id="height" name="height" class="mt-1 block w-full max-w-xs {{ $inputCls }}">
                            @foreach (['auto' => 'ავტომატური (ნაგულისხმევი)', 'small' => 'პატარა', 'medium' => 'საშუალო', 'large' => 'დიდი', 'full' => 'მთელ ეკრანზე'] as $val => $label)
                                <option value="{{ $val }}" @selected(old('height', $s['height'] ?? 'auto') === $val)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="flex items-center gap-2 text-sm text-slate-700">
                            <input type="checkbox" name="fluid" value="1" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" @checked(old('fluid', $s['fluid'] ?? false))>
                            სრულ სიგანეზე გაშლა (fluid) — გვერდით ცარიელი ადგილის გარეშე
                        </label>
                    </div>
                </div>
            @endif

            <details class="border-t border-slate-100 pt-4 group">
                <summary class="text-sm font-medium text-slate-600 cursor-pointer select-none flex items-center gap-1.5 hover:text-slate-900">
                    <svg class="w-4 h-4 transition-transform group-open:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    დამატებითი პარამეტრები (ფერები, სივრცე{{ $hasColumns && $row->type !== 'text' ? ', სვეტები' : '' }})
                </summary>

                <div class="mt-4 space-y-4 pl-5">
                    <div x-data="{
                        groupSize: '{{ old('group_size', (string) ($s['group_size'] ?? 1)) }}',
                        sharedBg: {{ ! empty($s['group_shared_background']) ? 'true' : 'false' }},
                        sharedBgGradient: {{ ! empty($s['group_background_gradient']) ? 'true' : 'false' }},
                    }">
                        <x-input-label for="group_size" value="სექციების დაჯგუფება გვერდიგვერდ" />
                        <select id="group_size" name="group_size" x-model="groupSize" class="mt-1 block w-full max-w-xs {{ $inputCls }}">
                            <option value="1">არა — ცალკე, სრულ სიგანეზე (ნაგულისხმევი)</option>
                            <option value="2">კი — შემდეგ 1 სექციასთან ერთად (2 სვეტი)</option>
                            <option value="3">კი — შემდეგ 2 სექციასთან ერთად (3 სვეტი)</option>
                            <option value="4">კი — შემდეგ 3 სექციასთან ერთად (4 სვეტი)</option>
                        </select>
                        <p class="text-xs text-slate-500 mt-1">გვერდის სექციების სიაში ეს სექცია და მისი მომდევნო სექცია(ები) გამოჩნდება ერთმანეთის გვერდით, ერთ ხაზში (Desktop-ზე; მობილურზე ერთმანეთის ქვეშ).</p>

                        <div x-show="groupSize !== '1'" x-cloak class="mt-3 space-y-3">
                            <label class="flex items-center gap-2 text-sm text-slate-700">
                                <input type="checkbox" name="group_shared_background" value="1" x-model="sharedBg" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                საერთო ფონი მთელი ჯგუფისთვის
                            </label>
                            <p class="text-xs text-slate-500 -mt-2">ჩართვისას ქვემოთ არჩეული ფონი გავრცელდება მთელ ჯგუფზე ერთად — თითოეულ სვეტს საკუთარი ფონის ნაცვლად (ეს ცალკე, დამოუკიდებელი პარამეტრია, არ არის დაკავშირებული ამ სექციის ჩვეულებრივ ფონთან). გამორთვისას თითოეული სექცია საკუთარ ფონს (ან საერთო სქროლ-გრადიენტს) ინარჩუნებს.</p>

                            <div x-show="sharedBg" x-cloak class="space-y-3 border-l-2 border-slate-100 pl-4">
                                <div class="grid grid-cols-4 gap-4">
                                    <div>
                                        <x-input-label for="group_background_color" value="ჯგუფის ფონის ფერი" />
                                        <input type="color" id="group_background_color" name="group_background_color" value="{{ old('group_background_color', $s['group_background_color'] ?? '#ffffff') }}" class="mt-1 h-10 w-20 rounded-lg border-slate-300">
                                    </div>
                                    <div>
                                        <x-input-label for="group_text_color" value="ჯგუფის ტექსტის ფერი" />
                                        <input type="color" id="group_text_color" name="group_text_color" value="{{ old('group_text_color', $s['group_text_color'] ?? $s['text_color'] ?? '#111827') }}" class="mt-1 h-10 w-20 rounded-lg border-slate-300">
                                    </div>
                                    <div>
                                        <x-input-label for="group_padding_top" value="ზედა სივრცე (px)" />
                                        <input type="number" step="8" min="0" max="300" id="group_padding_top" name="group_padding_top" value="{{ old('group_padding_top', $s['group_padding_top'] ?? 48) }}" class="mt-1 block w-full {{ $inputCls }}">
                                    </div>
                                    <div>
                                        <x-input-label for="group_padding_bottom" value="ქვედა სივრცე (px)" />
                                        <input type="number" step="8" min="0" max="300" id="group_padding_bottom" name="group_padding_bottom" value="{{ old('group_padding_bottom', $s['group_padding_bottom'] ?? 48) }}" class="mt-1 block w-full {{ $inputCls }}">
                                    </div>
                                </div>
                                <label class="flex items-center gap-2 text-sm text-slate-700">
                                    <input type="checkbox" name="group_use_gradient" value="1" x-model="sharedBgGradient" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                    გრადიენტული ფონი (ერთი ფერის ნაცვლად)
                                </label>
                                <div x-show="sharedBgGradient" x-cloak class="grid grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="group_background_gradient" value="მეორე ფერი" />
                                        <input type="color" id="group_background_gradient" name="group_background_gradient" value="{{ old('group_background_gradient', $s['group_background_gradient'] ?? '#f3f4f6') }}" class="mt-1 h-10 w-20 rounded-lg border-slate-300">
                                    </div>
                                    <div>
                                        <x-input-label for="group_gradient_angle" value="კუთხე (გრადუსი)" />
                                        <input type="number" min="0" max="360" id="group_gradient_angle" name="group_gradient_angle" value="{{ old('group_gradient_angle', $s['group_gradient_angle'] ?? 135) }}" class="mt-1 block w-24 {{ $inputCls }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($hasColumns && $row->type !== 'text')
                        <div>
                            <x-input-label for="columns" value="{{ $columnLabel }}" />
                            <select id="columns" name="columns" class="mt-1 block w-full max-w-xs {{ $inputCls }}">
                                @foreach ([1, 2, 3, 4] as $n)
                                    <option value="{{ $n }}" @selected(old('columns', $columns) == $n)>{{ $n }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div>
                        <x-input-label for="text_animation" value="სათაურის ანიმაცია (მოძრავი ტექსტი)" />
                        <select id="text_animation" name="text_animation" class="mt-1 block w-full max-w-xs {{ $inputCls }}">
                            @foreach ([
                                'none' => 'გამორთული (ნაგულისხმევი)',
                                'fade-up' => 'ზემოდან აღმოცენება',
                                'typewriter' => 'ტაიპრაითერი (ასო-ასო აკრეფა)',
                                'stagger' => 'ასო-ასო გამოჩენა',
                                'shimmer' => 'პულსაცია (shimmer)',
                                'rotate' => 'რამდენიმე ტექსტის მონაცვლეობა (აკრეფა → წაშლა → შემდეგი)',
                            ] as $val => $label)
                                <option value="{{ $val }}" @selected(old('text_animation', $row->textAnimation()) === $val)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-slate-500 mt-1">ეხება ამ სექციის სათაურს — ირთვება სქროლისას, ეკრანში გამოჩენისთანავე, ერთხელ.</p>
                    </div>

                    @if ($row->type === 'map')
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <x-input-label for="lat" value="განედი (latitude, -90 – 90)" />
                                <input type="number" step="0.000001" id="lat" name="lat" placeholder="მაგ: 39.4699" value="{{ old('lat', $row->mapLat()) }}" class="mt-1 block w-full {{ $inputCls }}">
                            </div>
                            <div>
                                <x-input-label for="lng" value="გრძედი (longitude, -180 – 180)" />
                                <input type="number" step="0.000001" id="lng" name="lng" placeholder="მაგ: -0.3763" value="{{ old('lng', $row->mapLng()) }}" class="mt-1 block w-full {{ $inputCls }}">
                            </div>
                            <div>
                                <x-input-label for="zoom" value="მასშტაბი" />
                                <input type="number" min="1" max="20" id="zoom" name="zoom" value="{{ old('zoom', $row->mapZoom()) }}" class="mt-1 block w-full {{ $inputCls }}">
                            </div>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">კოორდინატები აიღეთ Google Maps-იდან (დააჭირეთ მარჯვენა ღილაკს ლოკაციაზე რუკაზე და აირჩიეთ კოორდინატები).</p>
                    @endif

                    <div class="grid grid-cols-2 gap-4" x-data="{ overrideBg: {{ ! empty($s['override_background']) ? 'true' : 'false' }} }">
                        <div class="col-span-2">
                            <label class="flex items-center gap-2 text-sm text-slate-700">
                                <input type="checkbox" name="override_background" value="1" x-model="overrideBg" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                ამ სექციისთვის ცალკე ფონის გამოყენება (გადაფარავს გვერდის საერთო სქროლ-გრადიენტს)
                            </label>
                        </div>
                        <div x-show="overrideBg" x-cloak>
                            <x-input-label for="background_color" value="ფონის ფერი" />
                            <input type="color" id="background_color" name="background_color" value="{{ old('background_color', $s['background_color'] ?? '#ffffff') }}" class="mt-1 h-10 w-20 rounded-lg border-slate-300">
                        </div>
                        <div>
                            <x-input-label for="text_color" value="ტექსტის ფერი" />
                            <input type="color" id="text_color" name="text_color" value="{{ old('text_color', $s['text_color'] ?? '#111827') }}" class="mt-1 h-10 w-20 rounded-lg border-slate-300">
                        </div>
                        <div class="col-span-2" x-show="overrideBg" x-cloak x-data="{ useGradient: {{ ! empty($s['background_gradient']) ? 'true' : 'false' }} }">
                            <label class="flex items-center gap-2 text-sm text-slate-700 mb-2">
                                <input type="checkbox" name="use_gradient" value="1" x-model="useGradient" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                გრადიენტული ფონი (ერთი ფერის ნაცვლად)
                            </label>
                            <div x-show="useGradient" x-cloak class="space-y-3 pl-1">
                                <div>
                                    <x-input-label value="მზა პალიტრა (დააჭირეთ ასარჩევად)" />
                                    <div class="flex flex-wrap gap-2 mt-1">
                                        @foreach ([
                                            ['name' => 'Sunset', 'from' => '#ff7e5f', 'to' => '#feb47b'],
                                            ['name' => 'Ocean', 'from' => '#2193b0', 'to' => '#6dd5ed'],
                                            ['name' => 'Purple Dream', 'from' => '#667eea', 'to' => '#764ba2'],
                                            ['name' => 'Emerald', 'from' => '#11998e', 'to' => '#38ef7d'],
                                            ['name' => 'Berry', 'from' => '#c31432', 'to' => '#240b36'],
                                            ['name' => 'Sky', 'from' => '#00c6ff', 'to' => '#0072ff'],
                                            ['name' => 'Rose Gold', 'from' => '#f857a6', 'to' => '#ff5858'],
                                            ['name' => 'Peach', 'from' => '#ffecd2', 'to' => '#fcb69f'],
                                            ['name' => 'Golden Pond', 'from' => '#f6d365', 'to' => '#134e5e'],
                                        ] as $g)
                                            <button type="button"
                                                class="w-9 h-9 rounded-lg border border-slate-200 shadow-sm hover:scale-110 transition"
                                                style="background:linear-gradient(135deg, {{ $g['from'] }}, {{ $g['to'] }})"
                                                @click="useGradient = true; document.getElementById('background_color').value = '{{ $g['from'] }}'; document.getElementById('background_gradient').value = '{{ $g['to'] }}';"
                                                title="{{ $g['name'] }} ({{ $g['from'] }} → {{ $g['to'] }})"
                                            ></button>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="background_gradient" value="მეორე ფერი" />
                                        <input type="color" id="background_gradient" name="background_gradient" value="{{ old('background_gradient', $s['background_gradient'] ?? '#feb47b') }}" class="mt-1 h-10 w-20 rounded-lg border-slate-300">
                                    </div>
                                    <div>
                                        <x-input-label for="gradient_angle" value="კუთხე (გრადუსი)" />
                                        <input type="number" min="0" max="360" id="gradient_angle" name="gradient_angle" value="{{ old('gradient_angle', $s['gradient_angle'] ?? 135) }}" class="mt-1 block w-24 {{ $inputCls }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <x-input-label for="text_align" value="ტექსტის განლაგება" />
                            <select id="text_align" name="text_align" class="mt-1 block w-full {{ $inputCls }}">
                                @foreach (['left' => 'მარცხნივ', 'center' => 'ცენტრში', 'right' => 'მარჯვნივ'] as $val => $label)
                                    <option value="{{ $val }}" @selected(old('text_align', $s['text_align'] ?? 'center') === $val)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="background_image" value="ფონის სურათი" />
                            @if (! empty($s['background_image']))
                                <div class="flex items-center gap-2 my-1">
                                    <img src="{{ asset('storage/' . $s['background_image']) }}" class="w-20 h-12 object-cover rounded-lg">
                                    <label class="text-xs text-red-600 flex items-center gap-1">
                                        <input type="checkbox" name="remove_background_image" value="1"> წაშლა
                                    </label>
                                </div>
                            @endif
                            <input type="file" id="background_image" name="background_image" class="mt-1 block w-full text-sm">
                        </div>
                        <div>
                            <x-input-label for="padding_top" value="ზედა სივრცე (px)" />
                            <input type="number" step="8" min="0" max="300" id="padding_top" name="padding_top" value="{{ old('padding_top', $s['padding_top'] ?? 0) }}" class="mt-1 block w-32 {{ $inputCls }}">
                        </div>
                        <div>
                            <x-input-label for="padding_bottom" value="ქვედა სივრცე (px)" />
                            <input type="number" step="8" min="0" max="300" id="padding_bottom" name="padding_bottom" value="{{ old('padding_bottom', $s['padding_bottom'] ?? 0) }}" class="mt-1 block w-32 {{ $inputCls }}">
                        </div>
                    </div>
                </div>
            </details>

            <div class="border-t border-slate-100 pt-4">
                <x-input-label value="ჩვენება სხვა გვერდებზეც" />
                <p class="text-xs text-slate-500 mt-1 mb-2">მონიშნე გვერდები სადაც გინდა რომ ეს იგივე რიგი გამოჩნდეს — ერთი და იგივე კონტენტი, ცალკე კოპირების გარეშე. მისი რედაქტირება აისახება ყველგან, სადაც ის ჩანს.</p>
                <div class="max-h-48 overflow-y-auto border border-slate-200 rounded-lg divide-y divide-slate-100">
                    @forelse ($otherPages as $otherPage)
                        <label class="flex items-center gap-2 p-2.5 text-sm hover:bg-slate-50">
                            <input type="checkbox" name="shared_page_ids[]" value="{{ $otherPage->id }}"
                                class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                @checked(in_array($otherPage->id, old('shared_page_ids', $sharedPageIds)))>
                            {{ $otherPage->title }}
                        </label>
                    @empty
                        <div class="p-2.5 text-sm text-slate-500">სხვა გვერდი ჯერ არ არის.</div>
                    @endforelse
                </div>
            </div>

            <button class="admin-btn-primary">შენახვა</button>
        </form>

        @if ($row->textAnimation() === 'rotate' && $row->usesItemsForOwnType())
            <div class="p-4 bg-amber-50 text-amber-700 border border-amber-200 rounded-lg text-sm">
                „რამდენიმე ტექსტის მონაცვლეობა" ამ ტიპის რიგისთვის (რომელიც უკვე იყენებს სია — სლაიდები/სურათები/სვეტები) ვერ იმუშავებს რამდენიმე ცალკე ტექსტით — მხოლოდ სათაური მოციმციმებით გამოჩნდება. თუ გინდათ რეალურად რამდენიმე ტექსტის მონაცვლეობა, აირჩიეთ სხვა ტიპის რიგი (მაგ. ტექსტი, ერთსვეტიანი).
            </div>
        @elseif ($row->textAnimation() === 'rotate')
            <div class="admin-card p-6">
                <h3 class="font-medium text-slate-800 mb-1">მოსანაცვლებელი ტექსტები</h3>
                <p class="text-xs text-slate-500 mb-4">დაამატეთ რამდენიმე მოკლე ტექსტი — საიტზე თითოეული აიწერება, გაჩერდება, წაიშლება და შემდეგი დაიწყება; ბოლოს ისევ პირველიდან. თუ არცერთი არაა დამატებული, უბრალოდ ზემოთ არსებული სათაური გამოჩნდება უცვლელად.</p>

                <ul id="rotate-items-list" class="space-y-2 mb-6">
                    @forelse ($row->items as $item)
                        <li data-id="{{ $item->id }}" class="flex items-center gap-3 border border-slate-200 rounded-lg p-2 cursor-grab hover:border-slate-300 transition">
                            <svg class="w-5 h-5 text-slate-300 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M7 4a1 1 0 11-2 0 1 1 0 012 0zM7 10a1 1 0 11-2 0 1 1 0 012 0zM7 16a1 1 0 11-2 0 1 1 0 012 0zM15 4a1 1 0 11-2 0 1 1 0 012 0zM15 10a1 1 0 11-2 0 1 1 0 012 0zM15 16a1 1 0 11-2 0 1 1 0 012 0z" /></svg>
                            <form action="{{ route('admin.row-items.update', $item) }}" method="POST" class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-2">
                                @csrf @method('PUT')
                                <input type="text" name="title[ka]" value="{{ $item->getTranslation('title', 'ka', false) }}" placeholder="ტექსტი (ka)" class="{{ $inputCls }}">
                                <input type="text" name="title[en]" value="{{ $item->getTranslation('title', 'en', false) }}" placeholder="Text (en)" class="{{ $inputCls }}">
                                <div class="flex items-center gap-2">
                                    <input type="text" name="title[de]" value="{{ $item->getTranslation('title', 'de', false) }}" placeholder="Text (de)" class="flex-1 {{ $inputCls }}">
                                    <button class="text-xs text-indigo-600 hover:text-indigo-700 font-medium shrink-0">შენახვა</button>
                                </div>
                            </form>
                            <form action="{{ route('admin.row-items.destroy', $item) }}" method="POST" onsubmit="return confirm('წავშალო?');">
                                @csrf @method('DELETE')
                                <button class="text-sm text-red-500 hover:text-red-700 shrink-0">წაშლა</button>
                            </form>
                        </li>
                    @empty
                        <li class="text-sm text-slate-400">ჯერ არაფერია დამატებული.</li>
                    @endforelse
                </ul>

                <form action="{{ route('admin.row-items.store', $row) }}" method="POST" class="border-t border-slate-100 pt-4 grid grid-cols-1 md:grid-cols-3 gap-2">
                    @csrf
                    <input type="text" name="title[ka]" placeholder="ტექსტი (ka)" class="{{ $inputCls }}">
                    <input type="text" name="title[en]" placeholder="Text (en)" class="{{ $inputCls }}">
                    <div class="flex items-center gap-2">
                        <input type="text" name="title[de]" placeholder="Text (de)" class="flex-1 {{ $inputCls }}">
                        <button class="admin-btn-primary py-1.5 text-xs shrink-0">+ დამატება</button>
                    </div>
                </form>
            </div>

            @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
            <script>
                const rotateItemsList = document.getElementById('rotate-items-list');
                if (rotateItemsList) {
                    Sortable.create(rotateItemsList, {
                        animation: 150,
                        onEnd: function () {
                            const ids = Array.from(rotateItemsList.children).map(li => li.dataset.id).filter(Boolean);
                            fetch('{{ route('admin.row-items.reorder') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                },
                                body: JSON.stringify({ ids }),
                            });
                        },
                    });
                }
            </script>
            @endpush
        @endif

        @if ($showItems)
            <div class="admin-card p-6">
                <h3 class="font-medium text-slate-800 mb-4">{{ $row->type === 'slider' ? 'სლაიდები' : ($row->type === 'image' ? 'სურათები' : 'სვეტების შიგთავსი') }}</h3>

                <ul id="items-list" class="space-y-3 mb-6">
                    @forelse ($row->items as $item)
                        <li data-id="{{ $item->id }}" class="flex items-start gap-4 border border-slate-200 rounded-lg p-3 cursor-grab hover:border-slate-300 transition">
                            <svg class="w-5 h-5 text-slate-300 shrink-0 mt-2" fill="currentColor" viewBox="0 0 20 20"><path d="M7 4a1 1 0 11-2 0 1 1 0 012 0zM7 10a1 1 0 11-2 0 1 1 0 012 0zM7 16a1 1 0 11-2 0 1 1 0 012 0zM15 4a1 1 0 11-2 0 1 1 0 012 0zM15 10a1 1 0 11-2 0 1 1 0 012 0zM15 16a1 1 0 11-2 0 1 1 0 012 0z" /></svg>
                            <form action="{{ route('admin.row-items.update', $item) }}" method="POST" enctype="multipart/form-data" class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-2">
                                @csrf @method('PUT')
                                @if ($item->image)
                                    <div class="md:col-span-2 flex items-center gap-2">
                                        <img src="{{ asset('storage/' . $item->image) }}" class="w-24 h-16 object-cover rounded-lg shrink-0">
                                        <div>
                                            <label class="text-xs text-slate-500">სურათის სიმაღლე (px)</label>
                                            <input type="number" name="image_height" min="20" max="800" step="10" value="{{ $item->image_height }}" placeholder="ავტომატური" class="block text-xs w-32 {{ $inputCls }}">
                                        </div>
                                        @if ($row->type === 'image')
                                            <div>
                                                <label class="text-xs text-slate-500">განლაგება</label>
                                                <select name="align" class="block text-xs w-28 {{ $inputCls }}">
                                                    @foreach (['left' => 'მარცხნივ', 'center' => 'ცენტრში', 'right' => 'მარჯვნივ'] as $val => $label)
                                                        <option value="{{ $val }}" @selected($item->align === $val)>{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label class="text-xs text-slate-500">სიგანე (%)</label>
                                                <input type="number" name="width" min="10" max="100" step="5" value="{{ $item->width }}" class="block text-xs w-20 {{ $inputCls }}">
                                            </div>
                                        @endif
                                    </div>
                                @endif
                                <div class="md:col-span-2">
                                    <x-i18n-tabs>
                                        <x-slot:ka>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                                <input type="text" name="title[ka]" value="{{ $item->getTranslation('title', 'ka', false) }}" placeholder="{{ $row->type === 'image' ? 'წარწერა (caption / alt ტექსტი)' : 'სათაური' }}" class="{{ $inputCls }}">
                                                @if ($row->type !== 'image')
                                                    <input type="text" name="subtitle[ka]" value="{{ $item->getTranslation('subtitle', 'ka', false) }}" placeholder="ქვესათაური" class="{{ $inputCls }}">
                                                @endif
                                                @if ($row->type === 'text')
                                                    <div class="md:col-span-2">
                                                        <x-rich-editor name="body[ka]" :id="'item-' . $item->id . '-body-ka'" :value="$item->getTranslation('body', 'ka', false)" height="140px" />
                                                    </div>
                                                @endif
                                            </div>
                                        </x-slot:ka>
                                        <x-slot:en>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                                <input type="text" name="title[en]" value="{{ $item->getTranslation('title', 'en', false) }}" placeholder="{{ $row->type === 'image' ? 'Caption / alt text' : 'Title' }}" class="{{ $inputCls }}">
                                                @if ($row->type !== 'image')
                                                    <input type="text" name="subtitle[en]" value="{{ $item->getTranslation('subtitle', 'en', false) }}" placeholder="Subtitle" class="{{ $inputCls }}">
                                                @endif
                                                @if ($row->type === 'text')
                                                    <div class="md:col-span-2">
                                                        <x-rich-editor name="body[en]" :id="'item-' . $item->id . '-body-en'" :value="$item->getTranslation('body', 'en', false)" height="140px" />
                                                    </div>
                                                @endif
                                            </div>
                                        </x-slot:en>
                                        <x-slot:de>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                                <input type="text" name="title[de]" value="{{ $item->getTranslation('title', 'de', false) }}" placeholder="{{ $row->type === 'image' ? 'Bildunterschrift / Alt-Text' : 'Titel' }}" class="{{ $inputCls }}">
                                                @if ($row->type !== 'image')
                                                    <input type="text" name="subtitle[de]" value="{{ $item->getTranslation('subtitle', 'de', false) }}" placeholder="Untertitel" class="{{ $inputCls }}">
                                                @endif
                                                @if ($row->type === 'text')
                                                    <div class="md:col-span-2">
                                                        <x-rich-editor name="body[de]" :id="'item-' . $item->id . '-body-de'" :value="$item->getTranslation('body', 'de', false)" height="140px" />
                                                    </div>
                                                @endif
                                            </div>
                                        </x-slot:de>
                                        <x-slot:pl>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                                <input type="text" name="title[pl]" value="{{ $item->getTranslation('title', 'pl', false) }}" placeholder="{{ $row->type === 'image' ? 'Podpis / tekst alt' : 'Tytuł' }}" class="{{ $inputCls }}">
                                                @if ($row->type !== 'image')
                                                    <input type="text" name="subtitle[pl]" value="{{ $item->getTranslation('subtitle', 'pl', false) }}" placeholder="Podtytuł" class="{{ $inputCls }}">
                                                @endif
                                                @if ($row->type === 'text')
                                                    <div class="md:col-span-2">
                                                        <x-rich-editor name="body[pl]" :id="'item-' . $item->id . '-body-pl'" :value="$item->getTranslation('body', 'pl', false)" height="140px" />
                                                    </div>
                                                @endif
                                            </div>
                                        </x-slot:pl>
                                        <x-slot:es>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                                <input type="text" name="title[es]" value="{{ $item->getTranslation('title', 'es', false) }}" placeholder="{{ $row->type === 'image' ? 'Título / texto alt' : 'Título' }}" class="{{ $inputCls }}">
                                                @if ($row->type !== 'image')
                                                    <input type="text" name="subtitle[es]" value="{{ $item->getTranslation('subtitle', 'es', false) }}" placeholder="Subtítulo" class="{{ $inputCls }}">
                                                @endif
                                                @if ($row->type === 'text')
                                                    <div class="md:col-span-2">
                                                        <x-rich-editor name="body[es]" :id="'item-' . $item->id . '-body-es'" :value="$item->getTranslation('body', 'es', false)" height="140px" />
                                                    </div>
                                                @endif
                                            </div>
                                        </x-slot:es>
                                    </x-i18n-tabs>
                                </div>
                                <input type="text" name="link" value="{{ $item->link }}" placeholder="ბმული (არასავალდებულო)" class="{{ $inputCls }}">
                                <div class="flex items-center gap-2">
                                    <input type="file" name="image" class="text-xs w-28">
                                    <button class="text-xs text-indigo-600 hover:text-indigo-700 font-medium">შენახვა</button>
                                </div>
                            </form>
                            <form action="{{ route('admin.row-items.destroy', $item) }}" method="POST" onsubmit="return confirm('წავშალო?');">
                                @csrf @method('DELETE')
                                <button class="text-sm text-red-500 hover:text-red-700">წაშლა</button>
                            </form>
                        </li>
                    @empty
                        <li class="text-sm text-slate-400">ჯერ არაფერია დამატებული.</li>
                    @endforelse
                </ul>

                <form action="{{ route('admin.row-items.store', $row) }}" method="POST" enctype="multipart/form-data" class="border-t border-slate-100 pt-4 grid grid-cols-1 md:grid-cols-2 gap-2">
                    @csrf
                    <div class="md:col-span-2">
                        <x-i18n-tabs>
                            <x-slot:ka>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                    <input type="text" name="title[ka]" placeholder="{{ $row->type === 'image' ? 'წარწერა (caption / alt ტექსტი)' : 'სათაური' }}" class="{{ $inputCls }}">
                                    @if ($row->type !== 'image')
                                        <input type="text" name="subtitle[ka]" placeholder="ქვესათაური" class="{{ $inputCls }}">
                                    @endif
                                    @if ($row->type === 'text')
                                        <div class="md:col-span-2">
                                            <x-rich-editor name="body[ka]" id="new-item-body-ka" height="140px" />
                                        </div>
                                    @endif
                                </div>
                            </x-slot:ka>
                            <x-slot:en>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                    <input type="text" name="title[en]" placeholder="{{ $row->type === 'image' ? 'Caption / alt text' : 'Title' }}" class="{{ $inputCls }}">
                                    @if ($row->type !== 'image')
                                        <input type="text" name="subtitle[en]" placeholder="Subtitle" class="{{ $inputCls }}">
                                    @endif
                                    @if ($row->type === 'text')
                                        <div class="md:col-span-2">
                                            <x-rich-editor name="body[en]" id="new-item-body-en" height="140px" />
                                        </div>
                                    @endif
                                </div>
                            </x-slot:en>
                            <x-slot:de>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                    <input type="text" name="title[de]" placeholder="{{ $row->type === 'image' ? 'Bildunterschrift / Alt-Text' : 'Titel' }}" class="{{ $inputCls }}">
                                    @if ($row->type !== 'image')
                                        <input type="text" name="subtitle[de]" placeholder="Untertitel" class="{{ $inputCls }}">
                                    @endif
                                    @if ($row->type === 'text')
                                        <div class="md:col-span-2">
                                            <x-rich-editor name="body[de]" id="new-item-body-de" height="140px" />
                                        </div>
                                    @endif
                                </div>
                            </x-slot:de>
                            <x-slot:pl>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                    <input type="text" name="title[pl]" placeholder="{{ $row->type === 'image' ? 'Podpis / tekst alt' : 'Tytuł' }}" class="{{ $inputCls }}">
                                    @if ($row->type !== 'image')
                                        <input type="text" name="subtitle[pl]" placeholder="Podtytuł" class="{{ $inputCls }}">
                                    @endif
                                    @if ($row->type === 'text')
                                        <div class="md:col-span-2">
                                            <x-rich-editor name="body[pl]" id="new-item-body-pl" height="140px" />
                                        </div>
                                    @endif
                                </div>
                            </x-slot:pl>
                            <x-slot:es>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                    <input type="text" name="title[es]" placeholder="{{ $row->type === 'image' ? 'Título / texto alt' : 'Título' }}" class="{{ $inputCls }}">
                                    @if ($row->type !== 'image')
                                        <input type="text" name="subtitle[es]" placeholder="Subtítulo" class="{{ $inputCls }}">
                                    @endif
                                    @if ($row->type === 'text')
                                        <div class="md:col-span-2">
                                            <x-rich-editor name="body[es]" id="new-item-body-es" height="140px" />
                                        </div>
                                    @endif
                                </div>
                            </x-slot:es>
                        </x-i18n-tabs>
                    </div>
                    <input type="text" name="link" placeholder="ბმული (არასავალდებულო)" class="{{ $inputCls }}">
                    <div class="flex items-center gap-2 flex-wrap">
                        <input type="file" name="image" class="text-xs w-28">
                        <input type="number" name="image_height" min="20" max="800" step="10" placeholder="სურათის სიმაღლე (px)" class="text-xs w-36 {{ $inputCls }}">
                        @if ($row->type === 'image')
                            <select name="align" class="text-xs w-28 {{ $inputCls }}">
                                @foreach (['left' => 'მარცხნივ', 'center' => 'ცენტრში', 'right' => 'მარჯვნივ'] as $val => $label)
                                    <option value="{{ $val }}" @selected($val === 'center')>{{ $label }}</option>
                                @endforeach
                            </select>
                            <input type="number" name="width" min="10" max="100" step="5" value="100" placeholder="სიგანე (%)" class="text-xs w-24 {{ $inputCls }}">
                        @endif
                        <button class="admin-btn-primary py-1.5 text-xs">+ დამატება</button>
                    </div>
                </form>
            </div>
        @endif
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
    <script>
        const itemsList = document.getElementById('items-list');
        if (itemsList) {
            Sortable.create(itemsList, {
                animation: 150,
                onEnd: function () {
                    const ids = Array.from(itemsList.children).map(li => li.dataset.id).filter(Boolean);
                    fetch('{{ route('admin.row-items.reorder') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({ ids }),
                    });
                },
            });
        }
    </script>
    @endpush
</x-admin-layout>
