<?php
// archivo: /modulos/conductores/componentes/tabla_conductores.php
?>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-body p-0">

        <!-- Loader del módulo Conductores -->
        <div class="loader-conductores text-center py-4 d-none" id="loader-<?= $tablaId ?>">
            <div class="spinner-border text-primary"></div>
            <p class="mt-2 text-muted">Cargando datos...</p>
        </div>

        <!-- Tabla -->
        <div class="table-responsive">
            <table id="<?= $tablaId ?>" class="table table-hover align-middle mb-0 tabla-conductores">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px">#</th>
                        <th>Apellidos y Nombres</th>
                        <th style="width: 120px">DNI</th>
                        <th style="width: 120px">Licencia</th>
                        <th style="width: 130px">Teléfono</th>
                        <th style="width: 100px">Estado</th>
                        <th class="text-center" style="width: 120px">Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

    </div>
</div>