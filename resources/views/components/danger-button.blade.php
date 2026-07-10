<button {{ $attributes->merge(['type' => 'submit', 'class' => 'admin-btn-danger']) }}>
    {{ $slot }}
</button>
