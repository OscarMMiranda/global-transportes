// archivo: /modulos/papeletas/js/papeletas.pagos.prof.js
// Requiere: jQuery, Bootstrap 5, SweetAlert2

var modalPago = new bootstrap.Modal(document.getElementById('modalRegistrarPago'));

function formatSoles(v) {
  return 'S/ ' + (isNaN(v) ? '0.00' : parseFloat(v).toFixed(2));
}

function resetModalFields() {
  $("#formRegistrarPago")[0].reset();
  $("#id_papeleta").val("");
  $("#pago_monto_total").val("");
  $("#pago_monto_descuento").val("");
  $("#pago_monto_abonado").val("");
  $("#pago_saldo_pendiente").val("");
  $("#pago_progress").css('width', '0%').removeClass('bg-success bg-warning bg-danger');
  $("#papeleta_estado_badge").removeClass('bg-success bg-warning bg-secondary').text('');
  $("#contenedorHistorialPagos").html('');
  $("#papeleta_codigo").text('Código: —');
  $("#papeleta_ultima_actualizacion").text('—');
  $("#monto_help").text('Máx S/ 0.00');
  $("#monto_pagado").removeAttr('max').prop('disabled', false);
  $("#pago_fecha_pago, #pago_metodo, #pago_referencia, #pago_observacion, #btnRegistrarPago").prop('disabled', false);
}

function setBadgeAndProgress(data) {
  var total = parseFloat(data.total_a_pagar || (data.monto - data.monto_descuento));
  var pagado = parseFloat(data.monto_pagado_real || 0);
  var saldo = parseFloat(data.saldo || (total - pagado));
  var pct = total > 0 ? Math.min(100, (pagado / total) * 100) : 0;

  var $bar = $("#pago_progress");
  $bar.css('width', pct + '%').removeClass('bg-success bg-warning bg-danger');

  var $badge = $("#papeleta_estado_badge");
  $badge.removeClass('bg-success bg-warning bg-secondary');

  if (saldo <= 0) {
    $bar.addClass('bg-success');
    $badge.addClass('bg-success').text('Pagada');
  } else if (pct > 0 && pct < 100) {
    $bar.addClass('bg-warning');
    $badge.addClass('bg-warning').text('Parcial');
  } else {
    $bar.addClass('bg-danger');
    $badge.addClass('bg-secondary').text('Pendiente');
  }
}

function bloquearFormularioSiPagada(saldo) {
  var pagada = parseFloat(saldo) <= 0;
  $("#monto_pagado, #pago_fecha_pago, #pago_metodo, #pago_referencia, #pago_observacion, #btnRegistrarPago").prop('disabled', pagada);
  if (pagada) {
    $("#monto_help").text('Esta papeleta ya está pagada. No se permiten nuevos abonos.');
  }
}

// Abrir modal y cargar datos
$(document).on("click", ".btnRegistrarPago", function() {
  var id = $(this).data("id");
  if (!id) {
    Swal.fire("Error", "ID de papeleta no encontrado", "error");
    return;
  }

  resetModalFields();
  $("#contenedorHistorialPagos").html('<div class="text-center text-muted py-3">Cargando...</div>');

  $.post('/modulos/papeletas/acciones/obtener.php', { id: id }, function(res) {
    if (!res || !res.success) {
      Swal.fire("Error", (res && res.msg) ? res.msg : "No se pudo cargar la papeleta", "error");
      return;
    }

    var p = res.data;

    $("#id_papeleta").val(p.id);
    $("#papeleta_codigo").text('Código: ' + (p.codigo_papeleta || '—'));
    $("#pago_monto_total").val(formatSoles(p.monto));
    $("#pago_monto_descuento").val(formatSoles(p.monto_descuento || 0));
    $("#pago_monto_abonado").val(formatSoles(p.monto_pagado_real || 0));
    $("#pago_saldo_pendiente").val(formatSoles(p.saldo || (p.total_a_pagar - p.monto_pagado_real)));
    $("#papeleta_ultima_actualizacion").text(p.updated_at || p.ultima_fecha_pago || '—');

    setBadgeAndProgress(p);

    // bloquear si pagada
    bloquearFormularioSiPagada(p.saldo);

    // ajustar max y placeholder
    var maxPago = parseFloat(p.saldo || (p.total_a_pagar - p.monto_pagado_real));
    if (!isNaN(maxPago) && maxPago > 0) {
      $("#monto_pagado").attr('max', maxPago.toFixed(2));
      $("#monto_help").text('Máx S/ ' + maxPago.toFixed(2));
    } else {
      $("#monto_pagado").removeAttr('max');
      $("#monto_help").text('Máx S/ 0.00');
    }

    // cargar historial
    $.post('/modulos/papeletas/acciones/ver_pagos.php', { id: id }, function(r) {
      if (r && r.success) {
        $("#contenedorHistorialPagos").html(r.html);
      } else {
        $("#contenedorHistorialPagos").html('<div class="text-danger text-center py-3">' + ((r && r.html) ? r.html : 'No se pudo cargar historial') + '</div>');
      }
    }, 'json');

    modalPago.show();

  }, 'json').fail(function() {
    Swal.fire("Error", "Error de conexión al servidor", "error");
  });
});

// Registrar pago
$(document).on("click", "#btnRegistrarPago", function() {
  var monto = parseFloat($("#monto_pagado").val());
  var maxAttr = $("#monto_pagado").attr('max');
  var max = (typeof maxAttr !== 'undefined' && maxAttr !== false && maxAttr !== '') ? parseFloat(maxAttr) : null;

  if (isNaN(monto) || monto <= 0) {
    Swal.fire("Error", "Ingrese un monto válido", "error");
    return;
  }
  if (max !== null && !isNaN(max) && monto > max) {
    Swal.fire("Error", "El monto excede el saldo pendiente (máx S/ " + max.toFixed(2) + ")", "error");
    return;
  }

  var data = $("#formRegistrarPago").serialize();

  $("#btnRegistrarPago").prop('disabled', true);

  $.post('/modulos/papeletas/acciones/registrar_pago.php', data, function(res) {
    $("#btnRegistrarPago").prop('disabled', false);

    if (res && res.success) {
      Swal.fire("Éxito", res.msg || "Pago registrado correctamente", "success");
      modalPago.hide();
      if (typeof cargarPapeletas === "function") cargarPapeletas();
    } else {
      Swal.fire("Error", (res && res.msg) ? res.msg : "No se pudo registrar el pago", "error");
    }
  }, 'json').fail(function() {
    $("#btnRegistrarPago").prop('disabled', false);
    Swal.fire("Error", "Error de conexión al servidor", "error");
  });
});
