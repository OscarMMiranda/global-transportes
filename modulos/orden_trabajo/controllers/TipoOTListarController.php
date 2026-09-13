<?php
// archivo: /modulos/orden_trabajo/controllers/TipoOTListarController.php

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

/*
    Este controlador devuelve la lista de Tipos de OT para:

    - crear_ot.js
    - editar_ot.js
    - catalogos.js

    Formato esperado por cargarCatalogo():
    [
        { "id": 1, "nombre": "Importación" },
        { "id": 2, "nombre": "Exportación" }
    ]
*/

$sql = "
SELECT 
    id,
    nombre
FROM tipo_ot
ORDER BY nombre ASC
";

$res = $conn->query($sql);

$tipos = array();

if ($res && $res->num_rows > 0) {
    while ($fila = $res->fetch_assoc()) {
        $tipos[] = $fila;
    }
}

echo json_encode($tipos);
