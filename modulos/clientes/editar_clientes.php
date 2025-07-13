<?php
	// archivo : editar_clientes.php

	require_once '../../includes/conexion.php';

	require_once INCLUDES_PATH . '/header.php';
	// require_once '../../includes/header.php';

	$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
	if (!$id) {
    	echo "<p class='mensaje-sistema error'>ID de cliente inválido.</p>";
    	include '../../includes/footer.php';
    	exit;
	}

	$query = 
		"SELECT * 
		FROM clientes 
		WHERE id = ?";
	$stmt = $conn->prepare($query);
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$resultado = $stmt->get_result();

	if (!$resultado || $resultado->num_rows === 0) {
    	echo "<p class='mensaje-sistema error'>Cliente no encontrado.</p>";
    	include '../../includes/footer.php';
    	exit;
	}

	$cliente = $resultado->fetch_assoc();

	// Para precarga de ubicaciones
	$dptos = $conn->query(
		"SELECT * 
		FROM departamentos 
		ORDER BY nombre");
	$provs = $conn->query(
		"SELECT * 
		FROM provincias 
		WHERE departamento_id = {$cliente['departamento_id']} 
		ORDER BY nombre");
	$dists = $conn->query(
		"SELECT * 
		FROM distritos 
		WHERE provincia_id = {$cliente['provincia_id']} 
		ORDER BY nombre");
	?>

<link rel="stylesheet" href="../../css/estilo.css">

<main class="container py-4">
    <h2 class="titulo-pagina text-center mb-4">✏️ Editar Cliente</h2>

	<!-- ⚡️ Aquí van las alertas según GET -->
    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'ok'): ?>
        <p class="mensaje-sistema exito">Cliente actualizado correctamente.</p>
    <?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'error'): ?>
        <p class="mensaje-sistema error">Error al actualizar el cliente.</p>
    <?php endif; ?>

    <div class="login-form">
        <form 
			action="actualizar_clientes.php" 
			method="POST" 
			class="formulario">
            <input 
				type="hidden" 
				name="id" 
				value="<?= htmlspecialchars($cliente['id']) ?>">


		<div class="row gx-3 gy-4">
            <div class="col-md-6">
            	<label for="nombre" 
					class="form-label">
					Nombre 
					<span class="text-danger">*</span>
				</label>
            	<input 
					type="text" 
					id="nombre" 
					name="nombre" 
					class="form-control"
                	required value="<?= htmlspecialchars($cliente['nombre']) 
				?>">
          	</div>

            <div class="col-md-6">
            	<label for="ruc" 
					class="form-label">
					RUC
				</label>
               	<input 
					type="text" 
					id="ruc" 
					name="ruc" 
					class="form-control"
                   	pattern="\d{11}" 
				   	maxlength="11"
                   	value="<?= htmlspecialchars($cliente['ruc']) 
				?>">
			</div>

            <div class="col-md-6">
				<label for="direccion" 
					class="form-label">
					Dirección
				</label>
            	<input 
					type="text" 
					id="direccion" 
					name="direccion" 
					class="form-control"
                   	value="<?= htmlspecialchars($cliente['direccion']) ?>">
         
			</div>

            <div class="col-md-6">
                <label for="telefono"
					class="form-label">
					Teléfono:
				</label>
                <input 
					type="text" 
					id="telefono"
					name="telefono" 
					class="form-control"
					pattern="\d+"
					maxlength="15"
					value="<?= htmlspecialchars($cliente['telefono']) ?>">
            </div>

            <div class="col-md-6">
                <label for="correo"
					class="form-label">
					Correo:
				</label>
                <input 
					type="email"
					id="correo"
					name="correo"
					class="form-control"
					value="<?= htmlspecialchars($cliente['correo']) ?>">
            </div>

            <div class="col-md-6">
                <label for="departamento"
					class="form-label">
					Departamento:
					</label>
                <select 
					id="departamento" 
					name="departamento_id"
					class="form-select"
					required>
                    <option value="">Seleccione</option>
                    <?php while ($row = $dptos->fetch_assoc()):  ?>
                        <option value="<?= $row['id'] ?>" <?= $row['id'] == $cliente['departamento_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($row['nombre']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label for="provincia"
					class="form-label">
					Provincia:
				</label>
                <select 
					id="provincia" 
					name="provincia_id"
					class="form-select"
					required>

					data-selected="<?= $cliente['provincia_id'] ?>">
  					<option value="">Seleccione</option>
  					
                </select>
            </div>

            <div class="col-md-6">
                <label for="distrito"
					class="form-label">
					Distrito:</label>
                <select 
					id="distrito" 
					name="distrito_id"
					class="form-select" 
					required
					data-selected="<?= $cliente['distrito_id'] ?>"
					>
                    
                </select>
            </div>

            <div class="mt-4 text-end" >
                <button 
					type="submit" 
					class="btn btn-outline-success btn-lg"
					style="width: 250px; height: 45px;"
				>
					 <i class="fas fa-save me-1"></i>
					<!-- 💾  -->
					Actualizar
				</button>

                <a href="clientes.php" 
					class="btn btn-outline-primary btn-lg"
					style="width: 250px; height: 45px;"
					>
					<i class="fas fa-times"></i>
					Cancelar
				</a>
                <a href="../erp_dashboard.php" 
					class="btn btn-outline-secondary ms-2 btn-lg"
					style="width: 250px; height: 45px;"
					>
					<i class="fas fa-arrow-left"></i>
					<!-- ⬅  -->
					Volver al Dashboard
				</a>
            </div>
        
		</div>
		</form>
    </div>
</main>

<!-- jQuery primero -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
  const BASE_URL = '<?= rtrim($_SERVER['SCRIPT_NAME'], basename($_SERVER['SCRIPT_NAME'])) ?>';
</script>



<?php
  // Para que BASE_URL apunte a /modulos/ o carpeta padre de tu script
  $baseUrl = dirname(dirname($_SERVER['SCRIPT_NAME'])) . '/';
?>
<script>
  window.BASE_URL = '<?= $baseUrl ?>';
</script>

<!-- Ahora sí carga tu JS -->
<script src="<?= $baseUrl ?>js/ubigeo.js"></script>




<!-- Luego ubigeo.js -->
<script src="../../js/ubigeo.js"></script>



<!-- Precarga para que ubigeo.js actualice correctamente si cambia el departamento -->


<script>
	document.addEventListener('DOMContentLoaded', () => {
    	const departamento = document.getElementById('departamento');
    	const provincia    = document.getElementById('provincia');
    	const distrito     = document.getElementById('distrito');

    	
		departamento.value = '<?= $cliente['departamento_id'] ?>';
		provincia.value    = '<?= $cliente['provincia_id'] ?>';
    	distrito.value     = '<?= $cliente['distrito_id'] ?>';

		// Dispara el cambio de departamento (ubigeo.js lo escucha)
    	departamento.dispatchEvent(new Event('change'));

    	// Luego asigna provincia y distrito
    	setTimeout(() => {
        	// provincia.value = '<?= $cliente['provincia_id'] ?>';
        	provincia.dispatchEvent(new Event('change'));
        	distrito.value = '<?= $cliente['distrito_id'] ?>';
    	}, 300);
	});
</script>


<?php include '../../includes/footer.php'; ?>
