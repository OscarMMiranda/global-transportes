<?php
// archivo: /modulos/componentes/mensajes_flash.php
// Sistema de mensajes flash reutilizable y seguro

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Validación defensiva: evitar warnings si flash no es array
if (!isset($_SESSION['flash']) || !is_array($_SESSION['flash'])) {
    return;
}

foreach ($_SESSION['flash'] as $tipo => $mensajes) {

    if (!is_array($mensajes)) {
        continue;
    }

    foreach ($mensajes as $msg) {

        $clase = 'alert-info';

        switch ($tipo) {
            case 'success':
                $clase = 'alert-success';
                break;
            case 'error':
                $clase = 'alert-danger';
                break;
            case 'warning':
                $clase = 'alert-warning';
                break;
        }

        echo '<div class="alert ' . $clase . ' alert-dismissible fade show" role="alert">';
        echo htmlspecialchars($msg, ENT_QUOTES, 'UTF-8');
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
        echo '</div>';
    }
}

// Eliminar mensajes después de mostrarlos
unset($_SESSION['flash']);
?>