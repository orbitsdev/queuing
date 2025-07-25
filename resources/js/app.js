/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
     wsPath: import.meta.env.VITE_REVERB_PATH,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws'],
    // enabledTransports: ['ws', 'wss'],
});

// Log when WebSocket connection is established
window.Echo.connector.pusher.connection.bind('connected', () => {
    console.log('WebSocket connection established successfully!');
});

// Log connection errors
window.Echo.connector.pusher.connection.bind('error', (error) => {
    console.error('WebSocket connection error:', error);
});

