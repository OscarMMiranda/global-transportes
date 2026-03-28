<!-- archivo: /modulos/clientes/modales/modal_ver_cliente.php -->

<div class="modal fade" id="modalVerCliente" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Información del Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <table class="table table-bordered table-sm">
                    <tr><th>ID</th><td id="ver_id"></td></tr>
                    <tr><th>Nombre</th><td id="ver_nombre"></td></tr>
                    <tr><th>Tipo Cliente</th><td id="ver_tipo_cliente"></td></tr>
                    <tr><th>RUC</th><td id="ver_ruc"></td></tr>
                    <tr><th>Dirección</th><td id="ver_direccion"></td></tr>
                    <tr><th>Teléfono</th><td id="ver_telefono"></td></tr>
                    <tr><th>Correo</th><td id="ver_correo"></td></tr>
                    <tr><th>Estado</th><td id="ver_estado"></td></tr>
                </table>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>

        </div>
    </div>
</div>
