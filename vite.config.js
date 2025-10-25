import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import postcss from 'postcss';

export default defineConfig({
    css: {
        postcss: './postcss.config.js',
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/admin.css',
                'resources/css/user-dashboard.css',
                'resources/css/book-cards.css',
                'resources/css/book-carousel.css',
                'resources/css/modern-pages.css',
                'resources/js/app.js'
            ],
            refresh: true,
        }),
    ],
});
