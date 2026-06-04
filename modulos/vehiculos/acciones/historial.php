<?php
	//	archivo: modulos/vehiculos/acciones/historial.php

header('Content-Type: application/json; charset=utf-8');
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

$sql = "
    SELECT *
    FROM vehiculo_historial
    WHERE vehiculo_id = $id
    ORDER BY fecha DESC
";

$res = $conn->query($sql);

$html = "<ul class='list-group'>";

if (!$res || $res->num_rows === 0) {

    $html .= "<li class='list-group-item text-muted'>Sin historial registrado.</li>";

} else {

    while ($row = $res->fetch_assoc()) {

        $estadoAnterior = htmlspecialchars($row['estado_anterior']);
        $estadoNuevo = htmlspecialchars($row['estado_nuevo']);
        $motivo = htmlspecialchars($row['motivo'] ?: '—');
        $usuario = htmlspecialchars($row['usuario_id']);
        $fecha = htmlspecialchars($row['fecha']);
        $ip = htmlspecialchars($row['ip_origen'] ?: '—');
        $ua = htmlspecialchars($row['user_agent'] ?: '—');

        $html .= "
            <li class='list-group-item'>
                <strong>Estado:</strong> $estadoAnterior → $estadoNuevo<br>
                <strong>Motivo:</strong> $motivo<br>
                <strong>Usuario:</strong> $usuario<br>
                <strong>IP:</strong> $ip<br>
                <strong>Fecha:</strong> $fecha<br>
                <small class='text-muted'>User-Agent: $ua</small>
            </li>
        ";
    }
}

$html .= "</ul>";

echo json_encode([
    "success" => true,
    "html" => $html
]);
