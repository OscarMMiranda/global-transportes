<?php
// archivo: /modulos/orden_trabajo/views/partials/tabla_activa.php
?>

<div class="table-responsive" style="max-height: 500px; overflow-y: auto;">

    <table id="tablaOrdenesActivas"
           class="table table-sm table-hover table-striped align-middle shadow-sm tabla-ordenes">

        <thead class="table-dark sticky-top">
            <tr class="text-center">

                <th style="width: 110px;">Número OT</th>
                <th style="width: 110px;">Fecha</th>
                <th style="width: 180px;">Cliente</th>
                <th style="width: 110px;">O.C.</th>
                <th style="width: 130px;">Tipo OT</th>
                <th style="width: 150px;">Empresa</th>
                <th style="width: 110px;">Estado</th>
                <th style="width: 120px;" class="text-center">Acciones</th>

            </tr>
        </thead>

        <tbody id="tbodyActivas">
            <!-- AJAX insertará aquí las filas -->
        </tbody>

    </table>

</div>
