<?php
// archivo: /modulos/clientes/controllers/TrashController.php

if (!defined('GT_APP')) {
    define('GT_APP', true);
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../models/Cliente.php';

$conn = getConnection();
Cliente::init($conn);

// 1) Obtener clientes eliminados
$clientesEliminados = Cliente::allDeleted();

// 2) Definir recursos del módulo
if (!defined('MODULE_CSS')) define('MODULE_CSS', 'clientes.css');
if (!defined('MODULE_JS'))  define('MODULE_JS', 'clientes.js');

// 3) Renderizar vista
require_once INCLUDES_PATH . '/header_erp.php';
require __DIR__ . '/../views/trash.php';
require_once INCLUDES_PATH . '/footer.php';
