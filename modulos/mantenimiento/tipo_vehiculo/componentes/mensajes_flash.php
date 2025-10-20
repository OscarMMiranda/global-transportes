<?php
// archivo: /modulos/mantenimiento/tipo_vehiculo/componentes/mensajes_flash.php

if (!empty($_SESSION['flash']) && is_array($_SESSION['flash'])):
  $tipo    = isset($_SESSION['flash']['tipo']) ? $_SESSION['flash']['tipo'] : 'info';       // success, danger, warning, info
  $mensaje = isset($_SESSION['flash']['mensaje']) ? $_SESSION['flash']['mensaje'] : '';

  // Iconos por tipo
  $iconos = array(
    'success' => 'fas fa-check-circle',
    'danger'  => 'fas fa-times-circle',
    'warning' => 'fas fa-exclamation-triangle',
    'info'    => 'fas fa-info-circle'
  );
  $icono = isset($iconos[$tipo]) ? $iconos[$tipo] : 'fas fa-info-circle';

  // Eliminar el mensaje para que no se repita
  unset($_SESSION['flash']);
?>

  <div class="alert alert-<?= htmlspecialchars($tipo) ?> alert-dismissible fade show text-center shadow-sm my-3" role="alert">
    <i class="<?= $icono ?> me-2"></i> <?= htmlspecialchars($mensaje) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
  </div>

<?php endif; ?>