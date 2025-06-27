<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../../../includes/conexion.php';
header('Content-Type: text/html; charset=UTF-8');

// require_once __DIR__ . '/../../../includes/conexion.php';
// header('Content-Type: text/html; charset=UTF-8');

file_put_contents(__DIR__.'/log_dist.txt', print_r($_POST,1)."\n", FILE_APPEND);

$prov = isset($_POST['provincia_id']) ? (int)$_POST['provincia_id'] : 0;
echo '<option value="">— Selecciona —</option>';
if ($prov) {
    $stmt = $conn->prepare(
      "SELECT id,nombre FROM distritos WHERE provincia_id=? ORDER BY nombre"
    );
    $stmt->bind_param("i",$prov);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($r = $res->fetch_assoc()) {
        printf(
          '<option value="%d">%s</option>',
          $r['id'], htmlspecialchars($r['nombre'])
        );
    }
    $stmt->close();
}
