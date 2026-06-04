<?php
// archivo: /modulos/orden_trabajo/modales/modal_nuevo_cliente.php
?>

<div class="modal fade" id="modalNuevoCliente" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Registrar Nuevo Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <form id="formNuevoCliente">

                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label class="form-label">Nombre / Razón Social</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Tipo de Cliente</label>
                            <select id="tipo_cliente" name="tipo_cliente" class="form-select" required>
                                <option value="">Cargando...</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">

                        <div class="col-md-4">
                            <label class="form-label">RUC</label>
                            <input type="text" name="ruc" class="form-control">
                        </div>

                        <div class="col-md-8">
                            <label class="form-label">Dirección</label>
                            <input type="text" name="direccion" class="form-control">
                        </div>

                    </div>

                    <div class="row mb-3">

                        <div class="col-md-4">
                            <label class="form-label">Departamento</label>
                            <select id="departamento_id" name="departamento_id" class="form-select" required>
                                <option value="">Seleccione...</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Provincia</label>
                            <select id="provincia_id" name="provincia_id" class="form-select" required>
                                <option value="">Seleccione...</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Distrito</label>
                            <select id="distrito_id" name="distrito_id" class="form-select" required>
                                <option value="">Seleccione...</option>
                            </select>
                        </div>

                    </div>

                    <div class="row mb-3">

                        <div class="col-md-6">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="telefono" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Correo</label>
                            <input type="email" name="correo" class="form-control">
                        </div>

                    </div>

                </form>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" id="btnGuardarCliente" class="btn btn-primary">Guardar Cliente</button>
            </div>

        </div>
    </div>
</div>

