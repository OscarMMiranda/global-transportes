function listarLugares(entidad_id) {
  $.get('/modulos/mantenimiento/lugares/actions/listar.php', { entidad_id: entidad_id }, function (html) {
    $('#bodyLugares').html(html);
  });
}

function abrirModalLugar(id) {
  $('#formLugar')[0].reset();
  $('#id_lugar').val('');

  let entidad_id = $('#entidad_id').val();
  if (!entidad_id) {
    console.warn('⚠️ entidad_id no definido en DOM');
    alert('Error: entidad_id no está definido');
    return;
  }
  $('#entidad_id_lugar').val(entidad_id);

  cargarDepartamentos();
  cargarTiposLugar();

  if (!id) {
    $('#modalLugar').modal('show');

    // ✅ Carga encadenada si hay valores preseleccionados
    setTimeout(() => {
      let dep_id = $('#departamento_id').val();
      if (dep_id) {
        cargarProvincias(() => {
          let prov_id = $('#provincia_id').val();
          if (prov_id) {
            cargarDistritos();
          }
        });
      }
    }, 300);

    return;
  }

  $.get('/modulos/mantenimiento/lugares/actions/buscar.php', { id: id }, function (lugar) {
    if (lugar.error) return alert(lugar.error);

    $('#id_lugar').val(lugar.id);
    $('#nombre_lugar').val(lugar.nombre);
    $('#direccion_lugar').val(lugar.direccion);
    $('#tipo_id_lugar').val(lugar.tipo_id);

    $('#departamento_id').val(lugar.departamento_id);
    cargarProvincias(function () {
      $('#provincia_id').val(lugar.provincia_id);
      cargarDistritos(function () {
        $('#distrito_id').val(lugar.distrito_id);
      });
    });

    $('#modalLugar').modal('show');
  });
}

function guardarLugar() {
  let nombre   = $('#nombre_lugar').val().trim();
  let tipo     = $('#tipo_id_lugar').val();
  let distrito = $('#distrito_id').val();
  let entidad  = $('#entidad_id_lugar').val();

  if (!nombre || !tipo || !distrito || !entidad) {
    alert('Completa todos los campos obligatorios.');
    return;
  }

  $('#btnGuardarLugar').prop('disabled', true);

  let datos = $('#formLugar').serialize();

  $.ajax({
    url: '/modulos/mantenimiento/lugares/actions/guardar.php',
    type: 'POST',
    data: datos,
    dataType: 'json',
    success: function (json) {
      $('#btnGuardarLugar').prop('disabled', false);

      if (json.estado === 'ok') {
        $('#modalLugar').modal('hide');
        listarLugares(entidad);
        $('#panelAuditoria').load('/modulos/mantenimiento/lugares/auditoria_lugar.php?id=' + json.id);
      } else {
        alert(json.mensaje || 'Error inesperado');
        console.warn(json.campos || '');
      }
    },
    error: function (xhr, status, error) {
      $('#btnGuardarLugar').prop('disabled', false);
      alert('Error al guardar: ' + error);
      console.error(xhr.responseText);
    }
  });
}

function eliminarLugar(id) {
  if (!confirm('¿Eliminar este lugar de forma lógica?')) return;
  $.get('/modulos/mantenimiento/lugares/actions/borrar.php', { id: id }, function (r) {
    if (r === 'ok') {
      listarLugares($('#entidad_id_lugar').val());
    } else {
      alert(r);
    }
  });
}

function restaurarLugar(id) {
  $.get('/modulos/mantenimiento/lugares/actions/restaurar.php', { id: id }, function (r) {
    if (r === 'ok') {
      listarLugares($('#entidad_id_lugar').val());
    } else {
      alert(r);
    }
  });
}

function cargarDepartamentos() {
  $.get('/modulos/comunes/territorio/actions/departamentos.php', function (html) {
    $('#departamento_id').html(html);
    $('#provincia_id').html('<option value="">Seleccione provincia</option>');
    $('#distrito_id').html('<option value="">Seleccione distrito</option>');
  });
}

function cargarProvincias(callback) {
  var dep_id = $('#departamento_id').val();
  if (!dep_id) {
    $('#provincia_id').html('<option value="">Seleccione provincia</option>');
    $('#distrito_id').html('<option value="">Seleccione distrito</option>');
    return;
  }

  $.get('/modulos/comunes/territorio/actions/provincias.php', { departamento_id: dep_id }, function (html) {
    $('#provincia_id').html(html);
    $('#distrito_id').html('<option value="">Seleccione distrito</option>');
    if (callback) callback();
  });
}

function cargarDistritos(callback) {
  var prov_id = $('#provincia_id').val();
  if (!prov_id) {
    $('#distrito_id').html('<option value="">Seleccione distrito</option>');
    return;
  }

  $.get('/modulos/comunes/territorio/actions/distritos.php', { provincia_id: prov_id }, function (html) {
    $('#distrito_id').html(html);
    if (callback) callback();
  });
}

function cargarTiposLugar() {
  $.get('/modulos/comunes/territorio/actions/tipos.php', function (html) {
    $('#tipo_id_lugar').html(html);
  });
}

// ✅ Listeners para carga dinámica
$(document).ready(function () {
  $('#departamento_id').on('change', function () {
    cargarProvincias();
  });

  $('#provincia_id').on('change', function () {
    cargarDistritos();
  });
});