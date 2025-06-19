
import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';
import preset from './vendor/filament/support/tailwind.config.preset'
const colors = require('tailwindcss/colors')

export default {
  presets: [

    preset,

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

    './app/Filament/**/*.php',
    './resources/views/filament/**/*.blade.php',
    './vendor/filament/**/*.blade.php',

  ],
  theme: {
    extend: {
        fontFamily: {
        sans: ['"Inter"', ...defaultTheme.fontFamily.sans],
      },
      colors: {
              green: colors.green,
              indigo: colors.indigo,
              gray: colors.gray,
              secondary: colors.gray,
              positive: colors.emerald,
              negative: colors.red,
              warning: colors.amber,
              info: colors.blue,

           'kiosqueeing': {
  'primary': '#085fc5',       // Trustworthy blue
  'primary-hover': '#0d539b', // Slightly darker blue for hover
  'positive': '#22C55E',      // Success green
  'warning': '#F97316',       // Soft orange for mild alerts
  'negative': '#EF4444',      // Red for errors
  'info': '#1eafff',          // Light info blue
  'sidebar': '#FFFFFF',       // Sidebar background
  'background': '#F9FAFB',    // Main page background
  'text': '#334155',          // Dark slate text
},

'denim': {
    '50': '#edfaff',
    '100': '#d6f2ff',
    '200': '#b5ebff',
    '300': '#83e0ff',
    '400': '#49ccff',
    '500': '#1faeff',
    '600': '#0790ff',
    '700': '#0177f4',
    '800': '#085fc5',
    '900': '#0e529a',
    '950': '#0e325d',
},


      }



    },
  },
  plugins: [forms, typography],
}

