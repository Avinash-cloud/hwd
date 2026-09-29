import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                serif: ['Georgia', 'Cambria', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                brand: {
                    amber: '#D97706',
                    orange: '#F97316',
                    maroon: '#881337',
                    red: '#991B1B',
                    blue: '#0369A1',
                },
                saffron: {
                    DEFAULT: '#D97706',
                    50: '#fffbeb',
                    100: '#fef3c7',
                    200: '#fde68a',
                    300: '#fcd34d',
                    400: '#fbbf24',
                    500: '#D97706',
                    600: '#D97706',
                    700: '#D97706',
                    800: '#881337',
                    900: '#881337',
                },
                ganga: {
                    DEFAULT: '#0369A1',
                    50: '#f0f9ff',
                    100: '#e0f2fe',
                    200: '#bae6fd',
                    300: '#7dd3fc',
                    400: '#38bdf8',
                    500: '#0369A1',
                    600: '#0369A1',
                    700: '#0369A1',
                    800: '#0369A1',
                    900: '#0369A1',
                },
                temple: {
                    DEFAULT: '#991B1B',
                    50: '#fef2f2',
                    100: '#fee2e2',
                    200: '#fecaca',
                    300: '#fca5a5',
                    400: '#f87171',
                    500: '#991B1B',
                    600: '#991B1B',
                    700: '#991B1B',
                    800: '#991B1B',
                    900: '#881337',
                    950: '#881337',
                },
            },
        },
    },

    plugins: [forms],
};
