<?php
// /modulos/clientes/controllers/ListController.php

if (!defined('GT_APP')) {
    define('GT_APP', true);
}

// Conexión viene desde index.php

$estado = isset($_GET['estado']) ? $_GET['estado'] : 'todos';
$q      = isset($_GET['q']) ? trim($_GET['q']) : '';

$sql = "SELECT c.id, c.nombre, c.ruc, c.direccion, c.correo, c.telefono
        FROM clientes c
        WHERE 1 = 1";

// ===============================
//  ESTADOS UNIFICADOS
// ===============================
if ($estado === 'activos') {
    $sql .= " AND c.estado = 'Activo'";
}
elseif ($estado === 'inactivos') {
    $sql .= " AND c.estado = 'Inactivo'";
}
// estado = todos → no filtrar

// ===============================
//  BÚSQUEDA
// ===============================
if ($q !== '') {
    $qEsc = mysqli_real_escape_string($conn, $q);
    $sql .= " AND (c.nombre LIKE '%{$qEsc}%' 
              OR c.ruc LIKE '%{$qEsc}%' 
              OR c.correo LIKE '%{$qEsc}%')";
}

$sql .= " ORDER BY c.id DESC";

$result = mysqli_query($conn, $sql);
$clientes = array();

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $clientes[] = $row;
    }
}

require __DIR__ . '/../views/list.php';
