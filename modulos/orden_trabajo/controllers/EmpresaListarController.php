<?php
// archivo: /modulos/orden_trabajo/controllers/EmpresaListarController.php

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

/*
    Este controlador devuelve la lista de empresas para:

    - crear_ot.js
    - editar_ot.js
    - catalogos.js

    Formato esperado por cargarCatalogo():
    [
        { "id": 1, "nombre": "Empresa X" },
        { "id": 2, "nombre": "Empresa Y" }
    ]
*/

$sql = "
SELECT 
    id,
    nombre_comercial AS nombre
FROM empresa
ORDER BY nombre_comercial ASC
";

$res = $conn->query($sql);

$empresas = array();

if ($res && $res->num_rows > 0) {
    while ($fila = $res->fetch_assoc()) {
        $empresas[] = $fila;
    }
}

echo json_encode($empresas);
