if ('caches' in window) {
    caches.open('my-cache').then(cache => {
        console.log("Cache disponible");
    }).catch(error => {
        console.warn("Error al abrir Cache API:", error);
    });
} else {
    console.warn("Cache API no está disponible en este entorno.");
}
