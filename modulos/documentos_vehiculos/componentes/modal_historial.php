<!-- 
    archivo: /modulos/documentos_vehiculos/componentes/modal_historial.php

    ============================================================
     MODAL HISTORIAL DE DOCUMENTOS DEL VEHÍCULO
     RESPONSABILIDAD: mostrar versiones anteriores del documento
     COMPATIBLE: PHP 5.6 + IIS
     ============================================================ -->

<div id="modalHistorialVehiculo"
     style="display:none;
            position:fixed;
            top:0; left:0;
            width:100%; height:100%;
            background:rgba(0,0,0,0.5);
            z-index:10000;">

    <div style="background:#fff;
                width:80%;
                height:80%;
                margin:40px auto;
                padding:15px;
                border-radius:6px;
                position:relative;
                box-shadow:0 0 10px rgba(0,0,0,0.3);">

        <!-- Botón cerrar -->
        <button type="button"
                onclick="cerrarHistorialVehiculo()"
                class="btn btn-sm btn-secondary"
                style="position:absolute; top:10px; right:10px;">
            Cerrar
        </button>

        <!-- Título dinámico -->
        <h4 id="tituloHistorialVehiculo" class="mb-3"></h4>

        <!-- Contenido dinámico -->
        <div id="contenidoHistorialVehiculo"
             style="height:88%;
                    overflow-y:auto;
                    padding-right:5px;">
        </div>

    </div>
</div>

