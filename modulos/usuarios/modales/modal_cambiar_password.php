<?php
    // archivo: /modulos/usuarios/modales/modal_cambiar_password.php
    // ----------------------------------------------
    // Modal para cambiar la contraseña de un usuario
    // ----------------------------------------------   
?>

<div class="modal fade" id="modalCambiarPassword" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header bg-warning">
                <h5 class="modal-title">Cambiar contraseña</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <label>Nueva contraseña</label>
                <input type="password" id="pass1" class="form-control mb-2">

                <label>Confirmar contraseña</label>
                <input type="password" id="pass2" class="form-control">
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-warning" id="btnGuardarPassword">Guardar</button>
            </div>

        </div>
    </div>
</div>