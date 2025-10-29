import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';
import path from 'path';
import { resolve } from 'path';

export default defineConfig({
    plugins: [react()],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js'), // Map `@` to `resources/js` folder
        },
    },
    optimizeDeps: {
        include: ['react', 'react-dom', '@inertiajs/react', 'axios'],
    },
    server: {
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        origin: 'https://unitalicized-unsadistic-lainey.ngrok-free.dev', // Your Ngrok domain
        cors: true,
        hmr: {
            protocol: 'wss', // Use WebSocket Secure for Ngrok
            host: 'unitalicized-unsadistic-lainey.ngrok-free.dev',

        },
    },
});
