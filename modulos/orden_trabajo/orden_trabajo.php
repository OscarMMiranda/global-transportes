<?php
session_start();
require_once '../../includes/conexion.php';
require_once '../../includes/header_erp.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Listado de Órdenes de Trabajo</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
  <!-- Tu archivo de estilos personalizado -->
  <link rel="stylesheet" href="../../css/orden_trabajo.css">
  <style>
    /* Ajustes adicionales, si es necesario, sin modificar tu archivo orden_trabajo.css */
    .tabla-ordenes th, .tabla-ordenes td {
      vertical-align: middle;
    }
    .btn-lg {
      margin: 0.5rem;
    }
  </style>
</head>
<body>
  <div class="container mt-4">
    <h2 class="text-center text-primary mb-4">📋 Listado de Órdenes de Trabajo</h2>
    
    <!-- Botones superiores -->
    <div class="text-center mb-3">
      <a href="crear_orden.php" class="btn btn-primary btn-lg">➕ Crear Nueva Orden</a>
      <a href="anular_orden.php" class="btn btn-warning btn-lg">🚫 Anular Orden</a>
      <a href="eliminar_orden.php" class="btn btn-danger btn-lg">🗑️ Eliminar Orden</a>
    </div>
    
    <!-- Tabla de Órdenes Activas -->
    <div class="table-responsive mt-4">
      <table id="tablaOrdenesActivas" class="table table-bordered table-hover shadow-sm tabla-ordenes">
        <thead class="table-dark">
          <tr class="text-center">
            <th>Número OT</th>
            <th>Fecha</th>
            <th>Cliente</th>
            <th>O.C.</th>
            <th>Tipo OT</th>
            <th>Empresa</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php
          // Consulta de órdenes activas (excluyendo anuladas y eliminadas)
          $sqlOrdenes = "SELECT ot.id, ot.numero_ot, ot.fecha, ot.oc_cliente, ot.tipo_ot_id, 
                                c.nombre AS cliente_nombre, 
                                tot.nombre AS tipo_ot_nombre, 
                                e.razon_social AS empresa_nombre, 
                                eo.nombre AS estado_nombre
                         FROM ordenes_trabajo ot
                         LEFT JOIN clientes c ON ot.cliente_id = c.id
                         LEFT JOIN tipo_ot tot ON ot.tipo_ot_id = tot.id
                         LEFT JOIN empresa e ON ot.empresa_id = e.id
                         LEFT JOIN estado_orden_trabajo eo ON ot.estado_ot = eo.id
                         WHERE eo.id NOT IN (7,8)
                         ORDER BY CAST(SUBSTRING_INDEX(numero_ot, '-', -1) AS UNSIGNED) DESC, 
                                  CAST(SUBSTRING_INDEX(numero_ot, '-', 1) AS UNSIGNED) DESC";
          $resultOrdenes = $conn->query($sqlOrdenes);
          if ($resultOrdenes && $resultOrdenes->num_rows > 0) {
              while ($orden = $resultOrdenes->fetch_assoc()) { ?>
                <tr class="text-center">
                  <td class="fw-bold"><?= htmlspecialchars($orden['numero_ot']) ?></td>
                  <td><?= htmlspecialchars($orden['fecha']) ?></td>
                  <td><?= htmlspecialchars($orden['cliente_nombre']) ?></td>
                  <td><?= htmlspecialchars($orden['oc_cliente']) ?></td>
                  <td><?= htmlspecialchars($orden['tipo_ot_nombre']) ?></td>
                  <td><?= htmlspecialchars($orden['empresa_nombre']) ?></td>
                  <td class="fw-bold"><?= htmlspecialchars($orden['estado_nombre']) ?></td>
                  <td>
                    <a href="editar_orden.php?numero_ot=<?= urlencode($orden['numero_ot']) ?>" class="btn btn-warning btn-sm">✏️ Editar</a>
                    <a href="detalle_orden.php?orden_trabajo_id=<?= $orden['id'] ?>&tipo_ot_id=<?= $orden['tipo_ot_id'] ?>" class="btn btn-primary btn-sm">🚚 Registrar Viaje</a>
                    <!-- Botón Visualizar que dirige a la vista completa de la orden -->
                    <a href="ver_orden.php?orden_trabajo_id=<?= $orden['id'] ?>" class="btn btn-info btn-sm">👁️ Visualizar</a>
                  </td>
                </tr>
              <?php }
          } else {
              echo '<tr class="text-center"><td colspan="8">No hay órdenes activas.</td></tr>';
          }
          ?>
        </tbody>
      </table>
    </div>
    
    <!-- Tabla de Órdenes Anuladas -->
    <?php
    $sqlAnuladas = "SELECT ot.id, ot.numero_ot, ot.fecha, ot.oc_cliente, 
                           c.nombre AS cliente_nombre, 
                           tot.nombre AS tipo_ot_nombre, 
                           e.razon_social AS empresa_nombre, 
                           eo.nombre AS estado_nombre
                    FROM ordenes_trabajo ot
                    LEFT JOIN clientes c ON ot.cliente_id = c.id
                    LEFT JOIN tipo_ot tot ON ot.tipo_ot_id = tot.id
                    LEFT JOIN empresa e ON ot.empresa_id = e.id
                    LEFT JOIN estado_orden_trabajo eo ON ot.estado_ot = eo.id
                    WHERE eo.id = 7
                    ORDER BY CAST(SUBSTRING_INDEX(numero_ot, '-', -1) AS UNSIGNED) DESC, 
                             CAST(SUBSTRING_INDEX(numero_ot, '-', 1) AS UNSIGNED) DESC";
    $resultAnuladas = $conn->query($sqlAnuladas);
    if ($resultAnuladas && $resultAnuladas->num_rows > 0) { ?>
      <h3 class="text-center text-warning mt-5">🚫 Órdenes Anuladas</h3>
      <div class="table-responsive">
        <table id="tablaOrdenesAnuladas" class="table table-bordered table-hover shadow-sm tabla-ordenes">
          <thead class="table-warning">
            <tr class="text-center">
              <th>Número OT</th>
              <th>Fecha</th>
              <th>Cliente</th>
              <th>O.C.</th>
              <th>Tipo OT</th>
              <th>Empresa</th>
              <th>Estado</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($orden = $resultAnuladas->fetch_assoc()) { ?>
              <tr class="text-center">
                <td class="fw-bold"><?= htmlspecialchars($orden['numero_ot']) ?></td>
                <td><?= htmlspecialchars($orden['fecha']) ?></td>
                <td><?= htmlspecialchars($orden['cliente_nombre']) ?></td>
                <td><?= htmlspecialchars($orden['oc_cliente']) ?></td>
                <td><?= htmlspecialchars($orden['tipo_ot_nombre']) ?></td>
                <td><?= htmlspecialchars($orden['empresa_nombre']) ?></td>
                <td class="fw-bold text-warning"><?= htmlspecialchars($orden['estado_nombre']) ?></td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    <?php } ?>
    
    <!-- Tabla de Órdenes Eliminadas -->
    <?php
    $sqlEliminadas = "SELECT ot.id, ot.numero_ot, ot.fecha, ot.oc_cliente, 
                             c.nombre AS cliente_nombre, 
                             tot.nombre AS tipo_ot_nombre, 
                             e.razon_social AS empresa_nombre, 
                             eo.nombre AS estado_nombre
                      FROM ordenes_trabajo ot
                      LEFT JOIN clientes c ON ot.cliente_id = c.id
                      LEFT JOIN tipo_ot tot ON ot.tipo_ot_id = tot.id
                      LEFT JOIN empresa e ON ot.empresa_id = e.id
                      LEFT JOIN estado_orden_trabajo eo ON ot.estado_ot = eo.id
                      WHERE eo.id = 8
                      ORDER BY CAST(SUBSTRING_INDEX(numero_ot, '-', -1) AS UNSIGNED) DESC, 
                               CAST(SUBSTRING_INDEX(numero_ot, '-', 1) AS UNSIGNED) DESC";
    $resultEliminadas = $conn->query($sqlEliminadas);
    if ($resultEliminadas && $resultEliminadas->num_rows > 0) { ?>
      <h3 class="text-center text-danger mt-5">🚫 Órdenes Eliminadas</h3>
      <div class="table-responsive">
        <table id="tablaOrdenesEliminadas" class="table table-bordered table-hover shadow-sm tabla-ordenes">
          <thead class="table-danger">
            <tr class="text-center">
              <th>Número OT</th>
              <th>Fecha</th>
              <th>Cliente</th>
              <th>O.C.</th>
              <th>Tipo OT</th>
              <th>Empresa</th>
              <th>Estado</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($orden = $resultEliminadas->fetch_assoc()) { ?>
              <tr class="text-center">
                <td class="fw-bold"><?= htmlspecialchars($orden['numero_ot']) ?></td>
                <td><?= htmlspecialchars($orden['fecha']) ?></td>
                <td><?= htmlspecialchars($orden['cliente_nombre']) ?></td>
                <td><?= htmlspecialchars($orden['oc_cliente']) ?></td>
                <td><?= htmlspecialchars($orden['tipo_ot_nombre']) ?></td>
                <td><?= htmlspecialchars($orden['empresa_nombre']) ?></td>
                <td class="fw-bold text-danger"><?= htmlspecialchars($orden['estado_nombre']) ?></td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    <?php } ?>
  </div>
  
  <!-- Scripts: jQuery, DataTables y Bootstrap JS -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    $(document).ready(function(){
      $('#tablaOrdenesActivas').DataTable({
        "language": {
          "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
        },
        "order": [] // Mantiene el orden de la consulta
      });
      $('#tablaOrdenesAnuladas').DataTable({
        "language": {
          "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
        },
        "order": []
      });
      $('#tablaOrdenesEliminadas').DataTable({
        "language": {
          "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
        },
        "order": []
      });
    });
  </script>
</body>
</html>
