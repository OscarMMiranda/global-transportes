console.log('conductores.js cargado');

$(function () {
	const modalEl = document.getElementById('modalConductor');
  const modalConductor = new bootstrap.Modal(modalEl);
	const api = 'api.php';
  	let tablaConductores;

  	// 1. Inicializa DataTable con dataFilter, dataSrc y manejo de errores
  	tablaConductores = $('#tblConductores').DataTable({
		fixedHeader: true,
    	ajax: {
      		url: api,
      		data: { op: 'list' },

      		// Muestra en consola el texto crudo que llega del servidor
      		dataFilter(raw) {
        		console.log('⏺ RAW JSON list:', raw);
        		return raw;
      			},

      		// Parsea el JSON y extrae el array data
      		dataSrc(json) {
        		console.log('✅ Parsed list JSON:', json);
        		return json.success ? json.data : [];
      			},

      		// Captura cualquier fallo en la llamada o parseo
      		error(xhr) {
        		console.error('⛔ LIST AJAX ERROR:', xhr.responseText);
      			}
    		},
    
		columns: [
      		{ data: 'id' },
      		{ data: r => `${r.apellidos}, ${r.nombres}` },
      		{ data: 'dni' },
      		{ data: 'licencia_conducir' },
      		{ data: 'telefono' },
      		{ data: 'correo' },

			// Nuevo: direccion
  			{ data: 'direccion' },

			// Nuevo: foto (miniatura)
  			{
    		data: 'foto',
    		render: foto =>
      		foto
        	? `<img src="/uploads/conductores/${foto}" height="40">`
        	: ''
  			},

			{
  			data: 'activo',
  			render: activo => {
    		// convierte "0"/"1" → 0/1
    			const ok = Number(activo) === 1;
    			return ok
      			? '<span class="badge bg-success">Activo</span>'
      			: '<span class="badge bg-danger">Inactivo</span>';
  				}
			},

      		{
        	data: null,
        	orderable: false,

			render: r => {
				const id     = r.id;
  				
				const activo = Number(r.activo);

    			const btnView    = `<button class="btn-view    btn btn-sm btn-info   me-1" data-id="${id}">👁️</button>`;
    			const btnEdit    = `<button class="btn-edit    btn btn-sm btn-primary me-1" data-id="${id}">✏️</button>`;
    			const btnDelete  = `<button class="btn-delete  btn btn-sm btn-danger  me-1" data-id="${id}">🗑️</button>`;
    			const btnRestore = `<button class="btn-restore btn btn-sm btn-success me-1" data-id="${id}">♻️</button>`;


  				
				if (activo === 1) {
      				// registros activos: ver + editar + borrar
      				return btnView + btnEdit + btnDelete;
    				} 
				else {
      				// registros inactivos: ver + restaurar
      				return btnView + btnRestore;
    				}

    			}
			}
		],
    	order: [[1, 'asc']],
    	language: {
      		url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
    		}
		});	

		// 2) Listener para el checkbox “Mostrar inactivos”
  		$('#chkMostrarInactivos').on('change', function(){
    		// recalcula la URL con el filtro
    		const filter = this.checked ? 'all' : 'active';
    		tablaConductores
      			.ajax
      			.url(api + '?op=list&filter=' + filter)
      			.load();
  			});

  		// 2. Botón "Nuevo conductor" abre el modal en blanco
  		$('#btnNuevo').click(() => {
    		$('#frmConductor')[0].reset();
    		$('#c_id').val('');
    		$('#c_activo').prop('checked', true);

			// Reactiva todos los campos
  			$('#frmConductor input, #frmConductor select').prop('disabled', false);
  			$('#frmConductor button[type="submit"]').show();
  			$('#btnCancelar').text('Cancelar');

    		modalConductor.show();
  			});

  		// 3. Cargar datos para editar en el modal
  		$('#tblConductores').on('click', '.btn-edit', function () {
    		const id = $(this).data('id');
    		console.log('EDIT id=', id);

			$.getJSON(api, { op: 'get', id }, res => {
      			console.log('📥 GET Response:', res);
      			
				if (!res.success) {
        			return alert('Error al obtener datos: ' + res.error);
      				}
	
				// 1) Rellenar campos (excluyendo foto)
    			const skip = ['foto'];
    			$.each(res.data, (k, v) => {
      				if (skip.includes(k)) return;
      				$(`#c_${k}`)
        			.val(v)
        			.prop('disabled', false);
    				});
				
				// 2) Mostrar miniatura si hay foto
    			if (res.data.foto) {
      				$('#preview_foto')     			
						.attr('src', '/uploads/conductores/' + res.data.foto)
        				.show();
    				} 
				else {
					$('#preview_foto')
    					.attr('src', '')
    					.hide();
    				}

				// 3) Reset y habilitación del file-input
    			$('#c_foto')
      				.val('')
      				.prop('disabled', false);

    			// 4) Checkbox “Activo”
    			$('#c_activo')
      				.prop('checked', !!res.data.activo)
      				.prop('disabled', false);
				
				// 5) Reactiva botones del modal
    			$('#frmConductor button[type="submit"]').show();
    			$('#btnCancelar').text('Cancelar');



				modalConductor.show();
    		});
  		});

				// Forzar mayúsculas en tiempo real

	['#c_nombres', '#c_apellidos', '#c_licencia_conducir'].forEach(selector => {
  		$(selector).on('input blur paste', function () {
    		const cursor = this.selectionStart;
    		this.value = this.value.toUpperCase();
    		this.setSelectionRange(cursor, cursor); // mantiene el cursor en su lugar
  			});
		});

	// Ver conductor (solo lectura)
	$('#tblConductores').on('click', '.btn-view', function () {
		const id = $(this).data('id');
		console.log('VIEW id=', id);

  		$.getJSON(api, { op: 'get', id }, res => {
    		console.log('📥 VIEW Response:', res);
    		if (!res.success) {
      			return alert('Error al obtener datos: ' + res.error);
    			}

    		// 1)	Rellena el formulario
			const skip = ['foto'];				// adicionado 01
    		$.each(res.data, (k, v) => {		// adicionado 01
				if (skip.includes(k)) return;		
    			$(`#c_${k}`).val(v).prop('disabled', true);
    			});

			// 2) 	Preview de la foto
    		if (res.data.foto) {
      			$('#preview_foto')
        		.attr('src', '/uploads/conductores/' + res.data.foto)
        		.show();
    			} 
			else {
      			$('#preview_foto').hide();
    			}
			
			// 3) Dejar file-input vacío y deshabilitado
    		$('#c_foto').val('')
				.prop('disabled', true);
			
			// 4) Checkbox activo deshabilitado
    		$('#c_activo')
      			.prop('checked', !!res.data.activo)
      			.prop('disabled', true);

    		// 05)	Ocultar botón guardar y ajustar texto del cancelar
    		$('#frmConductor button[type="submit"]').hide();
    		$('#btnCancelar').text('Cerrar');

    		modalConductor.show();
  		});
	});

	
	
	
  	// 4. Eliminar lógico con confirmación
  	$('#tblConductores').on('click', '.btn-delete', function () {
    	const id = $(this).data('id');
    	console.log('DELETE id=', id);
    	if (!confirm('¿Eliminar conductor?')) return;
    	$.post(api, { op: 'delete', id }, res => {
      		console.log('📥 DELETE Response:', res);
      		if (!res.success) {
        		return alert('Error al eliminar: ' + res.error);
      			}
      		tablaConductores.ajax.reload();
    		}).fail(xhr => {
      		console.error('⛔ DELETE AJAX ERROR:', xhr.responseText);
      		alert('Error del servidor al eliminar');
    		});
  		});

  	


	// 5. Guardar (insert / update) con soporte para archivos
$('#frmConductor').submit(function (e) {
  e.preventDefault();

  const form = this;
  const formData = new FormData(form);
  formData.append('op', 'save');

  console.log('📤 Enviando FormData:');
  for (let [k, v] of formData.entries()) {
    console.log(k, v);
  }

  fetch(api, {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(res => {
    console.log('📥 SAVE Response:', res);
    if (res.success) {
      bootstrap.Modal.getInstance(modalEl).hide();
      tablaConductores.ajax.reload();
    } else {
      alert('❌ SAVE Error: ' + res.error);
    }
  })
  .catch(err => {
    console.error('⛔ AJAX SAVE FAILED:', err);
    alert('Error del servidor al intentar guardar');
  });
});


	// 6. Restaurar conductor inactivo
	$('#tblConductores').on('click', '.btn-restore', function () {
  		const id = $(this).data('id');
  		console.log('RESTORE id=', id);
  		if (!confirm('¿Deseas reactivar este conductor?')) return;

  		$.post(api, { op: 'restore', id }, res => {
    		console.log('📥 RESTORE Response:', res);
    		if (!res.success) {
      			return alert('Error al restaurar: ' + res.error);
    			}
    		tablaConductores.ajax.reload(null, false); // recarga sin perder paginación
  			}).fail(xhr => {
    		console.error('⛔ RESTORE AJAX ERROR:', xhr.responseText);
    		alert('Error del servidor al restaurar');
  		});
	});


});
