<?php if (!empty($ordenesAnuladas)) { ?>
  <h3 class="text-center text-warning mt-5">🚫 Órdenes Anuladas</h3>
  <div class="table-responsive">
    <table id="tablaOrdenesAnuladas" class="table table-bordered table-hover shadow-sm tabla-ordenes">
      <thead class="table-warning">
        <tr class="text-center">
          <th>Número OT</th><th>Fecha</th><th>Cliente</th><th>O.C.</th>
          <th>Tipo OT</th><th>Empresa</th><th>Estado</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($ordenesAnuladas as $orden) { ?>
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