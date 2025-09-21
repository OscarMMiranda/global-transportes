

www.globaltransportes.com/
├── index.php                  ← Punto de entrada principal
├── bootstrap_sitio.php        ← Arranque común para todas las páginas públicas
├── includes/
│   ├── config.php             ← Constantes, rutas, conexión
│   ├── funciones.php          ← Helpers globales (sanitize, IP, historial)
│   └── conexion.php           ← getConnection() y parámetros DB
├── partials/
│   ├── head.php               ← <head> con CSS, meta y JS
│   ├── header.php             ← Encabezado visual del sitio
│   ├── sidebar.php            ← Menú lateral (si aplica)
│   ├── hero.php               ← Banner principal
│   ├── ventajas.php           ← Sección de beneficios
│   └── footer.php             ← Pie de página
├── modulos/
│   └── vehiculos/             ← Módulo independiente (ya lo tienes)
├── assets/
│   ├── css/
│   ├── js/
│   └── img/
└── error_log.txt              ← Log de errores y trazas