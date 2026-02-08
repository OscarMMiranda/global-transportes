<?php
// archivo: /includes/funciones_historial.php

require_once __DIR__ . '/config.php';

function registrarHistorial($asistencia_id, $accion, $usuario, $detalle = null)
{
    $conn = getConnection();

    if (!$conn) {
        error_log("❌ registrarHistorial(): sin conexión");
        return;
    }

    $sql = "INSERT INTO asistencia_historial 
            (asistencia_id, accion, usuario, detalle, fecha_hora)
            VALUES (?, ?, ?, ?, NOW())";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        error_log("❌ registrarHistorial(): error prepare: " . mysqli_error($conn));
        return;
    }

    mysqli_stmt_bind_param($stmt, "isss", $asistencia_id, $accion, $usuario, $detalle);

    if (!mysqli_stmt_execute($stmt)) {
        error_log("❌ registrarHistorial(): error execute: " . mysqli_error($conn));
    }

    mysqli_stmt_close($stmt);
}
