import "./bootstrap";

import Alpine from "alpinejs";

const DB_NAME = "smart-klontong-offline";
const STORE_NAME = "pending-transactions";
const DB_VERSION = 1;

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

async function addOfflineTransaction(payload) {
    const db = await openOfflineDb();
    return new Promise((resolve, reject) => {
        const transaction = db.transaction(STORE_NAME, "readwrite");
        const store = transaction.objectStore(STORE_NAME);
        const request = store.add({
            payload,
            created_at: new Date().toISOString(),
        });
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

async function sendPendingTransactions() {
    if (!navigator.onLine) {
        return;
    }
    const pending = await getOfflineTransactions();
    if (!pending.length) {
        return;
    }

    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute("content");
    if (!csrfToken) {
        return;
    }

    for (const record of pending) {
        try {
            const response = await fetch("/transaksi", {
                method: "POST",
                credentials: "include",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify(record.payload),
            });

            if (response.ok) {
                await deleteOfflineTransaction(record.id);
            }
        } catch (error) {
            console.warn("Offline sync failed for queued transaction:", error);
            break;
        }
    }
}

window.Alpine = Alpine;

Alpine.start();

if ("serviceWorker" in navigator) {
    window.addEventListener("load", () => {
        navigator.serviceWorker
            .register("/service-worker.js")
            .then((registration) => {
                console.log(
                    "Service worker registered with scope:",
                    registration.scope,
                );
            })
            .catch((error) => {
                console.warn("Service worker registration failed:", error);
            });

        navigator.serviceWorker.addEventListener("message", (event) => {
            if (event.data?.type === "SYNC_PENDING_TRANSACTIONS") {
                sendPendingTransactions();
            }
        });

        window.addEventListener("online", () => {
            sendPendingTransactions();
        });
    });
}

window.sendPendingTransactions = sendPendingTransactions;
window.addOfflineTransaction = addOfflineTransaction;
