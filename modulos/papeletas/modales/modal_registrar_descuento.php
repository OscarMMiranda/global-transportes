<?php
    // archivo: /modulos/papeletas/modales/modal_registrar_descuento.php
?>  

<div class="modal fade" id="modalRegistrarDescuento" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Registrar descuento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">

                <form id="formRegistrarDescuento" autocomplete="off" novalidate>

                    <input type="hidden" name="papeleta_id" id="descuento_papeleta_id">

                    <div class="mb-3">
                        <label class="form-label">Tipo de descuento</label>
                        <select class="form-control" name="tipo" id="descuento_tipo" required aria-required="true">
                            <option value="">Seleccione</option>
                            <option value="Pronto pago">Pronto pago</option>
                            <option value="Descuento especial">Descuento especial</option>
                            <option value="Convenio">Convenio</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>

                    <div class="mb-3" id="grupo_tipo_entrada">
                        <label class="form-label">Aplicar como</label>
                        <div>
                            <label class="me-3"><input type="radio" name="descuento_tipo_input" value="monto" checked> Monto</label>
                            <label><input type="radio" name="descuento_tipo_input" value="porcentaje"> Porcentaje</label>
                        </div>
                    </div>

                    <div class="mb-3" id="grupo_monto">
                        <label class="form-label">Monto del descuento</label>
                        <input type="number" step="0.01" class="form-control" name="monto" id="descuento_monto" aria-label="Monto del descuento">
                    </div>

                    <div class="mb-3" id="grupo_porcentaje" style="display:none;">
                        <label class="form-label">Porcentaje (%)</label>
                        <input type="number" step="0.01" class="form-control" name="porcentaje" id="descuento_porcentaje" placeholder="Ej: 10" aria-label="Porcentaje de descuento">
                        <div id="descuento_monto_preview" class="small text-muted mt-1" aria-live="polite"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Fecha del descuento</label>
                        <input type="date" class="form-control" name="fecha" id="descuento_fecha" required aria-required="true">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Monto pendiente actual</label>
                        <input type="text" class="form-control" id="descuento_monto_pendiente" readonly aria-readonly="true">
                        <small class="text-muted d-block mt-1">
                            El porcentaje se aplicará sobre el <strong>monto pendiente</strong>. La vista previa muestra el monto equivalente.
                        </small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Observación (opcional)</label>
                        <textarea class="form-control" name="observacion" id="descuento_observacion" rows="2" placeholder="Motivo o nota"></textarea>
                    </div>

                </form>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Cerrar</button>
                <button class="btn btn-primary" id="btnRegistrarDescuento" type="button">Registrar descuento</button>
            </div>

        </div>
    </div>
</div>
