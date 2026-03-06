   <?php
    // archivo: /modulos/empleados/modales/modal_empleado.php
   ?>
   
   <div class="modal fade" id="modalEmpleado" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fa-solid fa-user"></i> Empleado
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <!-- BODY -->
            <div class="modal-body">

                <form id="formEmpleado" enctype="multipart/form-data">

                    <input type="hidden" id="e_id" name="e_id">
					<input type="hidden" id="foto_actual" name="foto_actual">

                    <div class="row g-3">

                        <!-- Nombres -->
                        <div class="col-md-4">
                            <label class="form-label">Nombres</label>
                            <input type="text" id="e_nombres" name="e_nombres" class="form-control" required>
                        </div>

                        <!-- Apellidos -->
                        <div class="col-md-4">
                            <label class="form-label">Apellidos</label>
                            <input type="text" id="e_apellidos" name="e_apellidos" class="form-control" required>
                        </div>

                        <!-- DNI -->
                        <div class="col-md-4">
                            <label class="form-label">DNI</label>
                            <input type="text" id="e_dni" name="e_dni" class="form-control" maxlength="8" required>
                        </div>

                        <!-- Teléfono -->
                        <div class="col-md-4">
                            <label class="form-label">Teléfono</label>
                            <input type="text" id="e_telefono" name="e_telefono" class="form-control">
                        </div>

                        <!-- Correo -->
                        <div class="col-md-4">
                            <label class="form-label">Correo</label>
                            <input type="email" id="e_correo" name="e_correo" class="form-control">
                        </div>

                        <!-- Dirección -->
                        <div class="col-md-12">
                            <label class="form-label">Dirección</label>
                            <input type="text" id="e_direccion" name="e_direccion" class="form-control">
                        </div>

                        <!-- UBIGEO: Departamento / Provincia / Distrito -->
                        <div class="col-md-4">
                            <label class="form-label">Departamento</label>
                            <select id="departamento_id" name="departamento_id" class="form-select" required></select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Provincia</label>
                            <select id="provincia_id" name="provincia_id" class="form-select" required></select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Distrito</label>
                            <select id="distrito_id" name="distrito_id" class="form-select" required></select>
                        </div>

                         <!-- Empresa -->
                        <div class="col-md-4">
                            <label class="form-label">Empresa</label>
                            <select id="empresa_id" name="empresa_id" class="form-select" required></select>
                        </div>

                        <!-- Fecha ingreso -->
                        <div class="col-md-4">
                            <label class="form-label">Fecha de ingreso</label>
                            <input type="date" id="e_fecha_ingreso" name="e_fecha_ingreso" class="form-control">
                        </div>

                        <!-- Estado -->
                        <div class="col-md-4">
                            <label class="form-label">Estado</label>
                            <select id="e_estado" name="e_estado" class="form-select">
                                <option value="activo">Activo</option>
                                <option value="inactivo">Inactivo</option>
                            </select>
                        </div>

                        <!-- Foto -->
                        <div class="col-md-12">
                            <label class="form-label">Foto</label>
                            <input type="file" id="e_foto" name="e_foto" class="form-control">
                            <img id="preview_foto_empleado" src="" class="img-thumbnail mt-2" style="display:none; max-height:150px;">
                        </div>

                        <!-- Roles -->
                        <div class="col-md-12">
                            <label class="form-label">Roles</label>
                            <div id="contenedor_roles" class="border rounded p-2" style="min-height: 80px;">
                                <span class="text-muted">Cargando...</span>
                            </div>
                        </div>

                    </div>

                </form>

            </div>

            <!-- FOOTER -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" id="btnGuardarEmpleado" class="btn btn-primary">
                    <i class="fa-solid fa-save"></i> Guardar
                </button>
            </div>

        </div>
    </div>
</div>
