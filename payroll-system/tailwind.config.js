import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree'],
            },
        colors: {
            transparent: 'transparent',
            current: 'currentColor',
            'white': '#ffffff',
            'purple': '#3f3cbb',
            'midnight': '#121063',
            'metal': '#565584',
            'tahiti': '#3ab7bf',
            'silver': '#bcb8b1',
            'bubble-gum': '#ff77e9',
            'bermuda': '#78dcca',
            'claret': '#91273e',
            'isabelline': '#f4f3ee',
            'indigo-dye': '#08415c',
            'persian-red': '#cc2936',
            'slate-gray': '#6b818c',
            'peach': '#f1bf98',
            'lavander-blush': '#eee5e9',
            'powder-blue': '#8da9c4',
            'mint-cream': '#eef4ed',
            'honeydew': '#dae7da',
            'baby-powder': '#f3f7f3',
            'caput-mortuum': '#562c2c',
            'ash-gray': '#c2d6c2',
            '': '#',
            '': '#',
            '': '#',
            '': '#',
            '': '#',
            '': '#',
            '': '#',
            '': '#',
            '': '#',
            '': '#',
            '': '#',
            '': '#',
            '': '#',
            '': '#',
            '': '#',
            '': '#',
            '': '#',
            '': '#',
            },
        },
    },

    plugins: [forms],
};
