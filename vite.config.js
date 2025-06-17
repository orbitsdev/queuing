import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js'],
      refresh: [
        'resources/**/*.blade.php',
        'resources/**/*.js',
        'resources/**/*.vue',
        'app/Livewire/**/*.php',
        'app/Http/Controllers/**/*.php',
        'app/View/Components/**/*.php'
      ]
    }),
    tailwindcss(),
  ],
});
