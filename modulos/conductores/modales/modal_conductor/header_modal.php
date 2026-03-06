	<?php
	// archivo: modulos/conductores/modales/modal_conductor/header_modal.php	


    if (!isset($titulo))  $titulo = 'Título del Modal';
	if (!isset($icono))   $icono  = 'fa fa-circle-info';
?>

<div class="modal-header bg-primary text-white d-flex align-items-center">
    <h5 class="modal-title fw-semibold d-flex align-items-center gap-2">
        <i class="<?= htmlspecialchars($icono) ?> fs-5"></i>
        <span><?= htmlspecialchars($titulo) ?></span>
    </h5>
    <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal"></button>
</div>