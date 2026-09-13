<!-- archivo: /modulos/viajes/modales/modal_test.php -->

<div class="modal-corp" id="modalTest">

    <div class="modal-corp-content" style="padding:20px; background:white; max-width:600px;">

        <!-- HEADER -->
        <div class="modal-corp-header" style="background:#444; color:white; padding:10px;">
            <h4 style="margin:0;">Modal de Prueba</h4>
            <button type="button" class="btn-cerrar" onclick="cerrarModal()">×</button>
        </div>

        <!-- BODY -->
        <div class="modal-corp-body" style="padding:15px;">
            <p>Este es un modal de prueba para verificar que el sistema corporativo de modales funciona correctamente.</p>

            <div class="fila">
                <div class="col">
                    <label>Campo de prueba</label>
                    <input type="text" id="campo_test" placeholder="Escribe algo...">
                </div>
            </div>

            <div class="fila">
                <div class="col">
                    <label>Otro campo</label>
                    <input type="number" id="campo_test2">
                </div>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="modal-corp-footer" style="padding:10px; text-align:right;">
            <button class="btn btn-success" onclick="cerrarModal()">Cerrar</button>
        </div>

    </div>

</div>
