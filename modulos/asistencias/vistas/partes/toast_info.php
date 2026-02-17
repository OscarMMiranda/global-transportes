<?php
    // archivo: modulos/asistencias/vistas/partes/toast_warning.php
    // Vista para mostrar un toast de advertencia al actualizar una asistencia  
?>

<div id="toastInfo"
     class="toast position-fixed bottom-0 end-0 m-4 shadow-lg"
     role="alert"
     aria-live="assertive"
     aria-atomic="true"
     data-bs-delay="3500"
     data-bs-autohide="true"
     style="z-index:9999; min-width: 320px;">

  <div class="toast-header bg-primary text-white">
    <i class="fa-solid fa-circle-info me-2"></i>
    <strong class="me-auto">Información</strong>
    <small>Hace un momento</small>
    <button type="button" class="btn-close btn-close-white ms-2 mb-1"
            data-bs-dismiss="toast" aria-label="Close"></button>
  </div>

  <div class="toast-body">
    Operación realizada correctamente.
  </div>
</div>
