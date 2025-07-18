<?php
header('Content-Type: application/json');
include '../../conexion.php'; // ajusta ruta si es necesario

// Obtener datos
$sql = "SELECT 
          a.id,
          c.nombre AS conductor,
          t.placa AS tracto,
          ca.placa AS carreta,
          DATE_FORMAT(a.fecha_inicio, '%Y-%m-%d') AS inicio,
          IFNULL(DATE_FORMAT(a.fecha_fin, '%Y-%m-%d'), '—') AS fin,
          a.estado
        FROM asignaciones a
        INNER JOIN conductores c       ON c.id = a.id_conductor
        INNER JOIN vehiculos t         ON t.id = a.id_vehiculo_tracto
        INNER JOIN vehiculos ca        ON ca.id = a.id_vehiculo_carreta
        ORDER BY a.fecha_inicio DESC";

$rows = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

// Envolver para DataTables
echo json_encode(["data" => $rows]);
