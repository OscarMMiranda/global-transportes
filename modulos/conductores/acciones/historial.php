<?php
// archivo: modulos/conductores/acciones/historial.php

header('Content-Type: application/json; charset=utf-8');
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

$sql = "
    SELECT *
    FROM conductores_historial
    WHERE id_registro = $id
      AND modulo = 'conductores'
    ORDER BY fecha_cambio DESC
";

$res = $conn->query($sql);

$html = "<ul class='list-group'>";

if (!$res || $res->num_rows === 0) {

    $html .= "<li class='list-group-item text-muted'>Sin historial registrado.</li>";

} else {

    while ($row = $res->fetch_assoc()) {

        $accion = htmlspecialchars($row['accion']);
        $usuario = htmlspecialchars($row['usuario'] ?: '—');
        $fecha = htmlspecialchars($row['fecha_cambio']);

        $html .= "
            <li class='list-group-item'>
                <strong>Acción:</strong> $accion<br>
                <strong>Usuario:</strong> $usuario<br>
                <strong>Fecha:</strong> $fecha<br>
        ";

        $raw = $row['cambios_json'];

        if (!empty($raw) && is_string($raw)) {

            $json = @json_decode($raw, true);

            if (is_array($json)) {

                $html .= "<ul style='margin-top:8px'>";

                foreach ($json as $campo => $detalle) {

                    // detalle = ["valor_anterior", "valor_nuevo"]
                    $antes = isset($detalle[0]) ? htmlspecialchars($detalle[0]) : '';
                    $despues = isset($detalle[1]) ? htmlspecialchars($detalle[1]) : '';

                    $html .= "<li><strong>$campo:</strong> $antes → $despues</li>";
                }

                $html .= "</ul>";

            } else {
                $html .= "<div class='text-muted'>Sin cambios registrados.</div>";
            }

        } else {
            $html .= "<div class='text-muted'>Sin cambios registrados.</div>";
        }

        $html .= "</li>";
    }
}

$html .= "</ul>";

echo json_encode([
    "success" => true,
    "html" => $html
]);
