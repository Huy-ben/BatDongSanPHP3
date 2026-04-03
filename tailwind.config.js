/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.ts',
        './resources/**/*.vue',
        './app/**/*.php',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Be Vietnam Pro', 'sans-serif'],
            },
            colors: {
                brand: {
                    50: '#fff7ed',
                    100: '#ffedd5',
                    500: '#f47920',
                    600: '#e8651a',
                    700: '#c84f0f',
                    DEFAULT: '#f47920',
                    light: '#ffedd5',
                    dark: '#c84f0f',
                },
                accent: '#F97316',
                navy: '#0F2A4A',
            },
            boxShadow: {
                glow: '0 14px 45px rgba(244, 121, 32, 0.22)',
            },
        },
    },
};
