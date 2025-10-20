<?php
// archivo: mensajes_flash.php

if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
    $texto = '';
    $icono = 'fas fa-check-circle';
    $clase = 'success';

    switch ($msg) {
        case 'agregado':
            $texto = '✅ Tipo de vehículo agregado correctamente.';
            break;
        case 'actualizado':
            $texto = '✅ Tipo de vehículo actualizado correctamente.';
            break;
        case 'eliminado':
            $texto = '🗑️ Tipo de vehículo eliminado.';
            break;
        case 'reactivado':
            $texto = '🔁 Tipo de vehículo restaurado.';
            break;
        default:
            $texto = '✅ Acción completada.';
    }

    echo "<div class='alert alert-$clase d-flex align-items-center' role='alert'>
            <i class='$icono me-2'></i> $texto
          </div>";
}

if (isset($_GET['error'])) {
    $error = htmlspecialchars($_GET['error']);
    echo "<div class='alert alert-danger d-flex align-items-center' role='alert'>
            <i class='fas fa-exclamation-triangle me-2'></i> ❌ $error
          </div>";
}