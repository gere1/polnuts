@php $guestSetting = \App\Models\Setting::current(); @endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center px-4 py-10 bg-gradient-to-br from-slate-900 via-slate-900 to-slate-950 relative overflow-hidden">
            <div class="pointer-events-none absolute inset-0 opacity-40" style="background-image: radial-gradient(circle at 20% 20%, rgba(99,102,241,0.25), transparent 40%), radial-gradient(circle at 80% 70%, rgba(129,140,248,0.18), transparent 45%);"></div>

            <div class="relative flex flex-col items-center">
                <a href="/" class="flex items-center gap-2.5 text-white mb-8">
                    @if ($guestSetting->logo)
                        <img src="{{ asset('storage/' . $guestSetting->logo) }}" class="h-11 w-11 rounded-xl object-contain bg-white/10 p-1.5 shadow-lg shadow-indigo-950/50">
                    @else
                        <span class="h-11 w-11 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-950/50">
                            <x-application-logo class="w-6 h-6 fill-current text-white" />
                        </span>
                    @endif
                    <span class="font-semibold text-xl tracking-tight">{{ $guestSetting->site_name }}</span>
                </a>

                <div class="w-full sm:max-w-md px-7 py-8 bg-white rounded-2xl shadow-2xl shadow-black/30 ring-1 ring-white/10">
                    {{ $slot }}
                </div>

                <p class="mt-6 text-sm text-slate-500">&copy; {{ date('Y') }} {{ $guestSetting->site_name }}</p>
            </div>
        </div>
    </body>
</html>
