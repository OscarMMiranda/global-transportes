<?php
	// clientes.php

	// 2) Modo depuración (solo DEV)
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('log_errors',     1);
    ini_set('error_log',      __DIR__ . '/error_log.txt');

    // 3) Cargar config.php (define getConnection() y rutas)
    require_once __DIR__ . '/../../includes/config.php';

    // 4) Obtener la conexión
    $conn = getConnection();

	// require_once '../../includes/header_erp.php';

	 // 2) Incluir header_erp
$erpHeader = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . '/includes/header_erp.php';
if (! file_exists($erpHeader)) {
    die("No se encontró el header: $erpHeader");
}
require_once $erpHeader;

	// ─── 2) Cargar cabecera específica del ERP ────────────────────────────────
//require_once INCLUDES_PATH . '/header_erp.php';



	// Mostrar errores (solo en desarrollo)
	error_reporting(E_ALL);
	ini_set('display_errors', 1);

	// Consulta con JOIN para mostrar nombres de ubicación
	// $query = 


	// 1) Preparar y ejecutar consulta con filtro por estado ‘Activo’
	$sql = "
  		SELECT 
    		c.id, 
			c.nombre, 
			c.ruc, 
			c.direccion, 
			c.correo,
    		dis.nombre AS distrito
  		FROM clientes c
  		LEFT JOIN distritos dis ON c.distrito_id = dis.id
  		WHERE c.estado = ?
  		ORDER BY c.id DESC
		";


	// 1) Preparar y ejecutar consulta con estado 'Activo'
		$estado = 'Activo';
		$stmt = mysqli_prepare($conn, $sql);

		if (! $stmt) {
  			die("Error en prepare: " . mysqli_error($conn));
			}
		

	mysqli_stmt_bind_param($stmt, 's', $estado);
	mysqli_stmt_execute($stmt);
	$resultado = mysqli_stmt_get_result($stmt);

	// 2) Función para mensajes flash
function flash($key) {
    if (isset($_GET[$key])) {
        $class = ($key === 'mensaje') ? 'mensaje-exito' : 'mensaje-error';
        echo '<div class="' . $class . '">'
           . htmlspecialchars($_GET[$key])
           . '</div>';
    }
}

?>

<!-- Estilos base personalizados -->
<link rel="stylesheet" href="../../css/estilo.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />


<main class="container mt-0">
	<!-- TÍTULO -->
	<h2 class="titulo-pagina text-center mt-0 mb-4">
  		<i class="fas fa-address-book me-2"></i> 
		Gestión de Clientes
	</h2>
	<!-- BARRA DE BOTONES -->
	<div class="d-flex justify-content-between mb-3">
  		<div>
    		<a href="crear_clientes.php" class="btn btn-primary me-2">
      			<i class="fas fa-user-plus me-1"></i> 
				Registrar Cliente
    		</a>
    		<a href="../../modulos/erp_dashboard.php" 
				class="btn btn-secondary">
      			<i class="fas fa-arrow-left me-1"></i> 
				Volver al Módulo
    		</a>
  		</div>
	</div>

    <?php if (isset($_GET['mensaje'])): ?>
        <div class="mensaje-exito">
            <?= htmlspecialchars($_GET['mensaje']) ?>
        </div>
    <?php elseif (isset($_GET['error'])): ?>
        <div class="mensaje-error">
            <?= htmlspecialchars($_GET['error']) ?>
        </div>
    <?php endif; ?>

	<?php
    	flash('mensaje');
    	flash('error');
  	?>

	<div class="table-responsive">
		 <table class="table table-hover table-striped">
            
		 	<!-- <thead> -->
			<!-- <thead class="table-dark"> -->
				<thead class="table-dark sticky-top">
                <tr>
                    <th >ID</th>
                    <th>Nombre</th>
                    <th>RUC</th>
                    <th>Dirección</th>
                    <!-- <th>Correo</th> -->
                    <th>Distrito</th>
					<th class="text-center">Acciones</th>
                </tr>
            </thead>
            
			<tbody>
            	<?php if ($resultado && mysqli_num_rows($resultado) > 0): ?>
          		<?php while ($c = mysqli_fetch_assoc($resultado)): ?>

                        <tr>
                            <td><?= $c['id'] ?></td>
                            <td><?= htmlspecialchars($c['nombre']) ?></td>
                            <td><?= htmlspecialchars($c['ruc']) ?></td>
                            <td><?= htmlspecialchars($c['direccion']) ?></td>
                            <!-- <td><?= htmlspecialchars($c['correo']) ?></td> -->
                            <td><?= htmlspecialchars($c['distrito']) ?></td>
                            
							<!-- <td class="acciones"> -->
							<td class="text-center">
							<div class="d-flex justify-content-center gap-1">
								<a href="/../modulos/clientes/views/editar_clientes.php?id=<?= $c['id'] ?>" 
   									class="btn btn-sm btn-warning me-1 d-flex align-items-center justify-content-center"
   									style="width: 36px; height: 36px;"
   									title="Editar">
   									<i class="fas fa-edit"></i>
								</a>
								<button 
									type="button"
									class="btn btn-sm btn-info btn-ver d-flex align-items-center justify-content-center"
									style="width: 36px; height: 36px;"
									data-id="<?= $c['id'] ?>" 
									title="Ver">
									<i class="fas fa-eye"></i>
								</button>
								<form 
									action="eliminar_clientes.php" 
									method="post" 
									class="d-inline"
								>
									<input 
										type="hidden" 
										name="id" 
										value="<?= $c['id'] ?>"
									>
									<button 
										type="submit" 
										class="btn btn-sm btn-danger d-flex align-items-center justify-content-center"
										style="width: 36px; height: 36px;"
										onclick="return confirm('¿Eliminar este cliente?')" 
										title="Eliminar">
										<i class="fas fa-trash-alt"></i>
									</button>
								</form>
							</div>
							</td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <!-- <td colspan="6" class="sin-registros">No se encontraron clientes activos.</td> -->
						<td colspan="6" class="text-center text-muted">No se encontraron clientes activos.</td>
         
					</tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>


<!-- Modal para ver detalle -->
<div class="modal fade" 
	id="modalVerCliente" 
	tabindex="-1" 
	aria-labelledby="modalVerClienteLabel" 
	aria-hidden="true">
  	<div class="modal-dialog modal-lg">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" 
					id="modalVerClienteLabel">
					👁️ Detalle del Cliente
				</h5>
        		<button 
					type="button" 
					class="btn-close" 
					data-bs-dismiss="modal">
				</button>
      		</div>
      		<div class="modal-body">
        		<!-- Contenido cargado vía AJAX -->
      		</div>
    	</div>
  	</div>
</div>






<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


<!-- Script para cargar modal vía AJAX -->
<script>
document.addEventListener("DOMContentLoaded", function () {
  var modalEl = document.getElementById("modalVerCliente");
  var modal = new bootstrap.Modal(modalEl);
  var body = modalEl.querySelector(".modal-body");

  document.querySelectorAll(".btn-ver").forEach(function (btn) {
    btn.addEventListener("click", function () {
      var id = this.getAttribute("data-id");
      fetch("ver_cliente.php?id=" + id + "&ajax=1")
        .then(resp => resp.text())
        .then(html => {
          body.innerHTML = html;
          modal.show();
        });
    });
  });
});
</script>




<?php
mysqli_stmt_close($stmt);
mysqli_close($conn);
require_once '../../includes/footer.php';
?>



<!-- <?php include '../../includes/footer.php'; ?> -->
