<button {{ $attributes->merge(['type' => 'button', 'class' => 'admin-btn-secondary']) }}>
    {{ $slot }}
</button>
