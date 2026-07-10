@php
    $adminSetting = \App\Models\Setting::current();
    $navItems = [
        ['route' => 'admin.pages.index', 'pattern' => 'admin.pages.*', 'label' => 'გვერდები', 'icon' => 'pages'],
        ['route' => 'admin.products.index', 'pattern' => 'admin.products.*', 'label' => 'პროდუქტები', 'icon' => 'products'],
        ['route' => 'admin.articles.index', 'pattern' => 'admin.articles.*', 'label' => 'სიახლეები', 'icon' => 'news'],
        ['route' => 'admin.menu.index', 'pattern' => 'admin.menu.*', 'label' => 'მენიუ', 'icon' => 'menu'],
        ['route' => 'admin.settings.edit', 'pattern' => 'admin.settings.*', 'label' => 'პარამეტრები', 'icon' => 'settings'],
    ];
@endphp
<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} · ადმინი</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="font-sans antialiased bg-slate-100 text-slate-800" x-data="{ sidebarOpen: false }">

    <div class="min-h-screen md:flex">
        <!-- Mobile top bar -->
        <div class="md:hidden flex items-center justify-between bg-gradient-to-r from-slate-900 to-slate-800 text-white px-4 h-14 shadow-md">
            <span class="font-semibold tracking-tight">{{ $adminSetting->site_name }} · ადმინი</span>
            <button @click="sidebarOpen = true" class="p-2 -mr-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
            </button>
        </div>

        <!-- Mobile overlay -->
        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-30 md:hidden"></div>

        <!-- Sidebar -->
        <aside
            class="fixed md:static inset-y-0 left-0 z-40 w-64 bg-gradient-to-b from-slate-900 to-slate-950 text-slate-300 flex flex-col transform transition-transform duration-200 md:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            <div class="h-16 flex items-center gap-2.5 px-5 border-b border-white/10">
                @if ($adminSetting->logo)
                    <img src="{{ asset('storage/' . $adminSetting->logo) }}" class="h-8 w-8 object-contain rounded-lg bg-white/10 p-1">
                @else
                    <span class="h-9 w-9 rounded-lg bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center text-white font-bold text-sm shadow-lg shadow-indigo-950/50">{{ mb_substr($adminSetting->site_name, 0, 1) }}</span>
                @endif
                <div class="leading-tight min-w-0">
                    <div class="text-white font-semibold text-sm truncate">{{ $adminSetting->site_name }}</div>
                    <div class="text-slate-500 text-xs">ადმინ პანელი</div>
                </div>
            </div>

            <nav class="flex-1 px-3 py-5 space-y-1 overflow-y-auto">
                @foreach ($navItems as $item)
                    @php $active = request()->routeIs($item['pattern']); @endphp
                    <a href="{{ route($item['route']) }}"
                       class="relative flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
                              {{ $active ? 'bg-gradient-to-r from-indigo-600 to-indigo-500 text-white shadow-md shadow-indigo-950/40' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        @if ($active)
                            <span class="absolute -left-3 top-1/2 -translate-y-1/2 h-5 w-1 rounded-r-full bg-indigo-300"></span>
                        @endif
                        <span class="w-5 h-5 shrink-0">
                            @switch($item['icon'])
                                @case('pages')
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    @break
                                @case('news')
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7a2 2 0 012-2 2 2 0 012 2v11a2 2 0 01-2 2zM9 8h4m-4 4h6m-6 4h6" /></svg>
                                    @break
                                @case('products')
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                    @break
                                @case('menu')
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h7" /></svg>
                                    @break
                                @case('settings')
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    @break
                            @endswitch
                        </span>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="border-t border-white/10 p-4">
                <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-2 text-xs text-slate-400 hover:text-white mb-4 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                    საიტის ნახვა
                </a>
                <div class="flex items-center gap-3 rounded-xl bg-white/5 p-2.5">
                    <span class="h-9 w-9 rounded-full bg-gradient-to-br from-slate-600 to-slate-700 flex items-center justify-center text-white text-sm font-semibold ring-1 ring-white/10 shrink-0">
                        {{ mb_substr(Auth::user()->name, 0, 1) }}
                    </span>
                    <div class="flex-1 min-w-0">
                        <div class="text-white text-sm font-medium truncate">{{ Auth::user()->name }}</div>
                        <a href="{{ route('profile.edit') }}" class="text-slate-500 hover:text-slate-300 text-xs transition">პროფილი</a>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-slate-500 hover:text-red-400 transition" title="გასვლა">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Content -->
        <div class="flex-1 min-w-0">
            @isset($header)
                <header class="bg-white/80 backdrop-blur border-b border-slate-200 px-4 sm:px-8 py-5 sticky top-0 z-20">
                    {{ $header }}
                </header>
            @endisset

            <main class="px-4 sm:px-8 py-8">
                {{ $slot }}
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
