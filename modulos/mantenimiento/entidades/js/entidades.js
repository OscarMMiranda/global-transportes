$(document).ready(function () {
  // 🔹 Al abrir el modal de creación, cargar tipos y departamentos
  $('#modalEntidad').on('shown.bs.modal', function () {
    cargarTipos();
    cargarDepartamentos();
    $('#provincia_id').html('<option value="">-- Seleccionar --</option>');
    $('#distrito_id').html('<option value="">-- Seleccionar --</option>');
  });

  // 🔹 Carga jerárquica: departamento → provincia → distrito (crear)
  $('#departamento_id').on('change', function () {
    var id = $(this).val();
    if (!id) {
      $('#provincia_id').html('<option value="">-- Seleccionar --</option>');
      $('#distrito_id').html('<option value="">-- Seleccionar --</option>');
      return;
    }
    $.get('/modulos/mantenimiento/entidades/ajax/provincias.php?departamento_id=' + id, function (html) {
      $('#provincia_id').html(html);
      $('#distrito_id').html('<option value="">-- Seleccionar --</option>');
    });
  });

  $('#provincia_id').on('change', function () {
    var id = $(this).val();
    if (!id) {
      $('#distrito_id').html('<option value="">-- Seleccionar --</option>');
      return;
    }
    $.get('/modulos/mantenimiento/entidades/ajax/distritos.php?provincia_id=' + id, function (html) {
      $('#distrito_id').html(html);
    });
  });

  // 🔹 Funciones reutilizables
  function cargarTipos() {
    $('#tipo_id').html('<option>Cargando...</option>');
    $.get('/modulos/mantenimiento/entidades/ajax/tipos.php')
      .done(function (html) {
        $('#tipo_id').html(html);
      })
      .fail(function () {
        $('#tipo_id').html('<option value="">Error al cargar tipos</option>');
      });
  }

  function cargarDepartamentos() {
    $('#departamento_id').html('<option>Cargando...</option>');
    $.get('/modulos/mantenimiento/entidades/ajax/departamentos.php')
      .done(function (html) {
        $('#departamento_id').html(html);
      })
      .fail(function () {
        $('#departamento_id').html('<option value="">Error al cargar departamentos</option>');
      });
  }

  function cargarDepartamentosEditar(dep, prov, dist) {
    $('#departamento_id_editar').html('<option>Cargando...</option>');
    $.get('/modulos/mantenimiento/entidades/ajax/departamentos.php', function (html) {
      $('#departamento_id_editar').html(html).val(dep);

      $.get('/modulos/mantenimiento/entidades/ajax/provincias.php?departamento_id=' + dep, function (html) {
        $('#provincia_id_editar').html(html).val(prov);

        $.get('/modulos/mantenimiento/entidades/ajax/distritos.php?provincia_id=' + prov, function (html) {
          $('#distrito_id_editar').html(html).val(dist);
        });
      });
    });

    // 🔹 Eventos jerárquicos (editar)
    $('#departamento_id_editar').on('change', function () {
      var id = $(this).val();
      $('#provincia_id_editar').html('<option>Cargando...</option>');
      $('#distrito_id_editar').html('<option value="">-- Seleccionar --</option>');
      $.get('/modulos/mantenimiento/entidades/ajax/provincias.php?departamento_id=' + id, function (html) {
        $('#provincia_id_editar').html(html);
      });
    });

    $('#provincia_id_editar').on('change', function () {
      var id = $(this).val();
      $('#distrito_id_editar').html('<option>Cargando...</option>');
      $.get('/modulos/mantenimiento/entidades/ajax/distritos.php?provincia_id=' + id, function (html) {
        $('#distrito_id_editar').html(html);
      });
    });
  }

  // 🔹 Crear entidad vía AJAX
  window.crearEntidad = function () {
    var datos = $('#formCrearEntidad').serialize();
    $.post('/modulos/mantenimiento/entidades/actions/crear.php', datos, function (respuesta) {
      if (respuesta === 'ok') {
        $('#modalEntidad').modal('hide');
        $('#tabActivos').load('/modulos/mantenimiento/entidades/controllers/ListFragment.php?estado=activo');
      } else {
        $('#formCrearEntidad .form-group:first').before('<div class="alert alert-danger">Error: ' + respuesta + '</div>');
      }
    }).fail(function () {
      $('#formCrearEntidad .form-group:first').before('<div class="alert alert-danger">Error al enviar datos.</div>');
    });
  };

  // 🔹 Ver entidad en modal
  window.verEntidad = function (id) {
    if (!id || isNaN(id)) {
      alert('ID inválido');
      return;
    }

    $.get('/modulos/mantenimiento/entidades/controllers/ver.php', { id: id }, function (html) {
      $('#modalContenidoVer').html(html);
      $('#modalVerEntidad').modal('show');
    }).fail(function (jqXHR, textStatus, errorThrown) {
      var mensaje = 'Error: ' + errorThrown + ' (' + textStatus + ')';
      $('#modalContenidoVer').html('<div class="modal-body"><div class="alert alert-danger">' + mensaje + '</div></div>');
      $('#modalVerEntidad').modal('show');
    });
  };

 // 🔹 Editar entidad en modal
window.editarEntidad = function (id) {
  if (!id || isNaN(id)) {
    alert('ID inválido');
    return;
  }

  $.get('/modulos/mantenimiento/entidades/views/editar.php', { id: id }, function (html) {
    $('#modalContenidoEditar').html(html);
    $('#modalEditarEntidad').modal('show');

    // 🔧 Ejecutar carga jerárquica con valores actuales
	cargarTiposEditar(); 
    cargarUbigeoEditar();
  }).fail(function (jqXHR, textStatus, errorThrown) {
    var mensaje = 'Error: ' + errorThrown + ' (' + textStatus + ')';
    $('#modalContenidoEditar').html('<div class="modal-body"><div class="alert alert-danger">' + mensaje + '</div></div>');
    $('#modalEditarEntidad').modal('show');
  });
};

  // 🔹 Actualizar entidad vía AJAX
  window.actualizarEntidad = function () {
    var datos = $('#formEditarEntidad').serialize();
    $.post('/modulos/mantenimiento/entidades/actions/actualizar.php', datos, function (respuesta) {
      if (respuesta === 'ok') {
        $('#modalEditarEntidad').modal('hide');
        $('#tabActivos').load('/modulos/mantenimiento/entidades/controllers/ListFragment.php?estado=activo');
      } else {
        $('#formEditarEntidad .form-group:first').before('<div class="alert alert-danger">Error: ' + respuesta + '</div>');
      }
    }).fail(function () {
      $('#formEditarEntidad .form-group:first').before('<div class="alert alert-danger">Error al enviar datos.</div>');
    });
  };

  // 🔹 Delegación de eventos
  $(document).on('click', '.btn-ver-entidad', function () {
    var id = $(this).data('id');
    console.log("🧪 Botón Ver activado con ID:", id);
    verEntidad(id);
  });

  $(document).on('click', '.btn-editar-entidad', function () {
    var id = $(this).data('id');
    console.log("✏️ Editar entidad ID:", id);
    editarEntidad(id);
  });


  	function cargarUbigeoEditar() {
  const depSelect  = $('#departamento_id_editar');
  const provSelect = $('#provincia_id_editar');
  const distSelect = $('#distrito_id_editar');

  const depValor   = depSelect.data('valor');
  const provValor  = provSelect.data('valor');
  const distValor  = distSelect.data('valor');

  // Cargar departamentos
  $.get('/modulos/mantenimiento/entidades/ajax/departamentos.php', function (html) {
    depSelect.html(html).val(depValor);

    // Cargar provincias
    $.get('/modulos/mantenimiento/entidades/ajax/provincias.php?departamento_id=' + depValor, function (html) {
      provSelect.html(html).val(provValor);

      // Cargar distritos
      $.get('/modulos/mantenimiento/entidades/ajax/distritos.php?provincia_id=' + provValor, function (html) {
        distSelect.html(html).val(distValor);
      });
    });
  });

  // Activar eventos jerárquicos
  depSelect.off('change').on('change', function () {
    const id = $(this).val();
    provSelect.html('<option value="">-- Cargando provincias --</option>');
    distSelect.html('<option value="">-- Seleccionar distrito --</option>');
    $.get('/modulos/mantenimiento/entidades/ajax/provincias.php?departamento_id=' + id, function (html) {
      provSelect.html(html);
    });
  });

  provSelect.off('change').on('change', function () {
    const id = $(this).val();
    distSelect.html('<option value="">-- Cargando distritos --</option>');
    $.get('/modulos/mantenimiento/entidades/ajax/distritos.php?provincia_id=' + id, function (html) {
      distSelect.html(html);
    });
  });
}




function cargarTiposEditar() {
  const tipoSelect = $('#tipo_id_editar');
  const tipoValor = tipoSelect.data('valor');

  tipoSelect.html('<option>Cargando...</option>');
  $.get('/modulos/mantenimiento/entidades/ajax/tipos.php', function (html) {
    tipoSelect.html(html).val(tipoValor);
  }).fail(function () {
    tipoSelect.html('<option value="">Error al cargar tipos</option>');
  });
}

});