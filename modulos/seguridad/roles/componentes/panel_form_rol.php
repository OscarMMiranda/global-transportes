<?php
//  /modulos/seguridad/roles/componentes/panel_form_rol.php
?>


<div class="card">
    <div class="card-header bg-success text-white">
        Crear / Editar Rol
    </div>

    <div class="card-body">

        <form id="formRol">

            <!-- Campo oculto para modo edición -->
            <input type="hidden" name="id" id="rol_id">
            <input type="hidden" name="modo" id="modo" value="crear">

            <div class="mb-3">
                <label class="form-label">Nombre del Rol</label>
                <input type="text" name="nombre" id="rol_nombre" class="form-control" required>
            </div>

            <div class="d-flex gap-2">

                <button type="submit" class="btn btn-success">
                    <i class="fa-solid fa-save"></i> Guardar
                </button>

                <button type="button" id="btnCancelarEdicion" class="btn btn-secondary d-none">
                    <i class="fa-solid fa-xmark"></i> Cancelar
                </button>

            </div>

        </form>

    </div>
</div>
