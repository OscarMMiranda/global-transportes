<?php
// archivo: /modulos/vehiculos/modales/modal_ver_vehiculo.php
?>

<!-- <div class="modal fade" id="modalVerVehiculo" tabindex="-1" aria-hidden="true"> -->

<div class="modal fade" id="modalVerVehiculo" data-id="" tabindex="-1" aria-hidden="true">

  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">

      <!-- HEADER -->
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">
          <i class="fa-solid fa-car-side me-2"></i>
          Ficha del Vehículo
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <!-- BODY -->
      <div class="modal-body position-relative">

        <!-- TABS -->
        <ul class="nav nav-tabs" id="vehiculoTabs" role="tablist">
          <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tab-basico">Básico</a></li>
          <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-ficha-ver">Ficha Técnica</a></li>
          <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-documentos">Documentos</a></li>
          <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-fotos">Fotos</a></li>
          <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-mantenimientos">Mantenimientos</a></li>
          <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-asignaciones">Asignaciones</a></li>
          <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-papeletas">Papeletas</a></li>
          <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-historial">Historial</a></li>
        </ul>

        <!-- TAB CONTENT -->
        <div class="tab-content mt-3">
          <div class="tab-pane fade show active" id="tab-basico"></div>
          <div class="tab-pane fade" id="tab-ficha-ver"></div>
          <div class="tab-pane fade" id="tab-documentos"></div>
          <div class="tab-pane fade" id="tab-fotos"></div>
          <div class="tab-pane fade" id="tab-mantenimientos"></div>
          <div class="tab-pane fade" id="tab-asignaciones"></div>
          <div class="tab-pane fade" id="tab-papeletas"></div>
          <div class="tab-pane fade" id="tab-historial"></div>
        </div>

      </div>

      <!-- FOOTER -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>

    </div>
  </div>
</div>

<!-- VISOR PDF (fuera del modal) -->
<div id="visorPDF" style="
    display:none;
    position:fixed;
    top:0;
    right:0;
    width:50%;
    height:100%;
    background:#fff;
    border-left:3px solid #0d6efd;
    z-index:99999;
    box-shadow:-4px 0 10px rgba(0,0,0,0.2);
">
    <div style="padding:10px; background:#0d6efd; color:white; font-weight:bold;">
        Documento PDF
        <button id="cerrarVisorPDF" 
                style="float:right; background:none; border:none; color:white; font-size:22px;">
            ×
        </button>
    </div>

    <iframe id="iframePDF" src="" style="width:100%; height:95%; border:none;"></iframe>
</div>

<!-- VISOR DE FOTO (fuera del modal) -->
<div id="visorFoto" 
     style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; 
            background:rgba(0,0,0,0.8); z-index:99999; text-align:center; padding-top:40px;">
    
    <img id="imgVisor" src="" 
         style="max-width:90%; max-height:85%; border:4px solid white;">

    <button id="cerrarVisorFoto" 
            style="position:absolute; top:10px; right:20px; 
                   background:none; border:none; color:white; font-size:40px;">
        ×
    </button>
</div>
