<?php
// archivo: /modulos/documentos_conductores/componentes/tabla_documentos_conductores.php
?>  


<div class="card">
    <div class="card-body">

        <!-- Nombre del conductor (correctamente ubicado) -->
        <p class="fw-bold mb-1">
            Conductor: 
            <span id="conductor_nombre">
                <?= htmlspecialchars($conductor['nombres'] . ' ' . $conductor['apellidos']) ?>
            </span>
        </p>

        <table id="tablaConductores" class="table table-striped table-bordered">

            <thead>
                <tr>
                    <th>DNI</th>
                    <th>Nombre</th>
                    <th>Documentos</th>
                    <th>Por vencer</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        Cargando datos del sistema…
                    </td>
                </tr>
            </tbody>
        </table>

    </div>
</div>

