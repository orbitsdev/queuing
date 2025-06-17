/** @type {import('tailwindcss').Config} */
export default {
  presets: [
    require('./vendor/wireui/wireui/tailwind.config.js')
  ],
  content: [
    './vendor/wireui/wireui/src/*.php',
    './vendor/wireui/wireui/ts/**/*.ts',
    './vendor/wireui/wireui/src/WireUi/**/*.php',
    './vendor/wireui/wireui/src/Components/**/*.php',

    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
    './app/Livewire/**/*.php',
  
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}

