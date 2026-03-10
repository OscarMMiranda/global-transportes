<?php
// archivo:  /modulos/clientes/controllers/ApiController.php

if (!defined('GT_APP')) {
    define('GT_APP', true);
}

header('Content-Type: application/json; charset=utf-8');

$type = isset($_GET['type']) ? $_GET['type'] : '';

/* ============================================================
   1) LISTA DE CLIENTES (DataTables)
   ============================================================ */
if ($type === 'list') {

    $estado = isset($_GET['estado']) ? $_GET['estado'] : 'activos';

    if ($estado === 'activos') {
        $sql = "SELECT id, nombre, ruc, direccion, correo, telefono
                FROM clientes
                WHERE estado = 'Activo'
                ORDER BY id DESC";
    }
    elseif ($estado === 'inactivos') {
        $sql = "SELECT id, nombre, ruc, direccion, correo, telefono
                FROM clientes
                WHERE estado = 'Inactivo'
                ORDER BY id DESC";
    }
    else {
        $sql = "SELECT id, nombre, ruc, direccion, correo, telefono
                FROM clientes
                ORDER BY id DESC";
    }

    $res = mysqli_query($conn, $sql);
    $data = array();

    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $data[] = $row;
        }
    }

    echo json_encode($data);
    exit;
}

/* ============================================================
   2) PROVINCIAS POR DEPARTAMENTO
   ============================================================ */
if ($type === 'provincias') {

    $depId = isset($_GET['departamento_id']) ? (int) $_GET['departamento_id'] : 0;

    $sql = "SELECT id, nombre 
            FROM provincias 
            WHERE departamento_id = " . $depId . "
            ORDER BY nombre ASC";

    $res = mysqli_query($conn, $sql);
    $data = array();

    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $data[] = $row;
        }
    }

    echo json_encode($data);
    exit;
}

/* ============================================================
   3) DISTRITOS POR PROVINCIA
   ============================================================ */
if ($type === 'distritos') {

    $provId = isset($_GET['provincia_id']) ? (int) $_GET['provincia_id'] : 0;

    $sql = "SELECT id, nombre 
            FROM distritos 
            WHERE provincia_id = " . $provId . "
            ORDER BY nombre ASC";

    $res = mysqli_query($conn, $sql);
    $data = array();

    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $data[] = $row;
        }
    }

    echo json_encode($data);
    exit;
}

/* ============================================================
   4) VISTA DEL CLIENTE (MODAL)
   ============================================================ */
if ($type === 'view') {

    if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'ID inválido'));
        exit;
    }

    $id = (int) $_GET['id'];

    $sql = "SELECT c.id, c.nombre, c.ruc, c.direccion, c.correo, c.telefono,
                   d.nombre AS departamento,
                   p.nombre AS provincia,
                   dis.nombre AS distrito
            FROM clientes c
            LEFT JOIN departamentos d ON c.departamento_id = d.id
            LEFT JOIN provincias p ON c.provincia_id = p.id
            LEFT JOIN distritos dis ON c.distrito_id = dis.id
            WHERE c.id = " . $id . " LIMIT 1";

    $res = mysqli_query($conn, $sql);

    if (!$res || mysqli_num_rows($res) === 0) {
        http_response_code(404);
        echo json_encode(array('error' => 'Cliente no encontrado'));
        exit;
    }

    $cliente = mysqli_fetch_assoc($res);

    header('Content-Type: text/html; charset=utf-8');
    require __DIR__ . '/../views/modal.php';
    exit;
}

/* ============================================================
   5) DEFAULT
   ============================================================ */
http_response_code(400);
echo json_encode(array('error' => 'Operación no soportada'));
exit;
