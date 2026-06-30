import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            // Custom animations
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                slideUp: {
                    '0%': { transform: 'translateY(20px)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                },
                pulseGlow: {
                    '0%, 100%': { boxShadow: '0 0 0 0 rgba(12, 74, 237, 0.5)' },
                    '50%': { boxShadow: '0 0 0 10px rgba(12, 74, 237, 0.5)' },
                },
            },
            animation: {
                fadeIn: 'fadeIn 0.6s ease-out forwards',
                slideUp: 'slideUp 0.6s ease-out forwards',
                pulseGlow: 'pulseGlow 2s infinite',
            },
            // Glassmorphism utility (background with blur)
            backgroundImage: theme => ({
                'glass': 'rgba(255,255,255,0.3)',
            }),
        },
    },

    plugins: [forms],
};
