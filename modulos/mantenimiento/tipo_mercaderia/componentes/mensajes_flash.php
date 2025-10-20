<?php
// archivo: /modulos/mantenimiento/componentes/mensajes_flash.php

// Compatibilidad con PHP 5.6
$msg   = isset($msg)   ? $msg   : (isset($_SESSION['msg'])   ? $_SESSION['msg']   : null);
$error = isset($error) ? $error : (isset($_SESSION['error']) ? $_SESSION['error'] : null);

// Limpieza defensiva (solo si no se hizo antes)
if (isset($_SESSION['msg']) || isset($_SESSION['error'])) {
    unset($_SESSION['msg'], $_SESSION['error']);
}
?>

<?php if ($msg): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>
    <?= htmlspecialchars($msg) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
  </div>
<?php endif; ?>

<?php if ($error): ?>
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-triangle me-2"></i>
    <?= htmlspecialchars($error) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
  </div>
<?php endif; ?>