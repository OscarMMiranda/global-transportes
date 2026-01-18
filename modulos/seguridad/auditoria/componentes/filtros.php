<?php
// archivo: /modulos/seguridad/auditoria/componentes/filtros.php
?>

<div class="row g-3">

    <!-- Usuario -->
    <div class="col-md-3">
        <label class="form-label">Usuario</label>
        <select id="filtro_usuario" class="form-select">
            <option value="">Todos</option>
        </select>
    </div>

    <!-- Módulo -->
    <div class="col-md-3">
        <label class="form-label">Módulo</label>
        <select id="filtro_modulo" class="form-select">
            <option value="">Todos</option>
        </select>
    </div>

    <!-- Acción -->
    <div class="col-md-3">
        <label class="form-label">Acción</label>
        <select id="filtro_accion" class="form-select">
            <option value="">Todas</option>
            <option value="crear">Crear</option>
            <option value="editar">Editar</option>
            <option value="eliminar">Eliminar</option>
            <option value="login">Login</option>
            <option value="logout">Logout</option>
            <option value="cambiar_rol">Cambiar Rol</option>
        </select>
    </div>

    <!-- Fecha desde -->
    <div class="col-md-3">
        <label class="form-label">Fecha desde</label>
        <input type="date" id="filtro_desde" class="form-control">
    </div>

    <!-- Fecha hasta -->
    <div class="col-md-3">
        <label class="form-label">Fecha hasta</label>
        <input type="date" id="filtro_hasta" class="form-control">
    </div>

    <!-- Botón buscar -->
    <div class="col-md-3 d-flex align-items-end">
        <button id="btnBuscar" class="btn btn-primary w-100">Buscar</button>
    </div>

    <!-- Botón limpiar -->
    <div class="col-md-3 d-flex align-items-end">
        <button id="btnLimpiar" class="btn btn-secondary w-100">Limpiar</button>
    </div>

</div>