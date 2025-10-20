<?php
function auditarCambioLugar($conn, $lugar_id, $accion, $usuario, $valores_antes, $valores_despues) {
    if (!is_object($conn) || get_class($conn) !== 'mysqli') {
        error_log("❌ auditoria_lugares: conexión inválida");
        return;
    }

    $fecha = date('Y-m-d H:i:s');

    $antes   = $conn->real_escape_string(json_encode($valores_antes, JSON_UNESCAPED_UNICODE));
    $despues = $conn->real_escape_string(json_encode($valores_despues, JSON_UNESCAPED_UNICODE));
    $usuario = $conn->real_escape_string($usuario);
    $accion  = $accion === 'insert' ? 'insert' : 'update';

    $sql = "INSERT INTO auditoria_lugares (
                lugar_id, accion, usuario, fecha, valores_antes, valores_despues
            ) VALUES (
                $lugar_id, '$accion', '$usuario', '$fecha', '$antes', '$despues'
            )";

    if (!$conn->query($sql)) {
        error_log("❌ auditoria_lugares: error SQL — " . $conn->error);
    }
}
?>