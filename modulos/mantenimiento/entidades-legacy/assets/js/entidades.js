(function($){
  'use strict';

  var guardandoEntidad = false;

  // 🔄 Cargar provincias
  function cargarProvincias(depId, callback) {
    $('#provincia').html('<option value="">— Cargando provincias —</option>');
    $('#distrito').html('<option value="">— Selecciona distrito —</option>');

    $.ajax({
      url: '/modulos/mantenimiento/entidades/ajax/ajax_provincias.php',
      data: { departamento_id: depId },
      dataType: 'html'
    })
    .done(function(html){
      $('#provincia').html(html);
      if (typeof callback === 'function') callback();
    })
    .fail(function(){
      console.error('❌ Provincias: error al cargar');
      $('#provincia').html('<option value="">[Error de datos]</option>');
    });
  }

  // 🔄 Cargar distritos
  function cargarDistritos(provId, callback) {
    $('#distrito').html('<option value="">— Cargando distritos —</option>');

    $.ajax({
      url: '/modulos/mantenimiento/entidades/ajax/ajax_distritos.php',
      data: { provincia_id: provId },
      dataType: 'html'
    })
    .done(function(html){
      $('#distrito').html(html);
      if (typeof callback === 'function') callback();
    })
    .fail(function(){
      console.error('❌ Distritos: error al cargar');
      $('#distrito').html('<option value="">[Error de datos]</option>');
    });
  }

  // 🚀 Precarga al abrir el modal
  $(document).on('shown.bs.modal', '#modalNuevaEntidad', function () {
    var depId  = $('#departamento').val();
    var provId = parseInt($('#provincia').data('valor'), 10);
    var distId = parseInt($('#distrito').data('valor'), 10);

    $('#provincia').html('<option value="">-- Seleccionar --</option>');
    $('#distrito').html('<option value="">-- Seleccionar --</option>');

    if (depId > 0 && provId > 0 && distId > 0) {
      cargarProvincias(depId, function () {
        $('#provincia').val(provId);
        cargarDistritos(provId, function () {
          $('#distrito').val(distId);
        });
      });
    }
  });

  // 🔁 Cambio dinámico de selects
  $(document).on('change', '#departamento', function () {
    cargarProvincias(this.value);
    $('#distrito').html('<option value="">-- Seleccionar distrito --</option>');
  });

  $(document).on('change', '#provincia', function () {
    cargarDistritos(this.value);
  });

  // 🛡️ Envío AJAX con bloqueo y trazabilidad
  $(document).on('submit', '#formNuevaEntidad', function (e) {
    e.preventDefault();
    if (guardandoEntidad) return;
    guardandoEntidad = true;

    var datos = $(this).serialize();

    $.ajax({
      type: 'POST',
      url: '/modulos/mantenimiento/entidades/controllers/GuardarEntidad.php',
      data: datos,
      dataType: 'text' // ← capturamos texto para parseo manual
    })
    .done(function(rawResponse) {
      guardandoEntidad = false;

      try {
        console.log('🧪 Respuesta cruda:', rawResponse);

        var inicio = rawResponse.indexOf('{');
        if (inicio === -1) throw new Error('No se encontró JSON en la respuesta');

        var limpio = rawResponse.substring(inicio).trim();
        var res = JSON.parse(limpio);

        if (res.estado === 'ok') {
          alert('✅ ' + (res.mensaje || 'Entidad registrada correctamente.'));
          $('#modalNuevaEntidad').modal('hide');
          window.location.href = '/modulos/mantenimiento/entidades/';
        } else if (typeof res.mensaje === 'string') {
          alert('❌ ' + res.mensaje);
        } else {
          alert('❌ Error desconocido: ' + JSON.stringify(res));
        }

      } catch (err) {
        console.error('❌ Error al parsear JSON:', err);
        console.error('📝 Respuesta cruda:', rawResponse);
        alert('❌ Error inesperado: ' + err.message);
      }
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
      guardandoEntidad = false;
      console.error('🚫 AJAX error:', textStatus, errorThrown);
      console.error('🖨️ Respuesta del servidor:', jqXHR.responseText);
      alert('❌ Error de conexión al guardar');
    });
  });

  // 🧯 Interceptor global de errores
  window.onerror = function(msg, src, line, col, err) {
    console.warn('🧯 Error global capturado:', msg, err);
    return true; // ← evita que el navegador muestre el modal genérico
  };

})(jQuery);