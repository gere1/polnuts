<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">{{ __('Profile') }}</h2>
    </x-slot>

    <div class="max-w-2xl mx-auto space-y-6">
        <div class="p-6 admin-card">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="p-6 admin-card">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="p-6 admin-card">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-admin-layout>
