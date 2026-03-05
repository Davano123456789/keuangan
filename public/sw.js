const CACHE_NAME = 'keuanganku-v1';
const urlsToCache = [
    '/',
    '/login',
    '/register',
    '/dashboard-admin/css/vertical-layout-light/style.css',
    '/dashboard-admin/vendors/feather/feather.css',
    '/dashboard-admin/vendors/ti-icons/css/themify-icons.css',
    '/dashboard-admin/vendors/css/vendor.bundle.base.css',
    '/dashboard-admin/vendors/js/vendor.bundle.base.js',
    '/img/pwa/FinanKu.png'
];

// Install Service Worker
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                return cache.addAll(urlsToCache);
            })
    );
});

// Cache and return requests
self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => {
                return response || fetch(event.request);
            })
    );
});

// Update Service Worker
self.addEventListener('activate', event => {
    const cacheWhitelist = [CACHE_NAME];
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (cacheWhitelist.indexOf(cacheName) === -1) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});
