<?php
// public_html/modulos/clientes/controllers/FormController.php

// ─── 0) Mostrar todos los errores (solo en desarrollo) ────────────────
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ─── 1) Cargar configuración global ────────────────────────────────────
$config = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . '/includes/config.php';
if (! file_exists($config)) {
    die("Config no encontrada en: $config");
}
require_once $config;   // define $conn, INCLUDES_PATH, BASE_URL

// ─── 2) Cargar modelos ─────────────────────────────────────────────────
$clienteModel = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\')
     . '/modulos/clientes/models/Cliente.php';
$ubigeoModel  = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\')
     . '/modulos/clientes/models/Ubigeo.php';

if (! file_exists($clienteModel)) {
    die("Modelo Cliente no encontrado en: $clienteModel");
}
require_once $clienteModel;

if (! file_exists($ubigeoModel)) {
    die("Modelo Ubigeo no encontrado en: $ubigeoModel");
}
require_once $ubigeoModel;

// Inicializar conexiones en los modelos
Cliente::init($conn);
Ubigeo::init($conn);

// ─── 3) Definir assets de este módulo ─────────────────────────────────
define('MODULE_CSS', 'clientes.css');
define('MODULE_JS',  'clientes.js');


// ─── 3.1 ) Inicializa variables para la vista ────────────────────────────
$errorMessage = '';
$editing      = false;
$cliente      = null;

// ─── 4) Lógica de petición ──────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoge y sanitiza datos
    $data = [
        'id'              => isset($_POST['id'])              ? (int) $_POST['id'] : null,
        'nombre'          => trim($_POST['nombre']),
        'ruc'             => trim($_POST['ruc']),
        'direccion'       => trim($_POST['direccion']),
        'correo'          => trim($_POST['correo']),
        'telefono'        => trim($_POST['telefono']),
        'departamento_id' => (int) $_POST['departamento_id'],
        'provincia_id'    => (int) $_POST['provincia_id'],
        'distrito_id'     => (int) $_POST['distrito_id'],
    ];

    // Aquí deberías validar los datos (requeridos, formato RUC, email, etc.)
    // y luego llamar a Cliente::save()
    try {
        Cliente::save($data);
        header('Location: index.php?action=list&msg=ok');
        exit;
    } catch (Exception $e) {
        // error_log("FormController save error: " . $e->getMessage());
        // $msg = 'error';
		$errorMessage = $e->getMessage();
        $cliente      = $data;
        $editing      = ! empty($data['id']);
    }

    // Redirigir al listado con mensaje
    // header('Location: ' . BASE_URL . 'modulos/clientes/index.php?action=list&msg=' . $msg);
    // exit;
}

// ─── 5) Preparar datos para mostrar el formulario ────────────────────────
$editing     = false;
$cliente     = null;
$departamentos = Ubigeo::getDepartamentos();
$provincias    = [];
$distritos     = [];

if (isset($_GET['id']) && ctype_digit($_GET['id'])) {
    $editing = true;
    $cliente = Cliente::find((int) $_GET['id']);
    if ($cliente) {
        // Cargar select dinámicos según la entidad
        $provincias = Ubigeo::getProvincias($cliente['departamento_id']);
        $distritos  = Ubigeo::getDistritos($cliente['provincia_id']);
    }
}

// ─── 6) Cargar layout global y la vista ─────────────────────────────────
$header = INCLUDES_PATH . '/header_erp.php';
if (! file_exists($header)) {
    die("Header no encontrado en: $header");
}
require_once $header;

// Vista de formulario (`$editing`, `$cliente`, `$departamentos`, `$provincias`, `$distritos`)
$view = __DIR__ . '/../views/form.php';
if (! file_exists($view)) {
    die("Vista form.php no encontrada en: $view");
}
require_once $view;

$footer = INCLUDES_PATH . '/footer.php';
if (! file_exists($footer)) {
    die("Footer no encontrado en: $footer");
}
require_once $footer;

