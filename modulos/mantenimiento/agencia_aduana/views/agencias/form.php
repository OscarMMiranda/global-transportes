<?php if($error): ?>
  	<div class="alert alert-danger"><?=htmlspecialchars($error)?></div>
<?php endif; ?>
<?php if(isset($_GET['msg'])): ?>
  	<div class="alert alert-success"><?=htmlspecialchars($_GET['msg'])?></div>
<?php endif; ?>

<!-- DIV que expone los valores para precarga en JS -->
<div id="agencia-datos"
     data-dep="<?= (int) $registro['departamento_id'] ?>"
     data-prov="<?= (int) $registro['provincia_id'] ?>"
     data-dist="<?= (int) $registro['distrito_id'] ?>">
</div>

<form method="post" action="editar_agencia_aduana.php" class="mb-5">
  	<input type="hidden" name="id" value="<?= $registro['id'] ?>">

<?php
  	// Helper para crear inputs en flex, con required sólo en ciertos campos
  	function campo($label, $name, $value){
    	// return "
      	// 	<div class='mb-3 d-flex align-items-center'>
        // 		<label class='form-label me-2 mb-0' style='width:200px;'>$label</label>
        // 		<input type='text' 
		// 			name='$name' 
		// 			class='form-control w-50'
        //     		value='".htmlspecialchars($value)."' required>
      	// 	</div>
    	// 	";


		 // Definimos aquí qué campos son obligatorios
      		$requeridos = ['nombre','ruc','direccion'];
      		$req = in_array($name, $requeridos) ? 'required' : '';

      	// Determinamos type="email" para correo, el resto type="text"
      		$type = $name === 'correo_general' ? 'email' : 'text';
		
		// Estilo uppercase para nombre
			$style = $name === 'nombre'
    				? "style='text-transform: uppercase'"
    				: '';

		// Atributos extra para RUC
    		$attrs = '';
    		if ($name === 'ruc') {
        		$attrs = "pattern='\\d{11}' ".
                	"inputmode='numeric' ".
                 	"maxlength='11' ".
                 	"title='RUC: 11 dígitos numéricos'";
    			}
      		return "

      		<div class='mb-3 d-flex align-items-center'>
        		<label class='form-label me-2 mb-0' style='width:200px;'>$label</label>
        		<input 
          			type='$type'
          			name='$name'
          			class='form-control w-50'
          			value='".htmlspecialchars($value)."' 
					$style
          			$req
					$attrs
        		>
      		</div>
      		";

  		}

  		echo campo('Nombre','nombre',$registro['nombre']);
  		echo campo('RUC','ruc',$registro['ruc']);
		echo campo('Correo','correo_general',$registro['correo_general']);
		echo campo('Contacto', 'contacto', $registro['contacto']);
  		echo campo('Dirección','direccion',$registro['direccion']);
		
  	?>

	<!-- Departamento -->
  	<div class="mb-3 d-flex align-items-center">
    	<label class="form-label me-2 mb-0" style="width:200px;">Departamento</label>
    	<select id="departamento_id" name="departamento_id" class="form-select w-50" required>
      		<option value="">- Selecciona -</option>
      		<?php foreach($departamentos as $d): ?>
        	<option value="<?= $d['id'] ?>" <?= $registro['departamento_id']==$d['id']?'selected':''?>>
          		<?= htmlspecialchars($d['nombre']) ?>
        	</option>
      		<?php endforeach; ?>
    	</select>
  	</div>

  <!-- Provincia -->
  	<div class="mb-3 d-flex align-items-center">
    	<label class="form-label me-2 mb-0" style="width:200px;">Provincia</label>
    	<select id="provincia_id" name="provincia_id" class="form-select w-50" required>
      		<option value="">— Selecciona un departamento —</option>
    	</select>
  	</div>

  <!-- Distrito -->
  	<div class="mb-3 d-flex align-items-center">
    	<label class="form-label me-2 mb-0" style="width:200px;">Distrito</label>
    	<select id="distrito_id" name="distrito_id" class="form-select w-50" required>
      		<option value="">— Selecciona una provincia —</option>
    	</select>
  	</div>

  	<button type="submit" class="btn btn-primary">
    	<?= $registro['id']>0 ? 'Actualizar Agencia' : 'Agregar Agencia' ?>
  	</button>
</form>
