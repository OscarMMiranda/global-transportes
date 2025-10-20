// archivo: js/cargar_activos.js
// propósito: cargar dinámicamente los tipos de mercadería activos y activar DataTables si corresponde

document.addEventListener('DOMContentLoaded', () => {
  const contenedor = document.getElementById('contenedorActivos');

  // 🛡️ Validación defensiva
  if (!contenedor) {
    console.warn('⚠️ contenedorActivos no encontrado en el DOM.');
    return;
  }

  // 🔄 Cargar registros activos vía AJAX
  fetch('ajax/listar_activos.php')
    .then(res => {
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      return res.text();
    })
    .then(html => {
      contenedor.innerHTML = html;
      activarDataTables(); // ✅ Activar búsqueda y paginación
    })
    .catch(err => {
      console.error('❌ Error al cargar tipos activos:', err);
      contenedor.innerHTML = `
        <div class="alert alert-danger">
          No se pudo cargar la lista de tipos activos. Intenta nuevamente más tarde.
        </div>
      `;
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
      console.log('✅ DataTable activado en tablaActivos');
    }
  } else {
    console.warn('⚠️ DataTables no está disponible o jQuery no cargado.');
  }
}