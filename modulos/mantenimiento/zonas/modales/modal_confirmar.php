<?php
// archivo: modales/modal_confirmar.php
// propósito: modal de confirmación para activar o desactivar distritos

// 🛡️ Validación defensiva
$accion     = isset($_GET['accion'])     ? $_GET['accion']     : '';
$distritoId = isset($_GET['id'])         ? (int) $_GET['id']   : 0;
$nombre     = isset($_GET['nombre'])     ? $_GET['nombre']     : '';
$estado     = isset($_GET['estado'])     ? $_GET['estado']     : 'inactivo';

$confirmar = ($accion === 'activar') ? 'Reactivar' : 'Desactivar';
$color     = ($accion === 'activar') ? 'success'   : 'danger';
$icono     = ($accion === 'activar') ? 'check'     : 'ban';
?>
<div class="modal fade" id="modalConfirmar" tabindex="-1" aria-labelledby="modalConfirmarLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="get" class="modal-content">
      <div class="modal-header bg-<?php echo $color; ?> text-white">
        <h5 class="modal-title" id="modalConfirmarLabel">
          <i class="fas fa-<?php echo $icono; ?> me-2"></i> <?php echo $confirmar; ?> distrito
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <p>¿Estás seguro de que deseas <strong><?php echo strtolower($confirmar); ?></strong> el distrito <strong><?php echo htmlspecialchars($nombre); ?></strong>?</p>
        <input type="hidden" name="<?php echo $accion; ?>" value="<?php echo $distritoId; ?>">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-<?php echo $color; ?>">Sí, <?php echo strtolower($confirmar); ?></button>
      </div>
    </form>
  </div>
</div>