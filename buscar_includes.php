<?php
    // archivo: /buscar_includes.php

echo "<pre>";

echo "Buscando carpeta 'includes'...\n\n";

function buscar($dir) {
    $items = scandir($dir);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;

        $ruta = $dir . '/' . $item;

        if (is_dir($ruta)) {
            echo $ruta . "\n";

            if ($item === 'includes') {
                echo "\n=== ENCONTRADO ===\n";
                echo "Ruta completa: $ruta\n";
                exit;
            }

            buscar($ruta);
        }
    }
}

buscar($_SERVER['DOCUMENT_ROOT']);

echo "\nNo se encontró carpeta 'includes'.\n";
