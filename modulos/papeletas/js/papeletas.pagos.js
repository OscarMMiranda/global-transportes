// archivo: /modulos/papeletas/js/papeletas.pagos.js

// PROTECCIÓN: eliminar duplicados del modal si existen (temporal)
(function removeDuplicateModals() {
  try {
    var nodes = document.querySelectorAll('#modalRegistrarPago');
    if (nodes.length > 1) {
      for (var i = 0; i < nodes.length - 1; i++) {
        nodes[i].parentNode.removeChild(nodes[i]);
      }
      console.warn('Se eliminaron modales duplicados:', nodes.length - 1);
    }
  } catch (e) {
    console.warn('removeDuplicateModals error', e);
  }
})();

// INSTANCIA GLOBAL DEL MODAL
var modalPago = new bootstrap.Modal(document.getElementById('modalRegistrarPago'));

// UTILIDADES
function formatSoles(v) {
  v = parseFloat(v || 0);
  if (isNaN(v)) v = 0;
  return 'S/ ' + v.toFixed(2);
}

function safeParseFloat(v) {
  var n = parseFloat(v);
  return isNaN(n) ? 0 : n;
}

// LIMPIAR FORMULARIO CUANDO EL MODAL SE CIERRA
$('#modalRegistrarPago').on('hidden.bs.modal', function () {

    if ($("#formRegistrarPago").length) {
      try { $("#formRegistrarPago")[0].reset(); } catch(e) {}
    }

    $("#id_papeleta").val("");
    $("#pago_monto_total").val("");
    $("#pago_monto_descuento").val("");
    $("#pago_monto_abonado").val("");
    $("#pago_saldo_pendiente").val("");
    $("#pago_progress").css('width','0%').removeClass('bg-success bg-warning bg-danger');
    $("#papeleta_estado_badge").removeClass('bg-success bg-warning bg-secondary').text('');
    $("#contenedorHistorialPagos").html('');
    $("#papeleta_codigo").text('Código: —');
    $("#papeleta_ultima_actualizacion").text('—');

    $("#monto_pagado, #pago_fecha_pago, #pago_metodo, #pago_referencia, #pago_observacion, #btnRegistrarPago").prop('disabled', false);
    $("#monto_pagado").removeAttr('max').attr('placeholder', '');

    console.log("Modal limpiado correctamente");
});

