<?php
    // archivo: /modulos/asistencias/modales/modal_feriados.php
?>

<div id="modalFeriado" class="modal">
    <div class="modal-content">

        <h3 id="tituloModalFeriado">Nuevo Feriado</h3>

        <!-- ID del feriado (solo para edición) -->
        <input type="hidden" id="feriado_id">

        <label>Fecha:</label>
        <input type="date" id="feriado_fecha">

        <label>Descripción:</label>
        <input type="text" id="feriado_descripcion" placeholder="Ej: Navidad, Año Nuevo">

        <button id="btnGuardarFeriado">Guardar</button>
        <button class="cerrar">Cerrar</button>

    </div>
</div>
