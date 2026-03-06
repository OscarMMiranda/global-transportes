<?php
// archivo: /modulos/empleados/componentes/modal_empleado.php

?>

<div class="modal fade" id="modalEmpleado" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Empleado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="formEmpleado" enctype="multipart/form-data">

                <input type="hidden" id="e_id" name="e_id">
                <input type="hidden" id="foto_actual" name="foto_actual">

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-6">
                            <label>Nombres</label>
                            <input type="text" id="e_nombres" name="e_nombres" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label>Apellidos</label>
                            <input type="text" id="e_apellidos" name="e_apellidos" class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label>DNI</label>
                            <input type="text" id="e_dni" name="e_dni" maxlength="15" class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label>Teléfono</label>
                            <input type="text" id="e_telefono" name="e_telefono" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label>Correo</label>
                            <input type="email" id="e_correo" name="e_correo" class="form-control">
                        </div>

                        <div class="col-md-12">
                            <label>Dirección</label>
                            <input type="text" id="e_direccion" name="e_direccion" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label>Departamento</label>
                            <select id="departamento_id" name="departamento_id" class="form-select" required></select>
                        </div>

                        <div class="col-md-4">
                            <label>Provincia</label>
                            <select id="provincia_id" name="provincia_id" class="form-select" required></select>
                        </div>

                        <div class="col-md-4">
                            <label>Distrito</label>
                            <select id="distrito_id" name="distrito_id" class="form-select" required></select>
                        </div>

                        <div class="col-md-6">
                            <label>Fecha de ingreso</label>
                            <input type="date" id="e_fecha_ingreso" name="e_fecha_ingreso" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label>Estado</label>
                            <select id="e_estado" name="e_estado" class="form-select">
                                <option value="activo">Activo</option>
                                <option value="inactivo">Inactivo</option>
                                <option value="suspendido">Suspendido</option>
                            </select>
                        </div>

                        <div class="col-md-12 mt-3">
                            <label>Roles del empleado</label>
                            <div id="contenedor_roles" class="border p-2 rounded" style="min-height: 80px;">
                                <!-- Se llena por AJAX -->
                            </div>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label>Foto</label>
                            <input type="file" id="e_foto" name="e_foto" class="form-control">
                            <img id="preview_foto_empleado" class="img-thumbnail mt-2" style="display:none; max-height:150px;">
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" id="btnGuardarEmpleado" class="btn btn-success">Guardar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>

            </form>

        </div>
    </div>
</div>
