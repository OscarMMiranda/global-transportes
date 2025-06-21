if ('caches' in window) {
    caches.open('my-cache').then(cache => {
        console.log("Cache disponible");
    });
} else {
    console.warn("Cache API no está disponible en este entorno.");
}
