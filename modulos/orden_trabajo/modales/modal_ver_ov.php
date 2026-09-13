<!-- archivo: /modulos/orden_trabajo/modales/modal_ver.php -->


<div class="modal fade" id="modalVerOV" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">

      <!-- HEADER -->
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">
          Orden de Vehículo: <span id="ov_numero"></span>
        </h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- BODY -->
      <div class="modal-body">

        <!-- ============================
             A. DATOS DE LA OV
        ============================ -->
        <h5 class="mt-3">Datos de la Orden de Vehículo</h5>
        <table class="table table-bordered table-sm">
          <tr><th style="width:200px;">Vehículo</th><td id="ov_vehiculo"></td></tr>
          <tr><th>Conductor</th><td id="ov_conductor"></td></tr>
          <tr><th>Fecha Salida</th><td id="ov_fecha_salida"></td></tr>
          <tr><th>Semana Viaje</th><td id="ov_semana"></td></tr>
          <tr><th>Estado</th><td id="ov_estado"></td></tr>
        </table>

        <!-- ============================
             B. DATOS DEL VIAJE
        ============================ -->
        <h5 class="mt-4">Datos del Viaje</h5>
        <table class="table table-bordered table-sm">
          <tr><th style="width:200px;">Origen</th><td id="viaje_origen"></td></tr>
          <tr><th>Destino</th><td id="viaje_destino"></td></tr>
          <tr><th>Zona</th><td id="viaje_zona"></td></tr>
          <tr><th>Fecha Llegada</th><td id="viaje_llegada"></td></tr>
          <tr><th>Distancia (km)</th><td id="viaje_distancia"></td></tr>
          <tr><th>Estado</th><td id="viaje_estado"></td></tr>
        </table>

        <button class="btn btn-sm btn-warning mb-3" id="btnEditarViaje">
          Editar Viaje
        </button>

        <!-- ============================
             C. DETALLES DEL VIAJE
        ============================ -->
        <h5 class="mt-4">Detalles del Viaje</h5>
        <table class="table table-bordered table-sm">
          <tr><th style="width:200px;">Tipo de Carga</th><td id="det_tipo_carga"></td></tr>
          <tr><th>Mercadería</th><td id="det_mercaderia"></td></tr>
          <tr><th>Contenedor</th><td id="det_contenedor"></td></tr>
          <tr><th>Almacén Retiro</th><td id="det_alm_retiro"></td></tr>
          <tr><th>Almacén Devolución</th><td id="det_alm_devolucion"></td></tr>
        </table>

        <button class="btn btn-sm btn-warning mb-3" id="btnEditarDetalles">
          Editar Detalles
        </button>

        <!-- ============================
             D. GUIAS
        ============================ -->
        <h5 class="mt-4">Guías</h5>
        <table class="table table-bordered table-sm">
          <tr><th style="width:200px;">Guía Cliente</th><td id="guia_cliente"></td></tr>
          <tr><th>Guía Transporte</th><td id="guia_transporte"></td></tr>
        </table>

        <button class="btn btn-sm btn-warning mb-3" id="btnEditarGuias">
          Editar Guías
        </button>

      </div>

      <!-- FOOTER -->
      <div class="modal-footer">
        <button class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>

    </div>
  </div>
</div>
