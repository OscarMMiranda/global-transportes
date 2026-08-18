// archivo: /modulos/papeletas/js/papeletas.descuentos.js
// Requisitos: jQuery, Bootstrap 5, SweetAlert2 (opcional)

(function () {
  'use strict';

  // Helpers
  function formatSoles(v) {
    v = parseFloat(v || 0);
    if (isNaN(v)) v = 0;
    return 'S/ ' + v.toFixed(2);
  }
  function safeParseFloat(v) {
    var n = parseFloat(v);
    return isNaN(n) ? 0 : n;
  }

  // Asegurar instancia del modal (Bootstrap 5)
  var modalEl = document.getElementById('modalRegistrarDescuento');
  function getBsModal() {
    if (!modalEl) return null;
    return bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
  }

  // Inicializar UI adicional si no existe (por compatibilidad)
  function ensurePercentageUI() {
    if ($('#descuento_porcentaje').length) return;

    var $montoGroup = $('#descuento_monto').closest('.mb-3');
    if ($montoGroup.length) {
      var html = '\
      <div class="mb-3" id="grupo_tipo_entrada">\
        <label class="form-label">Aplicar como</label>\
        <div>\
          <label class="me-3"><input type="radio" name="descuento_tipo_input" value="monto" checked> Monto</label>\
          <label><input type="radio" name="descuento_tipo_input" value="porcentaje"> Porcentaje</label>\
        </div>\
      </div>\
      <div class="mb-3" id="grupo_porcentaje" style="display:none;">\
        <label class="form-label">Porcentaje (%)</label>\
        <input type="number" step="0.01" class="form-control" name="porcentaje" id="descuento_porcentaje" placeholder="Ej: 10">\
        <div id="descuento_monto_preview" class="small text-muted mt-1"></div>\
      </div>';
      $montoGroup.after(html);
    }
  }

  // Mostrar/ocultar inputs según selección monto/porcentaje
  function bindTipoEntradaToggle() {
    $(document).on('change', 'input[name="descuento_tipo_input"]', function () {
      var val = $(this).val();
      if (val === 'porcentaje') {
        $('#descuento_monto').closest('.mb-3').hide();
        $('#grupo_porcentaje').show();
      } else {
        $('#grupo_porcentaje').hide();
        $('#descuento_monto').closest('.mb-3').show();
      }
    });
  }

  // Calcular y mostrar preview cuando se escribe porcentaje
  function bindPorcentajePreview() {
    $(document).on('input', '#descuento_porcentaje', function () {
      var saldo = parseFloat($('#descuento_monto_pendiente').data('saldo') || 0);
      var pct = parseFloat($(this).val() || 0);
      if (isNaN(pct) || pct <= 0 || isNaN(saldo)) {
        $('#descuento_monto_preview').text('');
        return;
      }
      var montoCalc = Math.round((saldo * (pct / 100)) * 100) / 100;
      $('#descuento_monto_preview').text('Equivale a: ' + formatSoles(montoCalc));
    });
  }

  // 🔥 VALIDACIÓN PROFESIONAL: bloquear modal si la papeleta está pagada
  $(document).on('click', '.btnRegistrarDescuento', function (e) {
    e.preventDefault();

    var id = $(this).data('id');
    var estadoNombre = ($(this).data('estado-nombre') || '').toLowerCase();

    console.log('btnRegistrarDescuento clicked, id=', id, 'estadoNombre=', estadoNombre);

    if (!id) {
      if (window.Swal) Swal.fire('Error', 'ID de papeleta no encontrado', 'error');
      console.error('Falta data-id en el botón .btnRegistrarDescuento');
      return;
    }

    // 🔥 Validación por nombre (más robusta)
    if (estadoNombre === 'pagada') {
      Swal.fire({
        icon: 'info',
        title: 'Papeleta pagada',
        text: 'No se pueden registrar descuentos en una papeleta ya pagada.',
      });
      return; // NO abrir modal
    }

    // Asegurar UI de porcentaje y bindings
    ensurePercentageUI();
    bindTipoEntradaToggle();
    bindPorcentajePreview();

    // Reset formulario y mostrar indicador
    var $form = $('#formRegistrarDescuento');
    if ($form.length) $form[0].reset();
    $('#descuento_papeleta_id').val(id);
    $('#descuento_monto_pendiente').val('Cargando...');
    $('#descuento_monto_pendiente').data('saldo', 0);
    $('#descuento_monto_preview').text('');

    // Llamada para obtener datos de la papeleta
    $.ajax({
      url: '/modulos/papeletas/acciones/obtener.php',
      method: 'POST',
      data: { id: id },
      dataType: 'json',
      timeout: 8000,
      success: function (res) {
        console.log('obtener.php response:', res);
        if (!res || !res.success) {
          var msg = (res && (res.msg || res.message)) ? (res.msg || res.message) : 'No se pudo cargar la papeleta';
          $('#descuento_monto_pendiente').val('—');
          if (window.Swal) Swal.fire('Error', msg, 'error');
          return;
        }

        var p = res.data || {};
        var monto = safeParseFloat(p.monto);
        var monto_desc = safeParseFloat(p.monto_descuento || 0);
        var monto_pagado = safeParseFloat(p.monto_pagado_real || p.monto_pagado || 0);
        var totalAPagar = monto - monto_desc;
        var saldo = totalAPagar - monto_pagado;
        if (saldo < 0) saldo = 0;

        $('#descuento_monto_pendiente').val(formatSoles(saldo));
        $('#descuento_monto_pendiente').data('saldo', saldo);

        var bs = getBsModal();
        if (!bs) {
          console.error('Modal #modalRegistrarDescuento no encontrado en el DOM');
          if (window.Swal) Swal.fire('Error', 'Modal no encontrado', 'error');
          return;
        }
        bs.show();
      },
      error: function (xhr, status, err) {
        console.error('Error AJAX obtener.php', status, err, xhr && xhr.responseText);
        $('#descuento_monto_pendiente').val('Error');
        if (window.Swal) Swal.fire('Error', 'No se pudo obtener datos de la papeleta', 'error');
      }
    });
  });

  // Envío del descuento
  $(document).on('click', '#btnRegistrarDescuento', function (e) {
    e.preventDefault();

    var $btn = $(this);
    var $form = $('#formRegistrarDescuento');
    if ($form.length === 0) {
      console.error('formRegistrarDescuento no encontrado');
      return;
    }

    var papeleta_id = $('#descuento_papeleta_id').val();
    var tipo = $('#descuento_tipo').val();
    var fecha = $('#descuento_fecha').val();
    var saldoActual = safeParseFloat($('#descuento_monto_pendiente').data('saldo'));

    // Determinar si se usa porcentaje o monto
    var modo = $('input[name="descuento_tipo_input"]:checked').val() || 'monto';
    var monto = 0;
    var porcentaje = '';

    if (modo === 'porcentaje') {
      porcentaje = $('#descuento_porcentaje').val();
      porcentaje = (typeof porcentaje === 'string') ? porcentaje.replace(',', '.') : porcentaje;
      porcentaje = parseFloat(porcentaje || 0);
      if (isNaN(porcentaje) || porcentaje <= 0) {
        if (window.Swal) Swal.fire('Error', 'Ingrese un porcentaje válido', 'error');
        return;
      }
      monto = Math.round((saldoActual * (porcentaje / 100)) * 100) / 100;
    } else {
      monto = safeParseFloat($('#descuento_monto').val());
      porcentaje = '';
    }

    // Validaciones cliente
    if (!papeleta_id) { if (window.Swal) Swal.fire('Error', 'ID de papeleta inválido', 'error'); return; }
    if (!tipo) { if (window.Swal) Swal.fire('Error', 'Seleccione el tipo de descuento', 'error'); return; }
    if (isNaN(monto) || monto <= 0) { if (window.Swal) Swal.fire('Error', 'Ingrese un monto de descuento válido', 'error'); return; }
    if (!fecha) { if (window.Swal) Swal.fire('Error', 'Seleccione la fecha del descuento', 'error'); return; }
    if (!isNaN(saldoActual) && saldoActual >= 0 && monto > saldoActual) {
      if (window.Swal) Swal.fire('Error', 'El monto de descuento no puede exceder el saldo pendiente (Máx ' + formatSoles(saldoActual) + ')', 'error');
      return;
    }

    // Preparar datos para enviar
    var payloadArray = $form.serializeArray();
    var obj = {};
    payloadArray.forEach(function (item) { obj[item.name] = item.value; });

    if (modo === 'porcentaje') {
      obj['porcentaje'] = porcentaje;
      obj['monto'] = monto.toFixed(2);
    } else {
      obj['porcentaje'] = '';
      obj['monto'] = (typeof obj['monto'] !== 'undefined') ? obj['monto'] : monto.toFixed(2);
    }

    var dataToSend = $.param(obj);
    console.log('Enviando registrar_descuento.php payload:', obj);

    // Confirmación
    $btn.prop('disabled', true);
    var confirmHtml = 'Aplicar descuento de <strong>' + formatSoles(monto) + '</strong> a la papeleta?';
    if (modo === 'porcentaje') confirmHtml = 'Aplicar descuento de <strong>' + porcentaje + '%</strong> (equivale a ' + formatSoles(monto) + ')?';

    var confirmPromise = window.Swal ? Swal.fire({
      title: 'Confirmar',
      html: confirmHtml,
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Sí, aplicar',
      cancelButtonText: 'Cancelar'
    }) : Promise.resolve({ isConfirmed: true });

    confirmPromise.then(function (result) {
      if (!result || !result.isConfirmed) {
        $btn.prop('disabled', false);
        return;
      }

      // Enviar por AJAX
      $.ajax({
        url: '/modulos/papeletas/acciones/registrar_descuento.php',
        method: 'POST',
        data: dataToSend,
        dataType: 'json',
        timeout: 10000,
        success: function (res) {
          $btn.prop('disabled', false);
          console.log('registrar_descuento.php response:', res);
          var ok = (res && (res.success === true || res.ok === true));
          if (ok) {
            if (window.Swal) Swal.fire('Éxito', (res.msg || 'Descuento registrado correctamente'), 'success');
            try { getBsModal().hide(); } catch (e) {}
            if (typeof cargarPapeletas === 'function') cargarPapeletas();
          } else {
            var msg = (res && (res.msg || res.message || res.html)) ? (res.msg || res.message || res.html) : 'No se pudo registrar el descuento';
            if (window.Swal) Swal.fire('Error', msg, 'error');
            else console.error('Error al registrar descuento:', msg);
          }
        },
        error: function (xhr, status, err) {
          $btn.prop('disabled', false);
          console.error('Error AJAX registrar_descuento.php', status, err, xhr && xhr.responseText);
          if (window.Swal) Swal.fire('Error', 'Error de conexión o respuesta inválida', 'error');
        }
      });
    });
  });

})();
