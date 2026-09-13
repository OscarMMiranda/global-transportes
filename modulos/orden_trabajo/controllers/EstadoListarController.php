<?php
// archivo: /modulos/orden_trabajo/controllers/EstadoListarController.php

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

/*
    Este controlador devuelve la lista de estados corporativos para:

    - editar_ot.js
    - list.php (filtros)
    - cualquier módulo que necesite mostrar estados

    Formato esperado por cargarCatalogo():
    [
        { "id": 1, "nombre": "Pendiente" },
        { "id": 2, "nombre": "En proceso" },
        ...
    ]
*/

$sql = "
SELECT 
    id,
    nombre
FROM estado_orden_trabajo
ORDER BY nombre ASC
";

$res = $conn->query($sql);

$estados = array();

if ($res && $res->num_rows > 0) {
    while ($fila = $res->fetch_assoc()) {
        $estados[] = $fila;
    }
}

echo json_encode($estados);
