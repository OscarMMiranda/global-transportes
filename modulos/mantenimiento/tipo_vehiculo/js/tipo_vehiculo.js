// archivo: /modulos/mantenimiento/tipo_vehiculo/js/tipo_vehiculo.js

$(document).ready(function () {

	 if (!document.getElementById('contenedorActivos') && !document.getElementById('contenedorInactivos')) {
    	console.log('🔒 tipo_vehiculo.js: módulo no presente, script desactivado.');
    	return;
  		}

  	cargarActivos();
  	cargarInactivos();

  	$('#tab-activos').on('click', cargarActivos);
  	$('#tab-inactivos').on('click', cargarInactivos);

  	// 🟢 Cargar formulario al abrir el modal de creación
  	$('#modalAgregar').on('show.bs.modal', function () {
    const $contenedor = $('#contenedorFormularioAgregar');
    $contenedor.html('<div class="text-center text-muted py-3"><i class="fas fa-spinner fa-spin me-2"></i> Cargando formulario...</div>');

  	$.get('/modulos/mantenimiento/tipo_vehiculo/ajax/form_create.php', function (html) {
      	$contenedor.html(html);
    	}).fail(function () {
      	$contenedor.html('<div class="alert alert-danger text-center">❌ Error al cargar el formulario.</div>');
    	});
  	});

  	// Delegación para botones de edición
  	$('#contenedorActivos, #contenedorInactivos').on('click', '.btn-editar', function () {
    	const id = $(this).data('id');
    	if (id > 0) {
    	  abrirModalEditar(id);
    		} 
		else {
      		console.warn('⚠️ ID inválido para edición:', id);
    		}
  		});

  // Delegación para botón "ver"
  $('#contenedorActivos, #contenedorInactivos').on('click', '.btn-ver', function () {
    const id = $(this).data('id');
    if (id > 0) {
      abrirModalVer(id);
    } else {
      console.warn('⚠️ ID inválido para ver:', id);
    }
  });

  // Delegación para botón "borrar"
  $('#contenedorActivos, #contenedorInactivos').on('click', '.btn-borrar', function () {
    const id = $(this).data('id');
    if (id > 0 && confirm('¿Estás seguro de que deseas eliminar este vehículo?')) {
      eliminarVehiculo(id);
    } else {
      console.warn('⚠️ Acción de borrado cancelada o ID inválido:', id);
    }
  });
});

// 🔄 Cargar lista de vehículos activos
function cargarActivos() {
  const $contenedor = $('#contenedorActivos');

  const tabla = document.getElementById('tablaActivos');
  if (tabla && $.fn.DataTable && $.fn.DataTable.isDataTable(tabla)) {
    $(tabla).DataTable().clear().destroy();
    $contenedor.empty();
  }

  $contenedor.html('<div class="text-center py-3 text-muted"><i class="fas fa-spinner fa-spin me-2"></i> Cargando activos...</div>');

  $.ajax({
    url: '/modulos/mantenimiento/tipo_vehiculo/ajax/listar_activos.php',
    method: 'GET',
    success: function (html) {
      $contenedor.html(html);

    setTimeout(function () {
  const idTabla = 'tablaActivos';
  const $tabla = $('#' + idTabla);
  if ($tabla.length && $.fn.DataTable) {
    inicializarTablaVehiculos(idTabla);
  } else {
    console.warn(`⚠️ Tabla ${idTabla} no encontrada en el DOM o DataTables no disponible.`);
  }
}, 100);


    },


    error: function (xhr, status, error) {
      console.error('❌ Error al cargar activos:', error);
      $contenedor.html('<div class="alert alert-danger text-center"><i class="fas fa-exclamation-triangle me-2"></i> Error al cargar vehículos activos.</div>');
    }
  });
}

// 🔄 Cargar lista de vehículos inactivos
function cargarInactivos() {
  const $contenedor = $('#contenedorInactivos');

  const tabla = document.getElementById('tablaInactivos');
  if (tabla && $.fn.DataTable && $.fn.DataTable.isDataTable(tabla)) {
    $(tabla).DataTable().clear().destroy();
    $contenedor.empty();
  }

  $contenedor.html('<div class="text-center py-3 text-muted"><i class="fas fa-spinner fa-spin me-2"></i> Cargando eliminados...</div>');

  $.ajax({
    url: '/modulos/mantenimiento/tipo_vehiculo/ajax/listar_inactivos.php',
    method: 'GET',
    success: function (html) {
      $contenedor.html(html);
      setTimeout(function () {
        inicializarTablaVehiculos('tablaInactivos');
      }, 50);
    },
    error: function (xhr, status, error) {
      console.error('❌ Error al cargar inactivos:', error);
      $contenedor.html('<div class="alert alert-danger text-center"><i class="fas fa-exclamation-triangle me-2"></i> Error al cargar vehículos eliminados.</div>');
    }
  });
}

// 🧩 Abrir modal de edición y cargar formulario desde controlador
function abrirModalEditar(id) {
  $('#modalEditarVehiculo').modal('show');

  const $formContenedor = $('#contenedorFormularioEditar');
  $formContenedor.html('<div class="text-center text-muted py-3"><i class="fas fa-spinner fa-spin me-2"></i> Cargando formulario...</div>');

  $.ajax({
    url: '/modulos/mantenimiento/tipo_vehiculo/ajax/form_edit_loader.php?id=' + id,
    method: 'GET',
    success: function (html) {
      $formContenedor.html(html);
    },
    error: function () {
      $formContenedor.html('<div class="alert alert-danger text-center">❌ Error al cargar el formulario.</div>');
    }
  });
}

// 🔍 Abrir modal de visualización
function abrirModalVer(id) {
  $('#modalVerVehiculo').modal('show');

  const $contenedor = $('#contenedorFormularioVer');
  $contenedor.html('<div class="text-center text-muted py-3"><i class="fas fa-spinner fa-spin me-2"></i> Cargando detalles...</div>');

  $.ajax({
    url: '/modulos/mantenimiento/tipo_vehiculo/ajax/form_view_loader.php?id=' + id,
    method: 'GET',
    success: function (html) {
      $contenedor.html(html);
    },
    error: function () {
      $contenedor.html('<div class="alert alert-danger text-center">❌ Error al cargar los detalles.</div>');
    }
  });
}

// 🗑️ Eliminar vehículo por AJAX
function eliminarVehiculo(id) {
  $.ajax({
    url: '/modulos/mantenimiento/tipo_vehiculo/ajax/eliminar.php',
    method: 'POST',
    data: { id: id },
    success: function (respuesta) {
      if (respuesta === 'OK') {
        cargarActivos();
      } else {
        alert('⚠️ No se pudo eliminar el vehículo.');
        console.warn('Respuesta inesperada:', respuesta);
      }
    },
    error: function (xhr, status, error) {
      console.error('❌ Error al eliminar vehículo:', error);
      alert('❌ Error al eliminar. Intenta nuevamente.');
    }
  });
}

// 📊 Inicializar tabla si existe
function inicializarTablaSiExiste(idTabla) {
  const tabla = document.getElementById(idTabla);
  if (!tabla || !$.fn.DataTable || typeof $.fn.DataTable !== 'function') {
    console.warn(`⚠️ Tabla ${idTabla} no encontrada o DataTables no disponible.`);
    return;
  }

  if ($.fn.DataTable.isDataTable(tabla)) {
    $(tabla).DataTable().clear().destroy();
  }

  $(tabla).DataTable({
    language: {
      url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
    },
    order: [[0, 'desc']],
    pageLength: 10
  });
}