<?php
// modulos/vehiculos/vistas/tabla_activos.php
// Tabla de Vehículos Activos

// Si no existe la variable o es false, mostrar mensaje y detener
	if (! isset($vehiculos_activos) || $vehiculos_activos === false) {
		echo '<div class="alert alert-warning">';
		echo 'No se pudo cargar la lista de vehículos activos.';
		echo '</div>';
		return;
		}
?>

<div class="table-responsive">
	<table id="tabla-activos" class="table table-striped table-hover table-bordered ">
		<thead class="table-dark text-center align-middle">
    		<tr>
      			<th scope="col">ID</th>
      			<th scope="col">Placa</th>
      			<th scope="col">Marca</th>
      			<th scope="col">Modelo</th>
      			<th scope="col">Tipo</th>
      			<th scope="col">Año</th>
      			<th scope="col">Empresa</th>
      			<th scope="col">Acciones</th>
    		</tr>
  		</thead>
        <tbody>
            <?php while ($row = $vehiculos_activos->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['placa'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($row['marca'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($row['modelo'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($row['tipo'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($row['anio'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($row['empresa'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <!-- Ver -->
                        <a href="index.php?action=view&id=<?= $row['id'] ?>"
                        	class="btn btn-info btn-sm">
                        	<i class="fas fa-eye"></i> Ver
                        </a>

                        <!-- Editar -->
                        <a href="index.php?action=edit&id=<?= $row['id'] ?>" 
							class="btn btn-warning btn-sm">
  							<i class="fas fa-edit"></i> Editar
						</a>

                        <!-- Eliminar -->
                        <a href="vistas/formulario_eliminar.php?id=<?= $row['id'] ?>" 
   class="btn btn-danger btn-sm">
    <i class="fas fa-trash"></i> Eliminar
</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>