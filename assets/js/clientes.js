/**
 * clientes.js
 * – Modal “Ver” con logs
 * – Llenado dinámico de provincias y distritos
 */
document.addEventListener('DOMContentLoaded', function () {
  	
	// 0) Logs iniciales
  	console.log('clientes.js cargado');
  	console.log('API URL:', window.CLIENTES_API_URL);

  	// REFERENCIAS AL MODAL “Ver”
  	const modalEl   = document.getElementById('modalVerCliente');
  	const modalBody = modalEl && modalEl.querySelector('.modal-body');
  	const modal     = modalEl && new bootstrap.Modal(modalEl);

  	// BOTONES “Ver”
  	const verButtons = document.querySelectorAll('.btn-ver');
  	console.log('Botones VER encontrados:', verButtons.length);

  	verButtons.forEach(btn => {
    	btn.addEventListener('click', function () {
      		const id = this.dataset.id;
      		console.log('Click VER id:', id);

      		if (!modal || !modalBody) {
        		return console.error('No se encontró el modal o su body');
      			}

      		modalBody.innerHTML = '<p>Cargando…</p>';
      		fetch(`${window.CLIENTES_API_URL}?method=view&id=${encodeURIComponent(id)}`)
        	.then(resp => {
          		if (!resp.ok) throw new Error(`HTTP ${resp.status}`);
          		return resp.text();
        		})
        	.then(html => {
          		modalBody.innerHTML = html;
          		modal.show();
        		})
        	.catch(err => {
          		console.error('Error cargando detalle:', err);
          		modalBody.innerHTML = `<p class="text-danger">Error: ${err.message}</p>`;
          		modal.show();
        		});
    		});
  		});

  	// Limpieza al cerrar
  	if (modalEl) {
    	modalEl.addEventListener('hidden.bs.modal', function () {
      		document.body.classList.remove('modal-open');
      		document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
    		});
  		}

  	// SELECTS DINÁMICOS
  	const depSelect  = document.getElementById('departamento_id');
  	const provSelect = document.getElementById('provincia_id');
  	const distSelect = document.getElementById('distrito_id');

  	// 1) Departamento → Provincias
  	if (depSelect) {
    	depSelect.addEventListener('change', function () {
      		const depId = this.value;
      		provSelect.innerHTML = '<option>Cargando…</option>';
      		distSelect.innerHTML = '<option value="">Selecciona...</option>';

      		fetch(`${window.CLIENTES_API_URL}?method=provincias&departamento_id=${depId}`)
        	.then(r => {
          		if (!r.ok) throw new Error(`HTTP ${r.status}`);
          		return r.json();
        		})
        	.then(list => {
          		let html = '<option value="">Selecciona...</option>';
          		list.forEach(p => {
            		html += `<option value="${p.id}">${p.nombre}</option>`;
          			});
          		provSelect.innerHTML = html;
        		})
        	.catch(err => {
          		console.error('Error cargando provincias:', err);
          		provSelect.innerHTML = '<option value="">Error</option>';
        		});
    		});
  		}

  	// 2) Provincia → Distritos
  	if (provSelect) {
    	provSelect.addEventListener('change', function () {
      		const provId = this.value;
      		distSelect.innerHTML = '<option>Cargando…</option>';

      		fetch(`${window.CLIENTES_API_URL}?method=distritos&provincia_id=${provId}`)
        	.then(r => {
          		if (!r.ok) throw new Error(`HTTP ${r.status}`);
          		return r.json();
        		})
        	.then(list => {
          		let html = '<option value="">Selecciona...</option>';
          		list.forEach(d => {
            		html += `<option value="${d.id}">${d.nombre}</option>`;
          			});
          		distSelect.innerHTML = html;
        		})
        	.catch(err => {
          		console.error('Error cargando distritos:', err);
          		distSelect.innerHTML = '<option value="">Error</option>';
        		});
    		});
  		}
	});


	document.addEventListener('DOMContentLoaded', function() {
  		if (!$.fn.DataTable.isDataTable('#tablaClientes')) {
    		$('#tablaClientes').DataTable({
      			language: {
        			url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
      				},
      			pageLength: 10,
      			order: [[1, 'asc']],
      			columnDefs: [
        			{ orderable: false, targets: 5 } // no ordena acciones
      				]
    			});
  			}
	
	
	// Transformaciones automáticas de mayúsculas/minúsculas
	document.querySelectorAll('.uppercase').forEach(input => {
  
		// Visual: forzar text-transform
  		input.style.textTransform = 'uppercase';
  		// Lógico: convertir el valor a mayúsculas al teclear
  		input.addEventListener('input', () => {
    		input.value = input.value.toUpperCase();
  			});
		});

	document.querySelectorAll('.lowercase').forEach(input => {
  		input.style.textTransform = 'lowercase';
  		input.addEventListener('input', () => {
    		input.value = input.value.toLowerCase();
  });
});


	
	
	
	});






