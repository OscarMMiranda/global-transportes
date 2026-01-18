<?php
header('Content-Type: text/plain; charset=utf-8');

require_once __DIR__ . '/../../../../includes/conexion.php';

$conn = getConnection();

if (!$conn) {
    echo "CONEXION = NULL\n";
    exit;
}

echo "CONEXION OK\n";
echo "TIPO: " . get_class($conn) . "\n";

$result = $conn->query("SELECT 1");
if (!$result) {
    echo "ERROR QUERY: " . $conn->error . "\n";
} else {
    echo "QUERY OK\n";
}