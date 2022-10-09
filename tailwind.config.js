/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
  ],
  theme: {
    fontFamily: {
      sans: ['PingFangSC-Regular', 'Helvetica Neue', 'Microsoft Yahei', '微软雅黑']
    },
    screens: {
      sm: '640px',
      md: '768px',
      lg: '1024px',
      xl: '1280px',
    },
    container: {
      center: true,
    },
    extend: {},
  },
  plugins: [],
}
