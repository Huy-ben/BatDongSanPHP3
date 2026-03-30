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
                    DEFAULT: '#E8392A',
                    light: '#FF5244',
                    dark: '#B02A1E',
                },
                accent: '#F97316',
                navy: '#0F2A4A',
            },
        },
    },
};
