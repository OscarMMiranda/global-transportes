<?php
// archivo: /modulos/infracciones/eliminadas.php

session_start();

// SOLO ADMIN PUEDE VER ESTE MÓDULO
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 1) {
    die("Acceso denegado");
}

// Configuración global
require_once __DIR__ . '/../../includes/config.php';

// Controlador
require_once __DIR__ . '/controllers/InfraccionesController.php';
$controller = new InfraccionesController($GLOBALS['db']);

// LISTAR SOLO INACTIVAS
$lista = $controller->listar(["estado" => "Inactivo"]);

// Head del módulo
include __DIR__ . '/componentes/head.php';
?>

<body class="bg-light">

<div class="container-fluid py-3">

    <?php include __DIR__ . '/componentes/menu.php'; ?>

    <h4 class="mb-3">
        Infracciones Eliminadas
    </h4>

    <div class="card">
        <div class="card-body table-responsive">

            <table id="tablaEliminadas" class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Código</th>
                        <th>Descripción</th>
                        <th>Gravedad</th>
                        <th>Monto</th>
                        <th>Puntos</th>
                        <th>Entidad</th>
                        <th>Eliminado Por</th>
                        <th>ID Usuario</th>
                        <th>Fecha Eliminación</th>
                        <th>IP Eliminación</th>
                        <th>Host</th>
                        <th>Navegador</th>
                        <th>Motivo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach($lista as $row){ ?>
                        <tr>

                            <td class="text-center"><?php echo $row['codigo']; ?></td>
                            <td><?php echo $row['descripcion']; ?></td>
                            <td class="text-center"><?php echo $row['gravedad']; ?></td>
                            <td class="text-end"><?php echo number_format($row['monto_base'], 2); ?></td>
                            <td class="text-center"><?php echo $row['puntos']; ?></td>
                            <td><?php echo $row['entidad_nombre']; ?></td>

                            <td><?php echo $row['eliminado_por']; ?></td>
                            <td><?php echo $row['eliminado_por_id']; ?></td>
                            <td><?php echo $row['fecha_eliminacion']; ?></td>
                            <td><?php echo $row['ip_eliminacion']; ?></td>
                            <td><?php echo $row['host_eliminacion']; ?></td>
                            <td><?php echo $row['user_agent_eliminacion']; ?></td>
                            <td><?php echo $row['motivo_eliminacion']; ?></td>

                            <td class="text-center">
                                <button class="btn btn-success btn-sm"
                                        onclick="reactivarInfraccion('<?php echo $row['id']; ?>')">
                                    Recuperar
                                </button>
                            </td>

                        </tr>
                    <?php } ?>
                </tbody>

            </table>

        </div>
    </div>

</div>

<!-- ============================================================
     SCRIPTS DESDE CDN (PROFESIONAL)
     ============================================================ -->

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Scripts del módulo -->
<script src="assets/acciones.js"></script>
<script src="js/infracciones.js"></script>

<script>
$(document).ready(function(){
    $("#tablaEliminadas").DataTable({
        pageLength: 25,
        order: [[0, "asc"]],
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        }
    });
});
</script>

</body>
</html>
