import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    build: {
        outDir: 'dist',
        publicDir: '../public/vite',
        manifest: true,
        rollupOptions: {
          input: {
            main: './vue/src/main.js'
          }
        }
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
