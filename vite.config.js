import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            // Three entries: app.css and app.js for the public front-end,
            // admin.js for the admin's WYSIWYG editor (loaded only on admin pages
            // via @vite(['resources/js/admin.js']) so the public bundle stays light).
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/admin.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
