<?php
// archivo temporal SOLO para pruebas

session_start(); // iniciar sesión ANTES de asignar valores

// Simular usuario logueado
$_SESSION['usuario_id'] = 1;
$_SESSION['usuario_nombre'] = 'Administrador';

// Simular datos enviados por POST
$_POST['modulo']      = 'usuarios';
$_POST['accion']      = 'prueba';
$_POST['registro_id'] = 123;
$_POST['descripcion'] = 'Prueba de auditoría manual';
$_POST['antes']       = '';
$_POST['despues']     = '';

// Ejecutar el registrador
require 'registrar_auditoria.php';