const CACHE_NAME = "smart-klontong-cache-v1";
const OFFLINE_URL = "/offline.html";
const DB_NAME = "smart-klontong-offline";
const STORE_NAME = "pending-transactions";
const DB_VERSION = 1;
const PRECACHE_URLS = [
    "/",
    OFFLINE_URL,
    "/manifest.webmanifest",
    "/images/logo.jpeg",
    "/build/manifest.json",
];

self.addEventListener("install", (event) => {
    event.waitUntil(
        (async () => {
            const cache = await caches.open(CACHE_NAME);
            await cache.addAll(PRECACHE_URLS);

            try {
                const manifestResponse = await fetch("/build/manifest.json");
                if (manifestResponse.ok) {
                    const manifest = await manifestResponse.json();
                    const manifestAssets = Object.values(manifest)
                        .filter((entry) => entry.file)
                        .map((entry) => `/build/${entry.file}`);
                    await cache.addAll(manifestAssets);
                }
            } catch (error) {
                console.warn(
                    "Failed to cache Vite assets during install:",
                    error,
                );
            }

            await self.skipWaiting();
        })(),
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
    const request = event.request;
    if (request.method !== "GET") {
        return;
    }

    const url = new URL(request.url);
    if (url.origin !== self.location.origin) {
        return;
    }

    event.respondWith(
        (async () => {
            try {
                const response = await fetch(request);
                if (response.ok) {
                    const cache = await caches.open(CACHE_NAME);
                    cache.put(request, response.clone());
                }
                return response;
            } catch (error) {
                const cachedResponse = await caches.match(request);
                if (cachedResponse) {
                    return cachedResponse;
                }

                if (
                    request.mode === "navigate" ||
                    request.headers.get("accept")?.includes("text/html")
                ) {
                    return caches.match(OFFLINE_URL);
                }

                return new Response("Offline", {
                    status: 503,
                    statusText: "Service Unavailable",
                });
            }
        })(),
    );
});

self.addEventListener("sync", (event) => {
    if (event.tag === "sync-transactions") {
        event.waitUntil(syncPendingTransactions());
    }
});

function openOfflineDb() {
    return new Promise((resolve, reject) => {
        const request = indexedDB.open(DB_NAME, DB_VERSION);

        request.onupgradeneeded = () => {
            const db = request.result;
            if (!db.objectStoreNames.contains(STORE_NAME)) {
                db.createObjectStore(STORE_NAME, {
                    keyPath: "id",
                    autoIncrement: true,
                });
            }
        };

        request.onsuccess = () => resolve(request.result);
        request.onerror = () => reject(request.error);
    });
}

async function getOfflineTransactions() {
    const db = await openOfflineDb();
    return new Promise((resolve, reject) => {
        const transaction = db.transaction(STORE_NAME, "readonly");
        const store = transaction.objectStore(STORE_NAME);
        const request = store.getAll();
        request.onsuccess = () => resolve(request.result);
        request.onerror = () => reject(request.error);
    });
}

async function deleteOfflineTransaction(id) {
    const db = await openOfflineDb();
    return new Promise((resolve, reject) => {
        const transaction = db.transaction(STORE_NAME, "readwrite");
        const store = transaction.objectStore(STORE_NAME);
        const request = store.delete(id);
        request.onsuccess = () => resolve();
        request.onerror = () => reject(request.error);
    });
}

async function syncPendingTransactions() {
    const pendingTransactions = await getOfflineTransactions();
    if (!pendingTransactions.length) {
        return;
    }

    for (const record of pendingTransactions) {
        try {
            const response = await fetch("/transaksi", {
                method: "POST",
                credentials: "include",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    "X-CSRF-TOKEN": record.payload?.csrf_token || "",
                },
                body: JSON.stringify(record.payload),
            });

            if (response.ok) {
                await deleteOfflineTransaction(record.id);
            } else {
                console.warn(
                    "Background sync failed for queued transaction",
                    record.id,
                    response.status,
                );
                break;
            }
        } catch (error) {
            console.warn("Background sync error:", error);
            break;
        }
    }
}

async function notifyClients(eventData) {
    const allClients = await self.clients.matchAll({
        includeUncontrolled: true,
    });
    for (const client of allClients) {
        client.postMessage(eventData);
    }
}
