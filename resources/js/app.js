import "./bootstrap";

import Alpine from "alpinejs";

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
    });
}
