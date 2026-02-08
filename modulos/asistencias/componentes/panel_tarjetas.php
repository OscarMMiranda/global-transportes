<?php
// archivo: /modulos/asistencias/componentes/panel_tarjetas.php
?>

<div class="panel-grid">

    <!-- Registrar asistencia -->
    <button class="card card-btn" id="btnAbrirRegistrarAsistencia">
        <h3>Registrar asistencia</h3>
        <small>Nuevo registro diario</small>
    </button>

    <!-- Modificar asistencia -->
    <button class="card card-btn" id="btnAbrirModificarAsistencia">
        <h3>Modificar asistencia</h3>
        <small>Editar registros existentes</small>
    </button>

    <!-- Reporte diario (vista completa, NO modal) -->
    <button class="card card-btn"
            onclick="location.href='/modulos/asistencias/vistas/reporte_diario.php'">
        <h3>Reporte diario</h3>
        <small>Resumen por fecha</small>
    </button>

    <!-- Reporte mensual -->
    <button class="card card-btn" onclick="location.href='matriz.php'">
        <h3>Reporte mensual</h3>
        <small>Matriz por conductor</small>
    </button>

    <!-- Vacaciones -->
    <button class="card card-btn" id="btnAbrirVacaciones">
        <h3>Vacaciones</h3>
        <small>Registrar rango</small>
    </button>

    <!-- Permisos -->
    <button class="card card-btn" id="btnAbrirPermisos">
        <h3>Permisos</h3>
        <small>Con goce / sin goce</small>
    </button>

</div>
