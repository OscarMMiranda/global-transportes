<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

echo "Conexión OK<br>";

$sql = "SELECT 1";
$res = $conn->query($sql);

echo "SQL OK<br>";
