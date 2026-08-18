<?php
// archivo: /modulos/papeletas/modales/modal_nueva_papeleta.php
?>

<div class="modal fade" id="modalNuevaPapeleta" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Registrar nueva papeleta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <!-- FORMULARIO PRINCIPAL -->
                <form id="formNuevaPapeleta">

                    <div class="row">

                        <!-- NÚMERO DE PAPELETA -->
                        <div class="col-md-6 mb-3">
                            <label>Número de papeleta</label>
                            <input type="text" class="form-control" name="codigo_papeleta" required>
                        </div>

                        <!-- VEHÍCULO -->
                        <div class="col-md-6 mb-3">
                            <label>Vehículo</label>
                            <select class="form-control" name="vehiculo_id" id="vehiculoNuevaSelect" required></select>
                        </div>

                        <!-- CONDUCTOR -->
                        <div class="col-md-6 mb-3">
                            <label>Conductor</label>
                            <select class="form-control" name="conductor_id" id="conductorNuevaSelect">
                                <option value="">-- Sin conductor --</option>
                            </select>
                        </div>

                        <!-- ENTIDAD EMISORA -->
                        <div class="col-md-6 mb-3">
                            <label>Entidad emisora</label>
                            <select class="form-control" name="entidad_emisora_id" id="entidadEmisoraSelect" required></select>
                        </div>

                        <!-- INFRACCIÓN -->
                        <div class="col-md-6 mb-3">
                            <label>Infracción</label>
                            <select class="form-control" name="infraccion_id" id="infraccionSelect" required></select>
                        </div>

                        <!-- FECHAS -->
                        <div class="col-md-4 mb-3">
                            <label>Fecha infracción</label>
                            <input type="date" class="form-control" name="fecha_infraccion" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Fecha notificación</label>
                            <input type="date" class="form-control" name="fecha_notificacion">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Fecha vencimiento</label>
                            <input type="date" class="form-control" name="fecha_vencimiento">
                        </div>

                        <!-- LUGAR -->
                        <div class="col-md-12 mb-3">
                            <label>Lugar</label>
                            <input type="text" class="form-control" name="lugar">
                        </div>

                        <!-- DESCRIPCIÓN -->
                        <div class="col-md-12 mb-3">
                            <label>Descripción</label>
                            <textarea class="form-control" name="descripcion" rows="3"></textarea>
                        </div>

                    </div>

                </form>

                <hr>

                <!-- ARCHIVO ADJUNTO -->
                <h5 class="mt-3">Adjuntar archivo (foto o PDF)</h5>

                <form id="formArchivoNuevaPapeleta" enctype="multipart/form-data">

                    <div class="row">

                        <div class="col-md-12 mb-3">
                            <label>Archivo</label>
                            <input type="file" name="archivo" class="form-control"
                                   accept="image/*,application/pdf">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label>Descripción del archivo</label>
                            <input type="text" name="descripcion_archivo" class="form-control"
                                   placeholder="Ej: Foto de la papeleta, Constancia de pago, etc.">
                        </div>

                    </div>

                </form>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button class="btn btn-primary" id="btnGuardarPapeleta">Guardar</button>
            </div>

        </div>
    </div>
</div>
