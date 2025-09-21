<?php if (!empty($ordenesEliminadas)) { ?>
  <h3 class="text-center text-danger mt-5">🚫 Órdenes Eliminadas</h3>
  <div class="table-responsive">
    <table id="tablaOrdenesEliminadas" class="table table-bordered table-hover shadow-sm tabla-ordenes">
      <thead class="table-danger">
        <tr class="text-center">
          <th>Número OT</th><th>Fecha</th><th>Cliente</th><th>O.C.</th>
          <th>Tipo OT</th><th>Empresa</th><th>Estado</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($ordenesEliminadas as $orden) { ?>
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