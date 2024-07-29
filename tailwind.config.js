/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./node_modules/flowbite/**/*.js",
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php'
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Inter var', 'sans'],
      },
    },
  },
  plugins: [
      require('@tailwindcss/forms'),
      require('flowbite/plugin'),
      require('@tailwindcss/typography'),
  ],
}

