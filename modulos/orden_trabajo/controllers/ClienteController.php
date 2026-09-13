<?php
// archivo: /modulos/orden_trabajo/controllers/ClienteController.php

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

/*
    Este controlador sirve para cargar clientes en:

    - crear_ot.js
    - catalogos.js

    Formato esperado por cargarCatalogo():
    [
        { "id": 1, "nombre": "Cliente X" },
        { "id": 2, "nombre": "Cliente Y" }
    ]
*/

$sql = "
SELECT 
    id,
    nombre
FROM clientes
WHERE estado = 'Activo'
AND deleted_at IS NULL
ORDER BY nombre ASC
";

$res = $conn->query($sql);

$clientes = array();

if ($res && $res->num_rows > 0) {
    while ($fila = $res->fetch_assoc()) {
        $clientes[] = $fila;
    }
}

// Respuesta JSON
echo json_encode($clientes);
