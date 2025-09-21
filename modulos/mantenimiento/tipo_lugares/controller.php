<?php
// archivo : /modulos/mantenimiento/tipo_lugares/controller.php

date_default_timezone_set('America/Lima');

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

require_once 'model.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$conn = getConnection();
if (!$conn) {
    echo "<script>alert('Error de conexión a la base de datos.'); window.location='index.php';</script>";
    exit;
}

// 🔍 Eliminar tipo si se recibe por GET
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);

    if (tipoLugarEnUso($conn, $id)) {
        echo "<script>alert('No se puede eliminar: el tipo está en uso por entidades.'); window.location='index.php';</script>";
        exit;
    }

    eliminarTipo($conn, $id);
    header('Location: index.php');
    exit;
}

// 🔄 Reactivar tipo si se recibe por GET
if (isset($_GET['reactivar'])) {
    $id = intval($_GET['reactivar']);
    reactivarTipo($conn, $id);
    header('Location: index.php');
    exit;
}

// 📝 Procesar formulario POST (insertar o editar)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id     = isset($_POST['id']) ? intval($_POST['id']) : null;
    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';

    if ($nombre === '') {
        echo "<script>alert('El nombre no puede estar vacío.'); window.location='index.php';</script>";
        exit;
    }

    if (existeTipoLugar($conn, $nombre, $id)) {
        echo "<script>alert('Ya existe un tipo con ese nombre.'); window.location='index.php';</script>";
        exit;
    }

    if ($id) {
        editarTipo($conn, $id, $nombre);
    } else {
        if (!insertarTipo($conn, $nombre)) {
            echo "<script>alert('Error al insertar el tipo.'); window.location='index.php';</script>";
            exit;
        }
    }

    header('Location: index.php');
    exit;
}

// 📋 Función para index.php
function listarTipos($conn) {
    return obtenerTipos($conn);
}

// 📂 Separar activos e inactivos con trazabilidad defensiva
function listarTiposSeparados($conn) {
    $todos = obtenerTipos($conn);
    $activos = [];
    $inactivos = [];

    foreach ($todos as $t) {
        $estado = isset($t['estado']) ? trim(strtolower($t['estado'])) : '';
        error_log("🧪 Tipo: ID={$t['id']}, estado={$estado}");

        if ($estado === 'activo') {
            $activos[] = $t;
        } elseif ($estado === 'inactivo') {
            $inactivos[] = $t;
        }
    }

    return ['activos' => $activos, 'inactivos' => $inactivos];
}