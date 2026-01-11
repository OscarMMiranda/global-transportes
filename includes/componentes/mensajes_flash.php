<?php
// archivo: /includes/mensajes_flash.php

if (!isset($_SESSION)) {
    session_start();
}

function mostrarMensaje($tipo, $mensaje) {
    $clase = "alert-info";

    switch ($tipo) {
        case "success": $clase = "alert-success"; break;
        case "error":   $clase = "alert-danger";  break;
        case "warning": $clase = "alert-warning"; break;
        case "info":    $clase = "alert-info";    break;
    }

    echo '<div class="alert ' . $clase . ' alert-dismissible fade show" role="alert">';
    echo $mensaje;
    echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
    echo '</div>';
}

// Mostrar mensajes si existen
if (isset($_SESSION['flash_messages']) && is_array($_SESSION['flash_messages'])) {
    foreach ($_SESSION['flash_messages'] as $msg) {
        mostrarMensaje($msg['tipo'], $msg['mensaje']);
    }
    unset($_SESSION['flash_messages']); // limpiar después de mostrar
}
?>