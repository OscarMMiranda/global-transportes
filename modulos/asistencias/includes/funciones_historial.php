<?php
// archivo: /modulos/asistencias/includes/funciones_historial.php

// Cargar config.php SIEMPRE con ruta absoluta para evitar errores en PHP 5.6
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';

function registrarHistorial($asistencia_id, $accion, $usuario, $detalle = null)
{
    // Obtener conexión
    $conn = getConnection();

    // Insertar registro en historial
    $sql = "INSERT INTO asistencia_historial 
            (asistencia_id, accion, usuario, detalle, fecha_hora)
            VALUES (?, ?, ?, ?, NOW())";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "isss", $asistencia_id, $accion, $usuario, $detalle);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

