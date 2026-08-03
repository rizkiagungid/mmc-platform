const CACHE_VERSION = 'v1.0.0';
const CACHE_NAME = `mmc-pwa-${CACHE_VERSION}`;
const OFFLINE_URL = '/offline.html';

const PRECACHE_ASSETS = [
    OFFLINE_URL,
    '/manifest.json',
    '/assets/css/style.css',
    '/assets/logo-mm-2023.png',
    '/assets/icons/icon-192.png',
    '/assets/icons/icon-256.png',
    '/assets/icons/icon-512.png',
    '/assets/icons/maskable-icon.png',
    '/assets/icons/apple-touch-icon.png',
    '/assets/icons/favicon.png'
];

// Routes that MUST NOT be cached (Security & Auth Guardrail)
const SENSITIVE_ROUTES = [
    '/login',
    '/register',
    '/auth',
    '/logout',
    '/admin/users',
    '/admin/audit-logs'
];

// Install Event: Pre-cache static core assets & offline page
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(PRECACHE_ASSETS);
        }).then(() => self.skipWaiting())
    );
});

// Activate Event: Automatic cleanup of outdated cache versions
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cache) => {
                    if (cache !== CACHE_NAME && cache.startsWith('mmc-pwa-')) {
                        console.log('[SW] Deleting old cache version:', cache);
                        return caches.delete(cache);
                    }
                })
            );
        }).then(() => self.clients.claim())
    );
});

// Fetch Event: Smart Cache & Security Filtering Strategy
self.addEventListener('fetch', (event) => {
    const request = event.request;
    const url = new URL(request.url);

    // 1. Security Check: Ignore non-GET requests, cross-origin, or sensitive auth/login routes
    if (request.method !== 'GET' || !url.origin.includes(self.location.origin)) {
        return;
    }

    const isSensitive = SENSITIVE_ROUTES.some(route => url.pathname.includes(route));
    if (isSensitive) {
        // Never cache sensitive endpoints; fetch directly from network
        return;
    }

    // 2. Static Assets (CSS, JS, Fonts, Images, Vendor) -> Cache First Strategy
    if (
        request.destination === 'style' ||
        request.destination === 'script' ||
        request.destination === 'image' ||
        request.destination === 'font' ||
        url.pathname.startsWith('/assets/')
    ) {
        event.respondWith(
            caches.match(request).then((cachedResponse) => {
                if (cachedResponse) {
                    // Serve cached asset and refresh cache in background
                    fetch(request).then((networkResponse) => {
                        if (networkResponse && networkResponse.status === 200) {
                            caches.open(CACHE_NAME).then((cache) => cache.put(request, networkResponse));
                        }
                    }).catch(() => {/* Ignore background sync failure */});
                    return cachedResponse;
                }
                return fetch(request).then((networkResponse) => {
                    if (networkResponse && networkResponse.status === 200) {
                        const responseToCache = networkResponse.clone();
                        caches.open(CACHE_NAME).then((cache) => cache.put(request, responseToCache));
                    }
                    return networkResponse;
                });
            })
        );
        return;
    }

    // 3. HTML Navigation Pages -> Network First Strategy with Offline Fallback
    if (request.mode === 'navigate' || (request.headers.get('accept') && request.headers.get('accept').includes('text/html'))) {
        event.respondWith(
            fetch(request).then((networkResponse) => {
                if (networkResponse && networkResponse.status === 200) {
                    const responseToCache = networkResponse.clone();
                    caches.open(CACHE_NAME).then((cache) => cache.put(request, responseToCache));
                }
                return networkResponse;
            }).catch(() => {
                return caches.match(request).then((cachedResponse) => {
                    if (cachedResponse) {
                        return cachedResponse;
                    }
                    return caches.match(OFFLINE_URL);
                });
            })
        );
        return;
    }
});

// Skip Waiting Message Handler (For One-Click App Updates)
self.addEventListener('message', (event) => {
    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }
});

// Background Sync Listener (Future Ready for Offline Attendance & Task Queue)
self.addEventListener('sync', (event) => {
    console.log('[SW] Background sync event:', event.tag);
    if (event.tag === 'sync-offline-attendance') {
        event.waitUntil(Promise.resolve());
    } else if (event.tag === 'sync-offline-tasks') {
        event.waitUntil(Promise.resolve());
    }
});

// Web Push Notification Listener (Future Ready for Firebase / Web Push API)
self.addEventListener('push', (event) => {
    let data = { title: 'Multimedia Club SMAN 1 Tamansari', body: 'Ada pemberitahuan baru di platform MMC!' };
    if (event.data) {
        try { data = event.data.json(); } catch(e) { data.body = event.data.text(); }
    }
    const options = {
        body: data.body,
        icon: '/assets/icons/icon-192.png',
        badge: '/assets/icons/favicon.png',
        vibrate: [100, 50, 100],
        data: { url: data.url || '/dashboard' }
    };
    event.waitUntil(self.registration.showNotification(data.title, options));
});

// Push Notification Click Event Handler
self.addEventListener('notificationclick', (event) => {
    event.notification.close();
    const targetUrl = event.notification.data?.url || '/dashboard';
    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then((clientList) => {
            for (const client of clientList) {
                if (client.url === targetUrl && 'focus' in client) return client.focus();
            }
            if (clients.openWindow) return clients.openWindow(targetUrl);
        })
    );
});
