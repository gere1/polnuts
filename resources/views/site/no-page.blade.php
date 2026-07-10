<x-site-layout>
    <div class="max-w-xl mx-auto py-24 text-center px-4">
        <h1 class="font-display italic text-3xl mb-4">{{ __('გვერდი ჯერ არ არის შექმნილი') }}</h1>
        <p class="text-gray-600">
            {{ __('შედით') }}
            <a href="{{ route('login') }}" class="text-brand hover:underline">{{ __('ადმინ პანელში') }}</a>
            {{ __('და შექმენით მთავარი გვერდი.') }}
        </p>
    </div>
</x-site-layout>
