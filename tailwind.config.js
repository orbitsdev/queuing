
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
  'primary': '#3B82F6',       // Trustworthy blue
  'primary-hover': '#2563EB', // Slightly darker blue for hover
  'positive': '#22C55E',      // Success green
  'warning': '#F97316',       // Soft orange for mild alerts
  'negative': '#EF4444',      // Red for errors
  'info': '#0EA5E9',          // Light info blue
  'sidebar': '#FFFFFF',       // Sidebar background
  'background': '#F9FAFB',    // Main page background
  'text': '#334155',          // Dark slate text
},

              
      }

      

    },
  },
  plugins: [forms, typography],
}

