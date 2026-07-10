@props(['name', 'value' => '', 'height' => '220px', 'id' => null])

@php
    $safeValue = str_contains($value ?? '', '<') ? $value : nl2br(e($value ?? ''));
    $elId = $id ?? $name;
    $fonts = \App\Models\Setting::RICH_TEXT_FONTS;
@endphp

<div id="{{ $elId }}-editor" style="height: {{ $height }}">{!! $safeValue !!}</div>
<input type="hidden" name="{{ $name }}" id="{{ $elId }}-input">

@once
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?{{ \App\Models\Setting::richTextFontsQuery() }}&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
    <style>
        .ql-toolbar.ql-snow { border-radius: 0.5rem 0.5rem 0 0; border-color: #cbd5e1; }
        .ql-container.ql-snow { border-radius: 0 0 0.5rem 0.5rem; border-color: #cbd5e1; font-size: 0.9rem; }
        .ql-editor { background-color: #94a3b8; }
        @foreach (\App\Models\Setting::RICH_TEXT_FONTS as $slug => $font)
            .ql-font-{{ $slug }} { font-family: '{{ $font['label'] }}', sans-serif; }
            .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="{{ $slug }}"]::before,
            .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="{{ $slug }}"]::before {
                content: '{{ $font['label'] }}';
                font-family: '{{ $font['label'] }}', sans-serif;
            }
        @endforeach
        .ql-snow .ql-picker.ql-font { width: 170px; }
    </style>
@endonce

@push('scripts')
<script>
    (function () {
        const SizeStyle = Quill.import('attributors/style/size');
        SizeStyle.whitelist = ['10px', '12px', '14px', '16px', '18px', '20px', '24px', '28px', '32px', '36px', '48px'];
        Quill.register(SizeStyle, true);

        const FontClass = Quill.import('attributors/class/font');
        FontClass.whitelist = {!! json_encode(array_keys($fonts)) !!};
        Quill.register(FontClass, true);

        // Quill's default color/background formats are class-based (ql-color-*), which need a
        // matching stylesheet to render and get lost outside the editor. Register the style-based
        // attributors instead so a chosen color is written as an inline style and actually shows.
        Quill.register(Quill.import('attributors/style/color'), true);
        Quill.register(Quill.import('attributors/style/background'), true);

        const editorEl = document.getElementById('{{ $elId }}-editor');
        const inputEl = document.getElementById('{{ $elId }}-input');
        const quill = new Quill(editorEl, {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{ header: 2 }, { header: 3 }],
                    [{ font: [false, ...FontClass.whitelist] }],
                    [{ size: [false, ...SizeStyle.whitelist] }],
                    [{ color: [] }, { background: [] }],
                    [{ align: [] }],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                    ['link'],
                    ['clean'],
                ],
            },
        });
        inputEl.value = quill.root.innerHTML;
        quill.on('text-change', function () {
            inputEl.value = quill.root.innerHTML;
        });
        editorEl.closest('form').addEventListener('submit', function () {
            inputEl.value = quill.root.innerHTML;
        });
    })();
</script>
@endpush
