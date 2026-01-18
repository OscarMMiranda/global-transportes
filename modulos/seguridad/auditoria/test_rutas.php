<?php
echo "<pre>";

echo "Estoy en: " . __FILE__ . "\n\n";

echo "Probando rutas...\n";

$paths = [
    '../../../includes/head.php',
    '../../../../includes/head.php',
    '../../includes/head.php',
    '../includes/head.php',
    '/includes/head.php'
];

foreach ($paths as $p) {
    echo $p . " => ";
    echo file_exists($p) ? "ENCONTRADO\n" : "NO existe\n";
}

echo "</pre>";