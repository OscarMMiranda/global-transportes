<?php
	// archivo: /modulos/mantenimiento/entidades/views/editar.php
	// contenido HTML para el modal de edición

	error_reporting(E_ALL);
	ini_set('display_errors', 1);

	require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
	$conn = getConnection();

	$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
	if ($id <= 0 || !($conn instanceof mysqli)) {
    	echo "<div class='modal-body'><div class='alert alert-danger'>ID inválido o conexión fallida.</div></div>";
    	return;
		}

	// Consulta jerárquica
	$sql = 
		"SELECT e.*, 
            dpto.id AS departamento_id,
            prov.id AS provincia_id,
            dist.id AS distrito_id
        FROM entidades e
        LEFT JOIN distritos dist ON e.distrito_id = dist.id
        LEFT JOIN provincias prov ON dist.provincia_id = prov.id
        LEFT JOIN departamentos dpto ON prov.departamento_id = dpto.id
        WHERE e.id = $id LIMIT 1";

	$res = $conn->query($sql);
	if (!$res || $res->num_rows === 0) {
    	echo "<div class='modal-body'><div class='alert alert-warning'>Entidad no encontrada.</div></div>";
    	return;
	}

$row = $res->fetch_assoc();
?>

<div class="modal-header bg-warning text-white d-flex justify-content-between align-items-center">
    <h4 class="modal-title mb-0">
        <i class="fa fa-pencil"></i> Editar entidad
    </h4>

    <?php
        $estadoRaw = strtolower(trim($row['estado']));
        $estadoTexto = ($estadoRaw === 'activo') ? 'Activo' : 'Inactivo';
        $estadoClase = ($estadoTexto === 'Activo') ? 'success' : 'danger';
    ?>

    <div class="small">
        <span class="badge badge-<?= $estadoClase ?>">
            <i class="fa <?= ($estadoTexto === 'Activo') ? 'fa-check-circle' : 'fa-ban' ?>"></i> 
            <?= $estadoTexto ?>
        </span>
    </div>
</div>


<div class="modal-body">
  	<form id="formEditarEntidad">
    	<input type="hidden" name="id" value="<?= $row['id'] ?>">
	<div class="form-row">
    	<div class="form-group col-md-8">
    	  	<label>Nombre</label>
    	  	<input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($row['nombre']) ?>" required>
    	</div>

    	<div class="form-group col-md-4">
    	  	<label>RUC</label>
    	  	<input type="text" name="ruc" class="form-control" value="<?= htmlspecialchars($row['ruc']) ?>" required>
    	</div>
	</div>
    	

		<div class="form-group">
  			<label for="tipo_id_editar">Tipo de lugar</label>
  			<select name="tipo_id" id="tipo_id_editar" class="form-control" required data-valor="<?= intval($row['tipo_id']) ?>">
    			<option value="">-- Seleccionar tipo --</option>
  			</select>
		</div>

    	<div class="form-group">
      		<label>Dirección</label>
      		<input type="text" name="direccion" class="form-control" value="<?= htmlspecialchars($row['direccion']) ?>">
    	</div>

    	<div class="form-row">
    <div class="form-group col-md-4">
        <label>Departamento</label>
        <select name="departamento_id" id="departamento_id_editar" 
                class="form-control" required 
                data-valor="<?= intval($row['departamento_id']) ?>">
            <option value="">-- Seleccionar --</option>
        </select>
    </div>

    <div class="form-group col-md-4">
        <label>Provincia</label>
        <select name="provincia_id" id="provincia_id_editar" 
                class="form-control" required 
                data-valor="<?= intval($row['provincia_id']) ?>">
            <option value="">-- Seleccionar --</option>
        </select>
    </div>

    <div class="form-group col-md-4">
        <label>Distrito</label>
        <select name="distrito_id" id="distrito_id_editar" 
                class="form-control" required 
                data-valor="<?= intval($row['distrito_id']) ?>">
            <option value="">-- Seleccionar --</option>
        </select>
    </div>
</div>

  	</form>
</div>

<div class="modal-footer">
  	<button type="button" class="btn btn-primary" onclick="actualizarEntidad()">
    	<i class="fa fa-save"></i> Guardar cambios
  	</button>
  	<button type="button" class="btn btn-default" data-dismiss="modal">
    	<i class="fa fa-times"></i> Cancelar
  	</button>
</div>