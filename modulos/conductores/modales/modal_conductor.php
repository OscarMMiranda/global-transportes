<?php
// archivo: /modulos/conductores/modales/modal_conductor.php
?>

<div class="modal fade" id="modalConductor" data-modo="crear" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">

            <!-- Encabezado -->
            <?php
                $titulo = "Registrar Conductor";
                $icono  = "fa fa-id-card";
                include __DIR__ . "/modal_conductor/header_modal.php";
            ?>

            <!-- Cuerpo (formulario completo) -->
            <?php include __DIR__ . "/modal_conductor/form_modal.php"; ?>

            <!-- Pie del modal -->
            <?php
                $idBotonGuardar = "btnGuardarConductor";
                $textoGuardar   = "Guardar";
                include __DIR__ . "/modal_conductor/footer_modal.php";
            ?>

        </div>
    </div>
</div>

