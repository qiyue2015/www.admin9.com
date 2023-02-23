/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        // './storage/framework/views/*.php',
        './resources/views/*.blade.php',
        './resources/views/**/*.blade.php',
        './resources/**/*.js',
    ],
    darkMode: 'media',
    theme: {
        screens: {
            sm: '640px',
            md: '768px',
            lg: '1024px',
        },
        fontFamily: {
            sans: ['Microsoft Yahei', 'Avenir', 'Segoe UI', 'Hiragino Sans GB', 'STHeiti', 'Microsoft Sans Serif', 'WenQuanYi Micro Hei', 'sans-serif']
        },
        container: {
            center: true
        },
        extend: {},
    },
    plugins: [
        require("daisyui")
    ],
    daisyui: {
        themes: ["winter", "night"],
    },
}
