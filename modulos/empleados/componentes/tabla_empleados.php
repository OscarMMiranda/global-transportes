<?php
// /modulos/empleados/componentes/tabla_empleados.php
?>

<div class="card shadow-sm border-0">

    <div class="card-body">

        <div class="table-responsive">

            <table id="<?= $tablaId ?>"
                   class="table table-hover align-middle w-100">

                <thead class="bg-dark text-white text-center">
                    <tr>
                        <th style="width:70px;">ID</th>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th style="width:110px;">DNI</th>
                        <th>Empresa</th>
                        <th>Roles</th>
                        <th style="width:160px;">Acciones</th>
                    </tr>
                </thead>

                <tbody class="text-center"></tbody>

            </table>

        </div>

    </div>
</div>
