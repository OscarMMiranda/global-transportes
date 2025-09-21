<?php
// archivo: /modulos/orden_trabajo/views/partials/tabla_activa.php
?>

<div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
  <table id="tablaOrdenesActivas" class="table table-sm table-hover table-striped align-middle shadow-sm tabla-ordenes">
    <thead class="table-dark sticky-top">
      <tr class="text-center">
        <th>Número OT</th>
        <th>Fecha</th>
        <th>Cliente</th>
        <th>O.C.</th>
        <th>Tipo OT</th>
        <th>Empresa</th>
        <th>Estado</th>
        <th class="text-center">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($ordenesActivas)): ?>
        <?php foreach ($ordenesActivas as $orden): ?>
          <tr class="text-center">
            <td class="fw-bold"><?php echo isset($orden['numero_ot']) ? htmlspecialchars($orden['numero_ot']) : '—'; ?></td>
            <td><?php echo isset($orden['fecha']) ? htmlspecialchars($orden['fecha']) : '—'; ?></td>
            <td><?php echo isset($orden['cliente_nombre']) ? htmlspecialchars($orden['cliente_nombre']) : '<span class="text-muted">—</span>'; ?></td>
            <td><?php echo isset($orden['oc_cliente']) ? htmlspecialchars($orden['oc_cliente']) : '—'; ?></td>
            <td>
              <?php
                if (isset($orden['tipo_ot_nombre'])) {
                  if ($orden['tipo_ot_nombre'] === 'Exportación')  ;
                  elseif ($orden['tipo_ot_nombre'] === 'Importación') ;
                  elseif ($orden['tipo_ot_nombre'] === 'Nacional') ;
                  echo htmlspecialchars($orden['tipo_ot_nombre']);
                } else {
                  echo '<span class="text-muted">—</span>';
                }
              ?>
            </td>
            <td><?php echo isset($orden['empresa_nombre']) ? htmlspecialchars($orden['empresa_nombre']) : '—'; ?></td>
            <td class="estado-pendiente">
              <?php echo isset($orden['estado_nombre']) ? htmlspecialchars($orden['estado_nombre']) : '—'; ?>
            </td>
            <td class="text-center">
  
	<div class="btn-group">
    	<button 
      		type="button" 
      		class="btn btn-sm btn-secondary dropdown-toggle" 
      		data-bs-toggle="dropdown" 
      		aria-expanded="false"
      		aria-label="Acciones de la orden <?= $orden['id']; ?>"
    	>
      		<i class="fa-solid fa-ellipsis-vertical"></i>
    	</button>
    <ul class="dropdown-menu dropdown-menu-end shadow">
      	<li>
        	<a class="dropdown-item" href="/modulos/orden_trabajo/views/ver.php?orden_trabajo_id=<?= $orden['id']; ?>">
				<i class="fa-solid fa-folder-open me-2 text-info"></i> Ver detalles
			</a>  
		</li>
      	<li>
			<a class="dropdown-item" href="/modulos/orden_trabajo/views/edit.php?numero_ot=<?= urlencode($orden['numero_ot']); ?>">
				<i class="fa-solid fa-pen-to-square me-2 text-warning"></i> Editar
			</a>
        	<!-- <a class="dropdown-item" href="../views/edit.php?numero_ot=<?= urlencode($orden['numero_ot']); ?>"> -->
          		
        	<!-- </a> -->
      	</li>
      	<li>
        	<a class="dropdown-item" href="../views/detalle.php?orden_trabajo_id=<?= $orden['id']; ?>&tipo_ot_id=<?= $orden['tipo_ot_id']; ?>">
          		<i class="fa-solid fa-truck-fast me-2 text-success"></i> Detalle
        	</a>
      	</li>
      	<li><hr class="dropdown-divider"></li>
      	<li>
        	<a class="dropdown-item text-danger" 
           		href="../views/eliminar.php?orden_trabajo_id=<?= $orden['id']; ?>" 
           		onclick="return confirm('¿Eliminar esta orden?');">
          		<i class="fa-solid fa-trash me-2"></i> Eliminar
        	</a>
      	</li>
    </ul>
  </div>
</td>

          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="8" class="text-center text-muted">No hay órdenes activas registradas.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>