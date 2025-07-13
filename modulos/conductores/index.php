<?php
	session_start();
	require_once '../../includes/conexion.php';
	if (!isset($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') {
    		header("Location: ../sistema/login.php");
    		exit;
		}
?>

<!DOCTYPE html>
	<html lang="es">
		<head>
  			<meta charset="utf-8">
  			<title>Gestión de Conductores</title>
  			<meta name="viewport" content="width=device-width,initial-scale=1">
  			<!-- Bootstrap 5 -->
  			<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  			<!-- DataTables -->
  			<link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
			<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->

			<link  
  				href="https://cdn.datatables.net/fixedheader/3.3.2/css/fixedHeader.bootstrap5.min.css"  
  				rel="stylesheet">  
			<script  
  				src="https://cdn.datatables.net/fixedheader/3.3.2/js/dataTables.fixedHeader.min.js">  
			</script>

		</head>

		<body class="bg-light">
  			<div class="container py-4">
				<div class="sticky-top bg-light py-2" style="z-index:1020">
    			<div class="d-flex justify-content-between align-items-center mb-4">
      				<h1 class="h4">🧍 Conductores</h1>
      				<div>
        				<a href="../erp_dashboard.php" 
							class="btn btn-outline-secondary me-2">
								← Dashboard
						</a>
        				<button id="btnNuevo"
        					class="btn btn-success"
        					data-bs-toggle="modal"
        					data-bs-target="#modalConductor">
  							Nuevo conductor
						</button>


						<!-- Aquí insertas el checkbox -->
    					<div class="form-check form-switch mb-0">
      						<input class="form-check-input"
             					type="checkbox"
             					id="chkMostrarInactivos">
      						<label class="form-check-label"
             					for="chkMostrarInactivos">
        						Mostrar inactivos
      						</label>
    					</div>
    				</div>
    			</div>
				</div>
    			<table id="tblConductores" 
					class="table table-striped table-hover table-bordered">
      				<thead>
        				<tr>
          					<th>ID</th>
          					<th>Apellido, Nombre</th>
          					<th>DNI</th>
          					<th>Licencia</th>
          					<th>Teléfono</th>
          					<th>Correo</th>
							<th>Dirección</th>          <!-- NUEVO -->
    						<th>Foto</th>               <!-- NUEVO -->
          					<th>Estado</th>
          					<th style="width:100px">Acciones</th>
        				</tr>
      				</thead>
      				<tbody></tbody>
    			</table>
  			</div>

  			<!-- Modal (formulario) -->
  			<div class="modal fade" 
				id="modalConductor" 
				tabindex="-1">
    			<div class="modal-dialog">
      				<form id="frmConductor" 
						class="modal-content"
						enctype="multipart/form-data">  <!-- permiso para archivos -->
        				<div class="modal-header">
          					<h5 class="modal-title">
								Conductor</h5>
          					<button type="button" 
								class="btn-close" 
								data-bs-dismiss="modal">
							</button>
        				</div>
        				<div class="modal-body">
          					<input type="hidden" 
								id="c_id" 
								name="id">
          					<div class="mb-3">
            					<label for="c_nombres" 
									class="form-label">
									Nombres</label>
            					<input type="text" 
									id="c_nombres" 
									name="nombres" 
									class="form-control" 
									required>
          					</div>
          					<div class="mb-3">
            					<label for="c_apellidos" 
									class="form-label">
									Apellidos
								</label>
            					<input type="text" 
									id="c_apellidos" 
									name="apellidos" 
									class="form-control" 
									required>
          					</div>
          					<div class="mb-3">
            					<label for="c_dni" 
									class="form-label">
									DNI
								</label>
            					<input type="text" 
									id="c_dni" 
									name="dni" 
									class="form-control" 
									required pattern="\d{8}"
								>
          					</div>
          					<div class="mb-3">
            					<label for="c_licencia" 
									class="form-label">
									Licencia Nº
								</label>
            					<input type="text" 
									id="c_licencia_conducir" 
									name="licencia_conducir" 
									class="form-control" 
									required>
          					</div>
          					<div class="mb-3">
            					<label for="c_telefono" 
									class="form-label">
									Teléfono
								</label>
            					<input type="tel" 
									id="c_telefono" 
									name="telefono" 
									class="form-control">
          					</div>
          					<div class="mb-3">
            					<label for="c_correo" 
									class="form-label">
									Correo
								</label>
            					<input type="email" 
									id="c_correo" 
									name="correo" 
									class="form-control">
          					</div>

							<!-- NUEVO: Dirección -->
    						<div class="mb-3">
      							<label for="c_direccion" 
									class="form-label">
									Dirección
								</label>
      							<input type="text"
             						id="c_direccion"
             						name="direccion"
             						class="form-control">
    						</div>

							<!-- NUEVO: Foto -->

							<div class="mb-3 text-center">
  								<img id="preview_foto" 
       								src="" 
       								alt="Foto conductor" 
       								class="img-fluid rounded" 
       								style="max-height:150px; display:none;">
							</div>
    						<div class="mb-3">
      							<label for="c_foto" 
									class="form-label">
									Foto
								</label>
      							<input type="file"
             						id="c_foto"
             						name="foto"
             						accept="image/*"
             						class="form-control">
    						</div>

          					<div class="form-check">
            					<input class="form-check-input" 
									type="checkbox" id="c_activo" 
									name="activo" value="1" 
									checked>
            					<label class="form-check-label" 
									for="c_activo">
									Activo</label>
          					</div>
        				</div>
        				<div class="modal-footer">
        	  				<button type="button" 
								class="btn btn-secondary" 
								data-bs-dismiss="modal"
								id="btnCancelar"
								>
								Cancelar
							</button>
        	  				<button type="submit" 
								class="btn btn-primary">
								Guardar
							</button>
        				</div>
      				</form>
    			</div>
  			</div>

  			<!-- Scripts al final -->
  			<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  			<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  			<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  			<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
  			<script src="assets/conductores.js"></script>
		</body>
	</html>
