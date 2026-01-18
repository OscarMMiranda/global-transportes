<?php
// archivo: /modulos/usuarios/componentes/tabla.php
// --------------------------------------------------------------
// Componente: Tabla del módulo Usuarios
// Responsable: UI de listado (DataTables)
// No contiene lógica de negocio
// --------------------------------------------------------------
?>

<div id="usuarios-tabla" class="card shadow-sm border-0">
    <div class="card-body p-3">

        <table id="tablaUsuarios"
               class="table table-hover table-striped align-middle w-100 tabla-erp"
               role="table">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre Completo</th>
                    <th>Usuario</th>
                    <th class="text-center">Rol</th>
                    <th class="text-center">Estado</th>
                    <th class="text-center" style="width: 140px;">Acciones</th>
                </tr>
            </thead>

            <tbody id="tbodyUsuarios">
                <tr>
                    <td colspan="6" class="text-center py-4">
                        <div class="spinner-border text-primary" role="status"></div>
                        <div class="text-muted mt-2">Cargando usuarios...</div>
                    </td>
                </tr>
            </tbody>

        </table>

    </div>
</div>