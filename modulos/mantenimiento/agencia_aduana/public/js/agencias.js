$(function(){

	console.log('✅ agencias.js arrancó');
	console.log('► département select:', $('#departamento_id').length);
	console.log('► provincia select:',   $('#provincia_id').length);
	console.log('► distrito select:',    $('#distrito_id').length);

	// DEBUG: confirmar que el script está cargado
	console.log('✅ agencias.js activo');

	// Al cambiar el departamento, cargar provincias
  	$('#departamento_id').on('change', function(){
    	var dep = $(this).val();
    	console.log('Departamento seleccionado:', dep);

    	if (!dep) {
      		$('#provincia_id').html('<option value="">— Selecciona un departamento —</option>');
      		$('#distrito_id').html('<option value="">— Selecciona una provincia —</option>');
      		return;
    		}

    	$.post(
      		'/modulos/mantenimiento/agencia_aduana/ajax/get_provincias.php',
      		{ departamento_id: dep },
      		function(html) {
        		$('#provincia_id').html(html);
        		$('#distrito_id').html('<option value="">— Selecciona una provincia —</option>');
      			}
    		).fail(function(xhr) {
      		console.error('Error al cargar provincias:', xhr.status);
    		});
  		});

  	// Al cambiar la provincia, cargar distritos
  	$('#provincia_id').on('change', function(){
    	var prov = $(this).val();
    	console.log('Provincia seleccionada:', prov);

    	if (!prov) {
      		$('#distrito_id').html('<option value="">— Selecciona una provincia —</option>');
      		return;
    		}

    	$.post(
      		'/modulos/mantenimiento/agencia_aduana/ajax/get_distritos.php',
      		{ provincia_id: prov },
      		function(html) {
        	$('#distrito_id').html(html);
      		}
    		).fail(function(xhr) {
      		console.error('Error al cargar distritos:', xhr.status);
    		});
  			});

  	// Precargar selects si estamos editando
  	var $data = $('#agencia-datos');
  	var depEdit  = parseInt($data.data('dep')) || 0;
  	var provEdit = parseInt($data.data('prov')) || 0;
  	var distEdit = parseInt($data.data('dist')) || 0;

  	if (depEdit) {
    	$('#departamento_id').val(depEdit).trigger('change');
    	setTimeout(function(){
      		$('#provincia_id').val(provEdit).trigger('change');
      		setTimeout(function(){
        		$('#distrito_id').val(distEdit);
      			}, 400);
    		}, 400);
  		}

	});
