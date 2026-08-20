const CACHE_NAME = 'barbersaas-pwa-v2';
const PRECACHE_ASSETS = [
  '/',
  '/manifest.json',
  '/images/icons/icon-192x192.png',
  '/images/icons/icon-512x512.png',
  '/images/logos/Logo-BaberSaaS.webp'
];

// Install Event
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      return cache.addAll(PRECACHE_ASSETS);
    }).then(() => self.skipWaiting())
  );
});

// Activate Event
self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cache) => {
          if (cache !== CACHE_NAME) {
            return caches.delete(cache);
          }
        })
      );
    }).then(() => self.clients.claim())
  );
});

// Fetch Event (Safe Network-First Strategy)
self.addEventListener('fetch', (event) => {
  // Only handle GET requests, ignore POST/PUT/DELETE
  if (event.request.method !== 'GET') return;
  
  // Ignore chrome-extension or external schemes
  if (!event.request.url.startsWith('http')) return;

  event.respondWith(
    fetch(event.request)
      .then((networkResponse) => {
        // Cache successful GET responses dynamically
        if (networkResponse && networkResponse.status === 200 && networkResponse.type === 'basic') {
          const responseToCache = networkResponse.clone();
          caches.open(CACHE_NAME).then((cache) => {
            cache.put(event.request, responseToCache);
          });
        }
        return networkResponse;
      })
      .catch(async () => {
        // If network fails (offline), return cached response or empty fallback response
        const cachedResponse = await caches.match(event.request);
        if (cachedResponse) {
          return cachedResponse;
        }
        // Fallback for html pages
        if (event.request.headers.get('accept')?.includes('text/html')) {
          const fallback = await caches.match('/');
          if (fallback) return fallback;
        }
        return new Response('Offline - No Network Connection', {
          status: 503,
          statusText: 'Service Unavailable',
          headers: new Headers({ 'Content-Type': 'text/plain' })
        });
      })
  );
});
