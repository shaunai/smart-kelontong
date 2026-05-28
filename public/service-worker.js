const CACHE_NAME = "smart-klontong-cache-v1";
const OFFLINE_URL = "/offline.html";
const ASSETS_TO_CACHE = [
    "/",
    OFFLINE_URL,
    "/manifest.webmanifest",
    "/images/logo.jpeg",
];

self.addEventListener("install", (event) => {
    event.waitUntil(
        caches
            .open(CACHE_NAME)
            .then((cache) => cache.addAll(ASSETS_TO_CACHE))
            .then(() => self.skipWaiting()),
    );
});

self.addEventListener("activate", (event) => {
    event.waitUntil(
        caches
            .keys()
            .then((keys) =>
                Promise.all(
                    keys
                        .filter((key) => key !== CACHE_NAME)
                        .map((key) => caches.delete(key)),
                ),
            )
            .then(() => self.clients.claim()),
    );
});

self.addEventListener("fetch", (event) => {
    if (event.request.method !== "GET") {
        return;
    }

    const url = new URL(event.request.url);
    if (url.origin !== self.location.origin) {
        return;
    }

    event.respondWith(
        fetch(event.request)
            .then((response) => {
                if (response.ok) {
                    const responseClone = response.clone();
                    caches
                        .open(CACHE_NAME)
                        .then((cache) =>
                            cache.put(event.request, responseClone),
                        );
                }
                return response;
            })
            .catch(() =>
                caches
                    .match(event.request)
                    .then(
                        (cachedResponse) =>
                            cachedResponse || caches.match(OFFLINE_URL),
                    ),
            ),
    );
});

self.addEventListener("sync", (event) => {
    if (event.tag === "sync-transactions") {
        event.waitUntil(syncPendingTransactions());
    }
});

async function syncPendingTransactions() {
    // Placeholder: implement pending transaction synchronization from IndexedDB
    // Jika toko masih offline, data transaksi dapat disimpan sementara di IndexedDB,
    // lalu dikirim kembali saat koneksi kembali.
    return Promise.resolve();
}
