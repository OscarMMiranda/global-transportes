<?php
	// archivo : /modulos/asistencias/vacaciones/componentes/header/header_actions.php
// ============================================================
// COMPONENTE: ACCIONES DEL SUBMÓDULO VACACIONES
// ============================================================
?>

<div class="d-flex gap-2 mb-3">

    <!-- Botón: Nueva solicitud de vacaciones -->
    <button class="btn btn-primary btn-sm" id="btnNuevaSolicitud">
        <i class="fa-solid fa-plane"></i> Nueva solicitud
    </button>

    <button class="btn btn-success btn-sm btnAprobarSolicitud" data-id="<?= $fila['id'] ?>">
        <i class="fa-solid fa-check"></i>Aprobar solicitud
    </button>


    <!-- Botón: Registrar compra de vacaciones -->
    <button class="btn btn-warning btn-sm" id="btnCompraVacaciones">
        <i class="fa-solid fa-money-bill-wave"></i> Compra de días
    </button>

    <!-- Botón: Ver movimientos -->
    <button class="btn btn-secondary btn-sm" id="btnVerMovimientos">
        <i class="fa-solid fa-list"></i> Movimientos
    </button>

    <!-- Botón: Actualizar -->
    <button class="btn btn-outline-dark btn-sm" id="btnActualizarVacaciones">
        <i class="fa-solid fa-rotate"></i> Actualizar
    </button>

</div>
