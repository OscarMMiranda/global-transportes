  <?php 
    // archivo: /modulos/empleados/modales/modal_ver_empleado.php
  ?>

  <div class="modal fade" id="modalVerEmpleado" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fa-solid fa-eye"></i> Ver empleado
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- BODY -->
            <div class="modal-body">

                <div class="row">

                    <!-- FOTO -->
                    <div class="col-md-4 text-center mb-3">
                        <img id="ver_foto" 
                             src="/assets/img/sin_foto.png" 
                             class="img-fluid rounded border" 
                             style="max-height: 220px; object-fit: cover;">
                    </div>

                    <!-- DATOS -->
                    <div class="col-md-8">

                        <div class="mb-2">
                            <strong>ID:</strong> <span id="ver_id"></span>
                        </div>

                        <div class="mb-2">
                            <strong>Nombres:</strong> <span id="ver_nombres"></span>
                        </div>

                        <div class="mb-2">
                            <strong>Apellidos:</strong> <span id="ver_apellidos"></span>
                        </div>

                        <div class="mb-2">
                            <strong>DNI:</strong> <span id="ver_dni"></span>
                        </div>

                        <div class="mb-2">
                            <strong>Empresa:</strong> <span id="ver_empresa"></span>
                        </div>

                        <div class="mb-2">
                            <strong>Correo:</strong> <span id="ver_correo"></span>
                        </div>

                        <div class="mb-2">
                            <strong>Teléfono:</strong> <span id="ver_telefono"></span>
                        </div>

                        <div class="mb-2">
                            <strong>Dirección:</strong> <span id="ver_direccion"></span>
                        </div>

                        <div class="mb-2">
                            <strong>Fecha ingreso:</strong> <span id="ver_fecha_ingreso"></span>
                        </div>

                        <div class="mb-2">
                            <strong>Roles:</strong> <span id="ver_roles"></span>
                        </div>

                    </div>

                </div>

            </div>

            <!-- FOOTER -->
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">
                    Cerrar
                </button>
            </div>

        </div>
    </div>
</div>
