<?php
// archivo: /modulos/usuarios/componentes/tabla.php
?>

<div class="card shadow-sm border-0">
    <div class="card-body">

        <table id="tablaUsuarios" class="table table-hover table-striped table-bordered align-middle w-100">
            <thead class="table-primary">
                <tr>
                    <th>ID</th>
                    <th>Nombre Completo</th>
                    <!-- <th>Apellido</th> -->
                    <th>Usuario</th>
                    <!-- <th>Correo</th> -->
                    <th class="text-center">Rol</th>
                    <!-- <th class="text-center">Creado</th> -->
                    <th class="text-center">Estado</th>
                    <th class="text-center" style="width: 140px;">Acciones</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td colspan="8" class="text-center py-4">
                        <div class="spinner-border text-primary" role="status"></div>
                        <div class="text-muted mt-2">Cargando usuarios...</div>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>
</div>