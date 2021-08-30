const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    purge: {
        //layers: ['components', 'base'],
        content: [
            './vendor/laravel/jetstream/**/*.blade.php',
            './storage/framework/views/*.php',
            './resources/views/**/*.blade.php',
            './vendor/asantibanez/livewire-select/src/LivewireSelect.php'
        ],
        options: {
            safelist: [/^bg-\w*-\d{3}$/],
        }
    },
    
    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    variants: {
        extend: {
            opacity: ['disabled'],
        },
    },

    plugins: [ 
        require('@tailwindcss/forms'), 
        require('@tailwindcss/typography'),
        require('daisyui')
    ],

};