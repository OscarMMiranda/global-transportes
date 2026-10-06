// ======================================================
// JS: ot_viaje_modal.js
// MODULO: Viajes OT
// RESPONSABILIDAD:
// - Abrir modal
// - Cargar datos OT
// - Cargar combos
// - Controlar tipo carga
// GLOBAL 2026
// ======================================================


// ------------------------------------------------------
// ABRIR MODAL
// ------------------------------------------------------
function abrirRegistrarViaje(idOT)
{
    if (!idOT) {
        return;
    }

    limpiarFormularioViaje();

    $("#modalRegistrarViaje").modal("show");

    $.ajax({

        url: "/modulos/orden_trabajo/api/ot_viaje_form_api.php",

        type: "POST",

        dataType: "json",

        data: {
            id_ot: idOT
        },

        success: function (r) {

            console.log(r);

            if (!r.ok) {

                alert(r.msg);

                return;
            }

            cargarDatosOT(r);

            cargarVehiculos(r.vehiculos);

            cargarOrigenes(r.origenes);

            cargarDestinos(r.destinos);

            cargarZonas(r.zonas);

            cargarTiposContenedor(
                r.tipos_contenedor
            );

            cargarTiposMercaderia(
                r.tipos_mercaderia
            );
        },

        error: function () {

            alert(
                "Error al obtener datos para registrar viaje"
            );

        }

    });
}


// ------------------------------------------------------
// OT
// ------------------------------------------------------
function cargarDatosOT(r)
{
    $("#rv_ot_id").val(
        r.ot.id
    );

    $("#rv_numero_ot").html(
        r.ot.numero_ot
    );

    $("#rv_cliente").html(
        r.ot.cliente
    );

    $("#rv_tipo_ot").html(
        r.ot.tipo_ot
    );

    $("#rv_empresa").html(
        r.ot.empresa
    );

    $("#rv_oc_cliente").html(
        r.ot.oc_cliente
    );

    $("#rv_tipo_documento").html(
        r.tipo_documento
    );

    $("#rv_documento_principal").html(
        r.documento_principal
    );

    $("#rv_fecha_viaje").val(
        r.ot.fecha
    );
}


// ------------------------------------------------------
// SELECT GENERICO
// ------------------------------------------------------
function llenarSelect(
    selector,
    lista,
    valueField,
    textField
)
{
    var html = '';

    html +=
        '<option value="">Seleccionar</option>';

    $.each(lista, function (i, item) {

        html +=
            '<option value="' +
            item[valueField] +
            '">' +
            item[textField] +
            '</option>';

    });

    $(selector).html(html);
}


// ------------------------------------------------------
// COMBOS
// ------------------------------------------------------
function cargarVehiculos(lista)
{
    llenarSelect(
        "#rv_vehiculo_id",
        lista,
        "id",
        "placa"
    );
}

function cargarOrigenes(lista)
{
    llenarSelect(
        "#rv_origen_id",
        lista,
        "id",
        "nombre"
    );
}

function cargarDestinos(lista)
{
    llenarSelect(
        "#rv_destino_id",
        lista,
        "id",
        "nombre"
    );
}

function cargarZonas(lista)
{
    llenarSelect(
        "#rv_zona_id",
        lista,
        "id",
        "nombre"
    );
}

function cargarTiposContenedor(lista)
{
    llenarSelect(
        "#rv_contenedor_tipo_id",
        lista,
        "id",
        "nombre"
    );
}

function cargarTiposMercaderia(lista)
{
    llenarSelect(
        "#rv_mercaderia_tipo_id",
        lista,
        "id",
        "nombre"
    );
}


// ------------------------------------------------------
// VEHICULO -> CONDUCTOR
// ------------------------------------------------------
$(document).on(
    "change",
    "#rv_vehiculo_id",
    function ()
{
    var conductorId =
        $(this)
            .find("option:selected")
            .data("conductor-id");

    var conductorNombre =
        $(this)
            .find("option:selected")
            .data("conductor");

    $("#rv_conductor_id").val(
        conductorId
    );

    $("#rv_conductor_nombre").val(
        conductorNombre
    );
});


// ------------------------------------------------------
// TIPO CARGA
// ------------------------------------------------------
$(document).on(
    "change",
    "#rv_tipo_carga",
    function ()
{
    $("#bloqueContenedor")
        .addClass("d-none");

    $("#bloqueMercaderia")
        .addClass("d-none");

    if (
        $(this).val() === "CONTENEDOR"
    ) {

        $("#bloqueContenedor")
            .removeClass("d-none");

    }

    if (
        $(this).val() === "MERCADERIA"
    ) {

        $("#bloqueMercaderia")
            .removeClass("d-none");

    }
});


// ------------------------------------------------------
// LIMPIAR
// ------------------------------------------------------
function limpiarFormularioViaje()
{
    $("#rv_ot_id").val("");

    $("#rv_conductor_id").val("");

    $("#rv_conductor_nombre").val("");

    $("#rv_fecha_viaje").val("");

    $("#rv_tipo_carga").val("");

    $("#rv_observaciones").val("");

    $("#bloqueContenedor")
        .addClass("d-none");

    $("#bloqueMercaderia")
        .addClass("d-none");
}