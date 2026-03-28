<!-- archivo: /modulos/clientes/modales/modal_cliente.php -->

<div class="modal fade" id="modalCliente" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- HEADER DINÁMICO -->
            <div class="modal-header bg-success text-white" id="modalClienteHeader">
                <h5 class="modal-title" id="modalClienteLabel">
                    <i class="fa fa-plus-circle"></i> Nuevo Cliente
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- FORMULARIO COMPLETO -->
            <form id="formCliente">

                <div class="modal-body">

                    <input type="hidden" id="cliente_id" name="cliente_id">

                    <div class="row mb-2">
                        <div class="col-md-8">
                            <label>Nombre</label>
                            <input type="text" id="cliente_nombre" name="nombre" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Tipo Cliente</label>
                            <select id="cliente_tipo" name="tipo_cliente" class="form-control"></select>
                        </div>
                    </div>

                    <!-- UBIGEO -->
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <label>Departamento</label>
                            <select id="cliente_departamento" name="departamento_id" class="form-control"></select>
                        </div>
                        <div class="col-md-4">
                            <label>Provincia</label>
                            <select id="cliente_provincia" name="provincia_id" class="form-control"></select>
                        </div>
                        <div class="col-md-4">
                            <label>Distrito</label>
                            <select id="cliente_distrito" name="distrito_id" class="form-control"></select>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-12">
                            <label>Dirección</label>
                            <input type="text" id="cliente_direccion" name="direccion" class="form-control">
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-4">
                            <label>RUC</label>
                            <input type="text" id="cliente_ruc" name="ruc" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Teléfono</label>
                            <input type="text" id="cliente_telefono" name="telefono" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Correo</label>
                            <input type="email" id="cliente_correo" name="correo" class="form-control">
                        </div>
                    </div>

                </div>

                <!-- FOOTER DENTRO DEL FORM -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarCliente">Guardar</button>
                </div>

            </form>

        </div>
    </div>
</div>
