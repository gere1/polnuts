import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    // Dynamically built from Row::SLIDER_HEIGHTS / Row::TEXT_HEIGHTS keys (app/Models/Row.php)
    // rather than scanned from a literal class string, so Tailwind must be told about them explicitly.
    safelist: [
        'h-[35vh]', 'min-h-[280px]',
        'h-[60vh]', 'min-h-[360px]',
        'h-[80vh]', 'min-h-[480px]',
        'h-screen',
        'min-h-[35vh]', 'min-h-[60vh]', 'min-h-[80vh]', 'min-h-screen',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms, typography],
};
