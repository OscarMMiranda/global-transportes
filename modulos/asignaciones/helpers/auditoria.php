<?php
// archivo: /modulos/asignaciones/helpers/auditoria.php

function registrarAuditoriaAsignacion($conn, $asignacionId, $accion, $antes, $despues, $usuario) {
    $sql = "INSERT INTO asignaciones_auditoria (asignacion_id, accion, antes, despues, usuario, fecha)
            VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt = mysqli_prepare($conn, $sql);
    $antesJson   = json_encode($antes);
    $despuesJson = json_encode($despues);
    mysqli_stmt_bind_param($stmt, "issss", $asignacionId, $accion, $antesJson, $despuesJson, $usuario);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}
