<?php
// archivo: /modulos/conductores/modales/modal_conductor/form_modal.php
?>

<div class="modal-body">

    <form id="formConductor" enctype="multipart/form-data">

        <input type="hidden" id="c_id" name="id">
        <input type="hidden" id="c_foto_actual" name="foto_actual" value="">

        <div class="row g-4">

            <!-- ============================
                 COLUMNA IZQUIERDA
            ============================ -->
            <div class="col-md-7">

                <!-- Sección: Datos personales -->
                <h6 class="form-section-title">Datos personales</h6>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="c_nombres" class="form-label">Nombres</label>
                        <input type="text" class="form-control" id="c_nombres" name="nombres" required>
                    </div>

                    <div class="col-md-6">
                        <label for="c_apellidos" class="form-label">Apellidos</label>
                        <input type="text" class="form-control" id="c_apellidos" name="apellidos" required>
                    </div>
                </div>

                <div class="row g-3 mt-1">
                    <div class="col-md-6">
                        <label for="c_dni" class="form-label">DNI</label>
                        <input type="text" class="form-control" id="c_dni" name="dni" maxlength="8" required>
                    </div>

                    <div class="col-md-6">
                        <label for="c_licencia" class="form-label">Licencia de Conducir</label>
                        <input type="text" class="form-control" id="c_licencia" name="licencia_conducir" required>
                    </div>
                </div>

                <!-- Sección: Contacto -->
                <h6 class="form-section-title mt-3">Contacto</h6>

                <div class="row g-3">
                    <div class="col-md-8">
                        <label for="c_correo" class="form-label">Correo</label>
                        <input type="email" class="form-control" id="c_correo" name="correo">
                    </div>

                    <div class="col-md-4">
                        <label for="c_telefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="c_telefono" name="telefono">
                    </div>
                </div>

                <div class="mt-3">
                    <label for="c_direccion" class="form-label">Dirección</label>
                    <input type="text" class="form-control" id="c_direccion" name="direccion">
                </div>

                <!-- Sección: Empresa -->
                <h6 class="form-section-title mt-3">Empresa</h6>

                <div class="mb-3">
                    <select id="empresa_id" name="empresa_id" class="form-control" required>
                        <option value="">Seleccione...</option>
                    </select>
                </div>

                <!-- Sección: Ubigeo -->
                <h6 class="form-section-title mt-3">Ubigeo</h6>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="departamento_id" class="form-label">Departamento</label>
                        <select id="departamento_id" name="departamento_id" class="form-control" required>
                            <option value="">Seleccione...</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="provincia_id" class="form-label">Provincia</label>
                        <select id="provincia_id" name="provincia_id" class="form-control" required disabled>
                            <option value="">Seleccione...</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="distrito_id" class="form-label">Distrito</label>
                        <select id="distrito_id" name="distrito_id" class="form-control" required disabled>
                            <option value="">Seleccione...</option>
                        </select>
                    </div>
                </div>

                <div class="form-check mt-3">
                    <input class="form-check-input" type="checkbox" id="c_activo" name="activo" checked>
                    <label class="form-check-label" for="c_activo">Activo</label>
                </div>

            </div>

            <!-- ============================
                 COLUMNA DERECHA (FOTO)
            ============================ -->
            <div class="col-md-5 text-center">

                <h6 class="form-section-title">Foto del Conductor</h6>

                <div class="mb-3">
                    <img id="preview_foto" class="img-fluid rounded shadow-sm"
                         style="max-height: 220px; display:none;" alt="Foto del conductor">
                </div>

                <input type="file" class="form-control" id="c_foto" name="foto" accept="image/*">
                <div class="form-text">Formatos permitidos: JPG y PNG.</div>

            </div>

        </div>

    </form>

</div>
