import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    darkMode: 'class',
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Primary colors using CSS variables
                primary: {
                    50: 'var(--color-primary-50)',
                    100: 'var(--color-primary-100)',
                    200: 'var(--color-primary-200)',
                    300: 'var(--color-primary-300)',
                    400: 'var(--color-primary-400)',
                    500: 'var(--color-primary-500)',
                    600: 'var(--color-primary-600)',
                    700: 'var(--color-primary-700)',
                    800: 'var(--color-primary-800)',
                    900: 'var(--color-primary-900)',
                    950: 'var(--color-primary-950)',
                    DEFAULT: 'var(--color-primary)',
                    light: 'var(--color-primary-400)',
                    lighter: 'var(--color-primary-300)',
                    dark: 'var(--color-primary-700)',
                    darker: 'var(--color-primary-800)',
                },
                // Background colors
                darker: 'var(--color-bg-primary)',
                dark: 'var(--color-bg-secondary)',
                light: 'var(--color-text-primary)',
                // Text colors
                'text-primary': 'var(--color-text-primary)',
                'text-secondary': 'var(--color-text-secondary)',
                'text-tertiary': 'var(--color-text-tertiary)',
                'text-quaternary': 'var(--color-text-quaternary)',
                // Status colors
                success: {
                    DEFAULT: 'var(--color-success)',
                    bg: 'var(--color-success-bg)',
                },
                warning: {
                    DEFAULT: 'var(--color-warning)',
                    bg: 'var(--color-warning-bg)',
                },
                error: {
                    DEFAULT: 'var(--color-error)',
                    bg: 'var(--color-error-bg)',
                },
                info: {
                    DEFAULT: 'var(--color-info)',
                    bg: 'var(--color-info-bg)',
                },
            },
        },
    },
    plugins: [],
};
