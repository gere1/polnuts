<x-admin-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">მენიუს მართვა</h2>
            <p class="text-sm text-slate-500 mt-0.5">გადაათრიეთ პუნქტები დასალაგებლად — ეს მენიუ ჩანს საიტის header-ში</p>
        </div>
    </x-slot>

    @php $defaultLocale = \App\Models\Setting::current()->default_locale; @endphp

    <div class="max-w-3xl mx-auto">
        @if (session('status'))
            <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg text-sm">{{ session('status') }}</div>
        @endif

        <ul id="menu-list" class="space-y-3 mb-6">
            @forelse ($items as $item)
                <li data-id="{{ $item->id }}" class="admin-card admin-card-hover p-4 flex items-start gap-3 cursor-grab">
                    <svg class="w-5 h-5 text-slate-300 shrink-0 mt-2.5" fill="currentColor" viewBox="0 0 20 20"><path d="M7 4a1 1 0 11-2 0 1 1 0 012 0zM7 10a1 1 0 11-2 0 1 1 0 012 0zM7 16a1 1 0 11-2 0 1 1 0 012 0zM15 4a1 1 0 11-2 0 1 1 0 012 0zM15 10a1 1 0 11-2 0 1 1 0 012 0zM15 16a1 1 0 11-2 0 1 1 0 012 0z" /></svg>

                    <span class="h-9 w-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                        @if ($item->file)
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V19a2 2 0 01-2 2zM13 3v6h6" /></svg>
                        @elseif ($item->page_id)
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        @else
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
                        @endif
                    </span>

                    <form action="{{ route('admin.menu.update', $item) }}" method="POST" enctype="multipart/form-data"
                          class="flex-1 space-y-3"
                          x-data="{ linkType: '{{ $item->file ? 'file' : ($item->page_id ? 'page' : 'url') }}' }">
                        @csrf @method('PUT')

                        <x-i18n-tabs>
                            <x-slot:ka><input type="text" name="label[ka]" value="{{ $item->getTranslation('label', 'ka', false) }}" placeholder="სახელი" @required($defaultLocale === 'ka') class="rounded-lg border-slate-300 text-sm w-full focus:border-indigo-500 focus:ring-indigo-500"></x-slot:ka>
                            <x-slot:en><input type="text" name="label[en]" value="{{ $item->getTranslation('label', 'en', false) }}" placeholder="Label" @required($defaultLocale === 'en') class="rounded-lg border-slate-300 text-sm w-full focus:border-indigo-500 focus:ring-indigo-500"></x-slot:en>
                            <x-slot:de><input type="text" name="label[de]" value="{{ $item->getTranslation('label', 'de', false) }}" placeholder="Titel" @required($defaultLocale === 'de') class="rounded-lg border-slate-300 text-sm w-full focus:border-indigo-500 focus:ring-indigo-500"></x-slot:de>
                            <x-slot:pl><input type="text" name="label[pl]" value="{{ $item->getTranslation('label', 'pl', false) }}" placeholder="Tytuł" @required($defaultLocale === 'pl') class="rounded-lg border-slate-300 text-sm w-full focus:border-indigo-500 focus:ring-indigo-500"></x-slot:pl>
                            <x-slot:es><input type="text" name="label[es]" value="{{ $item->getTranslation('label', 'es', false) }}" placeholder="Título" @required($defaultLocale === 'es') class="rounded-lg border-slate-300 text-sm w-full focus:border-indigo-500 focus:ring-indigo-500"></x-slot:es>
                        </x-i18n-tabs>

                        <div class="flex flex-wrap items-center gap-3">
                            <select x-model="linkType" class="rounded-lg border-slate-300 text-xs focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="page">გვერდი</option>
                                <option value="url">გარე ბმული</option>
                                <option value="file">ფაილი (PDF/სურათი)</option>
                            </select>

                            <div x-show="linkType === 'page'" class="flex-1 min-w-[10rem]">
                                <select name="page_id" class="rounded-lg border-slate-300 text-sm w-full focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">—</option>
                                    @foreach ($pages as $page)
                                        <option value="{{ $page->id }}" @selected($item->page_id === $page->id)>{{ $page->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div x-show="linkType === 'url'" class="flex-1 min-w-[10rem]">
                                <input type="text" name="url" value="{{ $item->url }}" placeholder="/news ან https://..." class="rounded-lg border-slate-300 text-sm w-full focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div x-show="linkType === 'file'" class="flex-1 min-w-[12rem] flex items-center gap-2">
                                @if ($item->file)
                                    <a href="{{ asset('storage/' . $item->file) }}" target="_blank" class="text-xs text-indigo-600 hover:text-indigo-700 underline shrink-0">მიმდინარე ფაილი</a>
                                @endif
                                <input type="file" name="file" class="text-xs flex-1">
                            </div>

                            <label class="flex items-center gap-1.5 text-xs text-slate-600 shrink-0">
                                <input type="checkbox" name="open_in_new_tab" value="1" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" @checked($item->open_in_new_tab)>
                                ახალ ჩანართში
                            </label>

                            <button title="შენახვა" class="p-2 rounded-lg text-indigo-600 hover:bg-indigo-50 shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            </button>
                        </div>
                    </form>

                    <form action="{{ route('admin.menu.destroy', $item) }}" method="POST" onsubmit="return confirm('წავშალო მენიუს პუნქტი?');" class="shrink-0">
                        @csrf @method('DELETE')
                        <button title="წაშლა" class="p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                    </form>
                </li>
            @empty
                <li class="admin-card p-10 text-center text-slate-400 text-sm">მენიუს პუნქტები ჯერ არ არის.</li>
            @endforelse
        </ul>

        <form action="{{ route('admin.menu.store') }}" method="POST" enctype="multipart/form-data" class="admin-card p-4 space-y-3" x-data="{ linkType: 'page' }">
            @csrf

            <x-i18n-tabs>
                <x-slot:ka><input type="text" name="label[ka]" placeholder="სახელი" @required($defaultLocale === 'ka') class="rounded-lg border-slate-300 text-sm w-full focus:border-indigo-500 focus:ring-indigo-500"></x-slot:ka>
                <x-slot:en><input type="text" name="label[en]" placeholder="Label" @required($defaultLocale === 'en') class="rounded-lg border-slate-300 text-sm w-full focus:border-indigo-500 focus:ring-indigo-500"></x-slot:en>
                <x-slot:de><input type="text" name="label[de]" placeholder="Titel" @required($defaultLocale === 'de') class="rounded-lg border-slate-300 text-sm w-full focus:border-indigo-500 focus:ring-indigo-500"></x-slot:de>
                <x-slot:pl><input type="text" name="label[pl]" placeholder="Tytuł" @required($defaultLocale === 'pl') class="rounded-lg border-slate-300 text-sm w-full focus:border-indigo-500 focus:ring-indigo-500"></x-slot:pl>
                <x-slot:es><input type="text" name="label[es]" placeholder="Título" @required($defaultLocale === 'es') class="rounded-lg border-slate-300 text-sm w-full focus:border-indigo-500 focus:ring-indigo-500"></x-slot:es>
            </x-i18n-tabs>

            <div class="flex flex-wrap items-center gap-3">
                <select x-model="linkType" class="rounded-lg border-slate-300 text-xs focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="page">გვერდი</option>
                    <option value="url">გარე ბმული</option>
                    <option value="file">ფაილი (PDF/სურათი)</option>
                </select>

                <div x-show="linkType === 'page'" class="flex-1 min-w-[10rem]">
                    <select name="page_id" class="rounded-lg border-slate-300 text-sm w-full focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">—</option>
                        @foreach ($pages as $page)
                            <option value="{{ $page->id }}">{{ $page->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div x-show="linkType === 'url'" class="flex-1 min-w-[10rem]">
                    <input type="text" name="url" placeholder="/news ან https://..." class="rounded-lg border-slate-300 text-sm w-full focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div x-show="linkType === 'file'" class="flex-1 min-w-[12rem]">
                    <input type="file" name="file" class="text-xs w-full">
                </div>

                <label class="flex items-center gap-1.5 text-xs text-slate-600 shrink-0">
                    <input type="checkbox" name="open_in_new_tab" value="1" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    ახალ ჩანართში
                </label>

                <button class="admin-btn-primary py-1.5 text-xs whitespace-nowrap">+ დამატება</button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
    <script>
        const menuList = document.getElementById('menu-list');
        if (menuList) {
            Sortable.create(menuList, {
                animation: 150,
                onEnd: function () {
                    const ids = Array.from(menuList.children).map(li => li.dataset.id).filter(Boolean);
                    fetch('{{ route('admin.menu.reorder') }}', {
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
