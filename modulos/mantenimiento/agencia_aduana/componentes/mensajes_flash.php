<?php
// archivo: /modulos/mantenimiento/agencia_aduana/componentes/mensajes_flash.php

if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];

    switch ($msg) {
        case 'guardado':
            echo '<div class="alert alert-success text-center">✅ Agencia guardada correctamente.</div>';
            break;
        case 'eliminado':
            echo '<div class="alert alert-warning text-center">✅ Agencia eliminada correctamente.</div>';
            break;
        case 'reactivado':
            echo '<div class="alert alert-success text-center">✅ Agencia reactivada correctamente.</div>';
            break;
        case 'error':
            echo '<div class="alert alert-danger text-center">❌ Ocurrió un error inesperado.</div>';
            break;
    }
}

// ✅ Mostrar errores del formulario si existen
if (!empty($error)) {
    echo '<div class="alert alert-danger text-center">❌ ' . htmlspecialchars($error) . '</div>';
}