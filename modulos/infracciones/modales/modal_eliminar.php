<?php
// archivo: /modulos/infracciones/modales/modal_eliminar.php
?>

<div class="modal fade" id="modalEliminar" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Eliminar Infracción</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="formEliminarInfraccion">

                <input type="hidden" name="id" id="eliminar_id">

                <div class="modal-body">

                    <p class="text-center mb-3">
                        ¿Está seguro que desea eliminar esta infracción?<br>
                        <strong>Esta acción no se puede deshacer.</strong>
                    </p>

                    <div class="border rounded p-3 bg-light">

                        <div class="row">

                            <div class="col-md-4 mb-2">
                                <label class="fw-bold">Código:</label>
                                <div id="eliminar_codigo"></div>
                            </div>

                            <div class="col-md-8 mb-2">
                                <label class="fw-bold">Descripción:</label>
                                <div id="eliminar_descripcion"></div>
                            </div>

                            <div class="col-md-4 mb-2">
                                <label class="fw-bold">Gravedad:</label>
                                <div id="eliminar_gravedad"></div>
                            </div>

                            <div class="col-md-4 mb-2">
                                <label class="fw-bold">Puntos:</label>
                                <div id="eliminar_puntos"></div>
                            </div>

                            <div class="col-md-4 mb-2">
                                <label class="fw-bold">% UIT:</label>
                                <div id="eliminar_porcentaje_uit"></div>
                            </div>

                            <div class="col-md-4 mb-2">
                                <label class="fw-bold">Monto Base (calculado):</label>
                                <div id="eliminar_monto_base"></div>
                            </div>

                            <div class="col-md-8 mb-2">
                                <label class="fw-bold">Entidad Emisora:</label>
                                <div id="eliminar_entidad_emisora"></div>
                            </div>

                        </div>

                        <hr>

                        <div class="row">

                            <div class="col-md-6 mb-2">
                                <label class="fw-bold">Creado por:</label>
                                <div id="eliminar_creado_por"></div>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label class="fw-bold">Fecha creación:</label>
                                <div id="eliminar_fecha_creacion"></div>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label class="fw-bold">Modificado por:</label>
                                <div id="eliminar_modificado_por"></div>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label class="fw-bold">Fecha modificación:</label>
                                <div id="eliminar_fecha_modificacion"></div>
                            </div>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </div>

            </form>

        </div>
    </div>
</div>
