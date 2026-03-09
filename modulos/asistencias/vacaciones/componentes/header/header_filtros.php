<?php
// archivo: /modulos/asistencias/vacaciones/header/header_filtros.php
// ============================================================
// COMPONENTE: FILTROS DEL SUBMÓDULO VACACIONES
// ============================================================
?>

<div class="card mb-3">
    <div class="card-body py-2">

        <div class="row g-2 align-items-end">

            <!-- Filtro: Empresa -->
            <div class="col-md-3">
                <label class="form-label mb-0">Empresa</label>
                <select id="filtroEmpresa" class="form-select form-select-sm">
                    <option value="">Todas</option>
                </select>
            </div>

            <!-- Filtro: Conductor -->
            <div class="col-md-4">
                <label class="form-label mb-0">Conductor</label>
                <select id="filtroConductor" class="form-select form-select-sm">
                    <option value="">Todos</option>
                </select>
            </div>

            <!-- Filtro: Año -->
            <div class="col-md-2">
                <label class="form-label mb-0">Año</label>
                <select id="filtroAnio" class="form-select form-select-sm">
                    <option value="">Todos</option>
                </select>
            </div>

            <!-- Botón aplicar -->
            <div class="col-md-2">
                <button id="btnAplicarFiltros" class="btn btn-primary btn-sm w-100">
                    <i class="fa-solid fa-filter"></i> Aplicar
                </button>
            </div>

        </div>

    </div>
</div>
