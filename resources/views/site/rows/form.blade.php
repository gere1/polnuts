@php
    $bare = $bare ?? false;
    $captchaA = random_int(1, 9);
    $captchaB = random_int(1, 9);
@endphp
<section class="{{ $bare ? '' : 'px-4' }}" style="{{ $bare ? '' : $row->styleAttr() }}">
    <div class="{{ $bare ? '' : 'max-w-xl mx-auto' }}">
        <x-animated-heading :row="$row" class="font-display italic text-3xl md:text-4xl text-center mb-2" />
        @if ($row->subtitle)
            <p class="text-center opacity-70 mb-8">{{ $row->subtitle }}</p>
        @endif

        @if (session('status'))
            <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg text-sm text-center">{{ session('status') }}</div>
        @endif

        <form action="{{ localizedRoute('contact.submit') }}" method="POST" class="space-y-4">
            @csrf

            <div style="position:absolute; left:-9999px; top:-9999px;" aria-hidden="true">
                <input type="text" name="website" tabindex="-1" autocomplete="off">
            </div>
            <input type="hidden" name="form_rendered_at" value="{{ encrypt(time()) }}">
            <input type="hidden" name="captcha_expected" value="{{ encrypt($captchaA + $captchaB) }}">

            <div>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="{{ __('Name') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="{{ __('Email') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <textarea name="message" rows="5" placeholder="{{ __('Message') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('message') }}</textarea>
                @error('message') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <input type="number" name="captcha_answer" value="{{ old('captcha_answer') }}" placeholder="{{ $captchaA }} + {{ $captchaB }} = ?" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('captcha_answer') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <button class="w-full px-6 py-3 bg-brand text-white rounded-lg font-medium hover:opacity-90 transition">{{ __('Send') }}</button>
        </form>
    </div>
</section>