// ABRIR MODAL Y CARGAR DATOS DE LA PAPELETA
$(document).on("click", ".btnRegistrarPago", function(e) {
    e.preventDefault();

    var id = $(this).data("id");
    var estadoNombre = ($(this).data("estado-nombre") || '').toLowerCase();

    console.log("Pago: id=", id, "estado=", estadoNombre);

    if (!id) {
        Swal.fire("Error", "ID de papeleta no encontrado", "error");
        return;
    }

    // 🔥 BLOQUEO PROFESIONAL: si ya está pagada, NO abrir modal
    if (estadoNombre === 'pagada') {
        Swal.fire({
            icon: 'info',
            title: 'Papeleta pagada',
            text: 'No se pueden registrar pagos en una papeleta ya pagada.'
        });
        return;
    }

    // Si no está pagada, continuar con el flujo normal
    $("#contenedorHistorialPagos").html('<div class="text-center text-muted py-3">Cargando...</div>');
    $("#papeleta_codigo").text('Código: —');
    $("#papeleta_ultima_actualizacion").text('—');

    $.post('/modulos/papeletas/acciones/obtener.php', { id: id }, function(res) {

        if (!res || !res.success) {
            var msg = (res && res.msg) ? res.msg : "No se pudo cargar la papeleta";
            Swal.fire("Error", msg, "error");
            return;
        }

        var p = res.data || {};

        $("#id_papeleta").val(p.id);

        if (p.codigo_papeleta) {
            $("#papeleta_codigo").text('Código: ' + p.codigo_papeleta);
        } else {
            $("#papeleta_codigo").text('Código: —');
        }

        $("#pago_monto_total").val(formatSoles(p.monto));
        $("#pago_monto_descuento").val(formatSoles(p.monto_descuento || 0));

        var montoPagadoReal = safeParseFloat(
            p.monto_pagado_real ||
            p.monto_pagado_field ||
            p.monto_pagado_sum ||
            p.monto_pagado ||
            0
        );

        $("#pago_monto_abonado").val(formatSoles(montoPagadoReal));

        var totalAPagar = safeParseFloat(p.total_a_pagar || (p.monto - (p.monto_descuento || 0)));
        var saldo = safeParseFloat(p.saldo || (totalAPagar - montoPagadoReal));
        if (saldo < 0) saldo = 0;

        $("#pago_saldo_pendiente").val(formatSoles(saldo));

        var porcentaje = safeParseFloat(
            p.porcentaje_pagado ||
            (totalAPagar > 0 ? Math.min(100, (montoPagadoReal / totalAPagar) * 100) : 0)
        );

        porcentaje = Math.max(0, Math.min(100, porcentaje));
        $("#pago_progress").css('width', porcentaje + '%').removeClass('bg-success bg-warning bg-danger');

        $("#papeleta_estado_badge").removeClass('bg-success bg-warning bg-secondary');

        if (saldo <= 0) {
            $("#pago_progress").addClass('bg-success');
            $("#papeleta_estado_badge").addClass('bg-success').text('Pagada');

            $("#monto_pagado, #pago_fecha_pago, #pago_metodo, #pago_referencia, #pago_observacion, #btnRegistrarPago")
                .prop('disabled', true);

            $("#monto_help").text('Esta papeleta ya está pagada. No se permiten nuevos abonos.');

        } else if (porcentaje > 0 && porcentaje < 100) {
            $("#pago_progress").addClass('bg-warning');
            $("#papeleta_estado_badge").addClass('bg-warning').text('Parcial');

        } else {
            $("#pago_progress").addClass('bg-danger');
            $("#papeleta_estado_badge").addClass('bg-secondary').text('Pendiente');
        }

        var maxPago = saldo;
        if (!isNaN(maxPago) && maxPago > 0) {
            $("#monto_pagado").attr('max', maxPago.toFixed(2));
            $("#monto_pagado").attr('placeholder', 'Máx S/ ' + maxPago.toFixed(2));
            $("#monto_help").text('Máx S/ ' + maxPago.toFixed(2) + '. Use punto para decimales.');
        } else {
            $("#monto_pagado").removeAttr('max');
            $("#monto_pagado").attr('placeholder', '');
            $("#monto_help").text('Máx S/ 0.00');
        }

        if (p.updated_at) {
            $("#papeleta_ultima_actualizacion").text(p.updated_at);
        } else if (p.ultima_fecha_pago) {
            $("#papeleta_ultima_actualizacion").text(p.ultima_fecha_pago);
        } else {
            $("#papeleta_ultima_actualizacion").text('—');
        }

        $("#contenedorHistorialPagos").html('<div class="text-center text-muted py-3">Cargando historial...</div>');

        $.ajax({
          url: '/modulos/papeletas/acciones/ver_pagos.php',
          method: 'POST',
          data: { id: id },
          dataType: 'json',
          timeout: 10000,
          success: function(r) {
            if (r && r.success && r.html) {
              $("#contenedorHistorialPagos").html(r.html);
            } else {
              $("#contenedorHistorialPagos").html('<div class="text-danger text-center py-3">No se pudo cargar historial</div>');
            }
          },
          error: function(xhr, status, err) {
            $("#contenedorHistorialPagos").html('<div class="text-danger text-center py-3">Error al cargar historial</div>');
          }
        });

        modalPago.show();

    }, 'json').fail(function() {
        Swal.fire("Error", "Error de conexión al servidor", "error");
    });
});

// REGISTRAR PAGO
$(document).on("click", "#btnRegistrarPago", function() {

    var monto = safeParseFloat($("#monto_pagado").val());
    var maxAttr = $("#monto_pagado").attr('max');
    var max = maxAttr ? safeParseFloat(maxAttr) : null;

    if (isNaN(monto) || monto <= 0) {
        Swal.fire("Error", "Ingrese un monto válido mayor a 0", "error");
        return;
    }

    if (max !== null && monto > max) {
        Swal.fire("Error", "El monto excede el saldo pendiente (máx S/ " + max.toFixed(2) + ")", "error");
        return;
    }

    $("#btnRegistrarPago").prop('disabled', true);

    $.post(
        '/modulos/papeletas/acciones/registrar_pago.php',
        $("#formRegistrarPago").serialize(),
        function(res) {

            $("#btnRegistrarPago").prop('disabled', false);

            if (res && res.success) {

                Swal.fire("Éxito", res.msg || "Pago registrado correctamente", "success");

                modalPago.hide();

                if (typeof cargarPapeletas === "function") {
                    cargarPapeletas();
                }

            } else {
                Swal.fire("Error", res.msg || "No se pudo registrar el pago", "error");
            }

        },
        'json'
    ).fail(function() {
        $("#btnRegistrarPago").prop('disabled', false);
        Swal.fire("Error", "Error de conexión al servidor", "error");
    });

});

