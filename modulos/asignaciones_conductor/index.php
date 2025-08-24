<?php
//  archivo :   /modulos/asignaciones_conductor/index.php

    session_start();
        
// 2)   Modo DEBUG (solo DEV)
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('log_errors',     1);
    ini_set('error_log',      __DIR__ . '/error_log.txt');

//  3)  Carga de configuraciones globales y validador
    require_once __DIR__ . '/../../includes/validador.php';
    require_once __DIR__ . '/../../includes/config.php';

//  4)  Obtener la conexión unica
    $conn = getConnection();

//  5)  Acción desde URL, valor por defecto 'list'
    $action = isset($_GET['action']) ? $_GET['action'] : 'list';

//  6)  Despacho al controlador
    require_once __DIR__ . '/controlador.php';
