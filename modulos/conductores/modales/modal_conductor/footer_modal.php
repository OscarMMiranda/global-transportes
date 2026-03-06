<?php
// archivo: /modulos/conductores/componentes/footer_modal.php
// Pie de modal reutilizable para formularios del ERP

/**
 * Parámetros esperados:
 * $idBotonGuardar (string) — ID del botón de guardar
 * $textoGuardar   (string) — Texto del botón de guardar
 */

if (!isset($idBotonGuardar)) $idBotonGuardar = "btnGuardar";
if (!isset($textoGuardar))   $textoGuardar   = "Guardar";
?>

<div class="modal-footer bg-light">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
        <i class="fa fa-times"></i> Cancelar
    </button>

    <button type="button" class="btn btn-success" id="<?= htmlspecialchars($idBotonGuardar) ?>">
        <i class="fa fa-save"></i> <?= htmlspecialchars($textoGuardar) ?>
    </button>
</div>
