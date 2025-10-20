//  archivo : /modulos/mantenimiento/tipo_mercaderia/js/tipo_mercaderia.js

document.addEventListener('DOMContentLoaded', () => {
  	
	// 🔄 Cargar registros activos
  		fetch('ajax/listar_activos.php')
    	.then(res => res.text())
    	.then(html => {
      		document.getElementById('contenedorActivos').innerHTML = html;
      		activarDataTables(); // ✅ Activar búsqueda y paginación
    		})
    	.catch(err => console.error('❌ Error al cargar activos:', err));

  	// 🔄 Cargar registros inactivos
  		fetch('ajax/listar_inactivos.php')
    	.then(res => res.text())
    	.then(html => {
    	  	document.getElementById('contenedorInactivos').innerHTML = html;
    	  	activarDataTables(); // ✅ Activar búsqueda y paginación
    		})
    	.catch(err => console.error('❌ Error al cargar inactivos:', err));

  // ✎ Delegación para botones de edición
  document.addEventListener('click', function (e) {
    if (e.target.closest('.btn-editar')) {
      const btn = e.target.closest('.btn-editar');
      const id = btn.getAttribute('data-id');

      fetch(`ajax/editar_form.php?ajax=1&id=${id}`)
        .then(res => res.text())
        .then(html => {
          document.getElementById('contenidoModalEditar').innerHTML = html;
          const modal = new bootstrap.Modal(document.getElementById('modalEditar'));
          modal.show();
        })
        .catch(err => console.error('❌ Error al cargar formulario de edición:', err));
    }
  });
});

// ✅ Activar DataTables si están disponibles
function activarDataTables() {
  if (window.jQuery && $.fn.DataTable) {
    const config = {
      language: {
        search: "🔍 Buscar:",
        lengthMenu: "Mostrar _MENU_ registros",
        zeroRecords: "No se encontraron coincidencias",
        info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
        infoEmpty: "Sin registros disponibles",
        paginate: {
          first: "Primero",
          last: "Último",
          next: "→",
          previous: "←"
        }
      },
      order: [[0, 'asc']],
      pageLength: 10
    };

    if ($('#tablaActivos').length && !$.fn.DataTable.isDataTable('#tablaActivos')) {
      $('#tablaActivos').DataTable(config);
    }

    if ($('#tablaInactivos').length && !$.fn.DataTable.isDataTable('#tablaInactivos')) {
      $('#tablaInactivos').DataTable(config);
    }
  } else {
    console.warn('⚠️ DataTables no está disponible.');
  }
}