<?php
    // archivo: modulos/asistencias/vistas/partes/toast_error.php
    // Vista para mostrar un toast de error al actualizar una asistencia
?>

<div id="toastError"
     class="toast position-fixed bottom-0 end-0 m-4 shadow-lg"
     role="alert"
     aria-live="assertive"
     aria-atomic="true"
     data-bs-delay="5000"
     data-bs-autohide="false"
     style="z-index:9999; min-width: 320px;">

  <div class="toast-header bg-danger text-white">
    <i class="fa-solid fa-circle-xmark me-2"></i>
    <strong class="me-auto">Error</strong>
    <small>Hace un momento</small>
    <button type="button" class="btn-close btn-close-white ms-2 mb-1"
            data-bs-dismiss="toast" aria-label="Close"></button>
  </div>

  <div class="toast-body">
    Ocurrió un error al procesar la solicitud.
  </div>
</div>
