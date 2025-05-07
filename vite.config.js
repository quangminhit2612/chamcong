import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        host: '0.0.0.0',   // <-- thêm dòng này
        port: 5173,        // <-- đúng port Vite dev server
        hmr: {
            host: '192.168.0.68',  // <-- địa chỉ IP LAN của máy bạn
        },
    },
});
