<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$id = 12;
$q = $conn->query("SELECT id FROM asignaciones_conductor LIMIT 1");

echo "SQL OK";

