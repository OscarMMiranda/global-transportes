<?php
// archivo : /modulos/mantenimiento/entidades/controllers/ListController.php

// 1) Activar modo defensivo
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../error_log.txt');

// 2) Cargar modelos
require_once __DIR__ . '/../models/EntidadModel.php';
require_once __DIR__ . '/../models/TerritorioModel.php';
require_once __DIR__ . '/../models/TipoLugarModel.php';

// 3) Validar conexión
if (!isset($conn) || !$conn) {
    die("❌ Error: conexión no disponible.");
}

// 4) Obtener entidades separadas por estado
$entidades = obtenerEntidadesSeparadas($conn);

// 5) Obtener departamentos para combo jerárquico
$departamentos = getDepartamentos($conn);

// 6) Obtener tipos de lugar activos
$tipos = obtenerTiposActivos($conn);


$depId     = 15;
$provId    = 127;
$distId    = 1251;
$provincias = getProvincias($conn, $depId);
$distritos  = getDistritos($conn, $provId);

// 7) Mostrar vista principal
include __DIR__ . '/../views/ListView.php';