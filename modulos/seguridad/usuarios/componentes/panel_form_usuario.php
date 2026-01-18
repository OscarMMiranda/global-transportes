<?php
// archivo: /modulos/seguridad/usuarios/componentes/panel_form_usuario.php
// Panel principal: formulario de usuario
?>

<div class="card shadow-sm">
    <div class="card-header bg-secondary text-white py-2">
        <strong><i class="fa-solid fa-user-pen"></i> Datos del Usuario</strong>
    </div>

    <div class="card-body">

        <form id="form-usuario">

            <input type="hidden" id="usuario_id" name="usuario_id">

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre completo</label>
                <input type="text" class="form-control" id="nombre" name="nombre">
            </div>

            <div class="mb-3">
                <label for="usuario" class="form-label">Usuario</label>
                <input type="text" class="form-control" id="usuario" name="usuario">
            </div>

            <div class="mb-3">
                <label for="correo" class="form-label">Correo electrónico</label>
                <input type="email" class="form-control" id="correo" name="correo">
            </div>

            <div class="mb-3">
                <label for="rol_id" class="form-label">Rol asignado</label>
                <select class="form-select" id="rol_id" name="rol_id">
                    <option value="">Seleccione...</option>
                    <!-- Se llenará vía AJAX -->
                </select>
            </div>

            <div class="d-flex gap-2">

                <button type="submit" class="btn btn-success" id="btn-guardar">
                    <i class="fa-solid fa-floppy-disk"></i> Guardar
                </button>

                <button type="button" class="btn btn-secondary d-none" id="btn-cancelar">
                    <i class="fa-solid fa-xmark"></i> Cancelar
                </button>

            </div>

        </form>

    </div>
</div>