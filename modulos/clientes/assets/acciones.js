// archivo: /modulos/clientes/assets/acciones.js

const DEBUG_ACC = true;

function alog(msg) {
    if (DEBUG_ACC) console.log("DEBUG ACC:", msg);
}

function aerror(msg) {
    if (DEBUG_ACC) console.error("DEBUG ACC ERROR:", msg);
}

alog("acciones.js cargado correctamente");

// ============================
// ACCIÓN: VER CLIENTE
// ============================
$(document).off("click", ".btnVerCliente");
$(document).on("click", ".btnVerCliente", function () {
    const id = $(this).data("id");
    alog("Clic en VER cliente ID: " + id);
    verCliente(id);
});

// ============================
// ACCIÓN: EDITAR CLIENTE
// ============================
$(document).off("click", ".btnEditarCliente");
$(document).on("click", ".btnEditarCliente", function () {
    const id = $(this).data("id");
    alog("Clic en EDITAR cliente ID: " + id);

    if (typeof abrirModalEditarCliente === "function") {
        abrirModalEditarCliente(id);
    } else {
        aerror("abrirModalEditarCliente() no está definida");
    }
});

// ============================
// ACCIÓN: INACTIVAR CLIENTE
// ============================
$(document).off("click", ".btnEliminarCliente");
$(document).on("click", ".btnEliminarCliente", function () {
    const id = $(this).data("id");
    alog("Clic en INACTIVAR cliente ID: " + id);

    if (typeof eliminarCliente === "function") {
        eliminarCliente(id);
    } else {
        aerror("eliminarCliente() no está definida");
    }
});

// ============================
// ACCIÓN: ACTIVAR CLIENTE
// ============================
$(document).off("click", ".btnActivarCliente");
$(document).on("click", ".btnActivarCliente", function () {
    const id = $(this).data("id");
    alog("Clic en ACTIVAR cliente ID: " + id);

    if (typeof activarCliente === "function") {
        activarCliente(id);
    } else {
        aerror("activarCliente() no está definida");
    }
});

// ============================
// FUNCIONES    : activarCliente
// ============================
function activarCliente(id) {

    if (!id) {
        console.error("ID inválido en activarCliente()");
        return;
    }

    $.ajax({
        url: "/modulos/clientes/ajax/activar_cliente.php",
        type: "POST",
        data: { id: id },
        dataType: "json",
        success: function (resp) {

            if (resp.status === "ok") {
                Swal.fire("Activado", resp.msg, "success");

                if (tablaClientes) {
                    tablaClientes.ajax.reload(null, false);
                }

            } else {
                Swal.fire("Error", resp.msg, "error");
            }
        },
        error: function () {
            Swal.fire("Error", "No se pudo activar el cliente", "error");
        }
    });
}

// funciones    : eliminarCliente
function eliminarCliente(id) {

    if (!id) {
        console.error("ID inválido en eliminarCliente()");
        return;
    }

    $.ajax({
        url: "/modulos/clientes/ajax/desactivar_cliente.php",
        type: "POST",
        data: { id: id },
        dataType: "json",
        success: function (resp) {

            if (resp.status === "ok") {
                Swal.fire("Desactivado", resp.msg, "success");

                if (tablaClientes) {
                    tablaClientes.ajax.reload(null, false);
                }

            } else {
                Swal.fire("Error", resp.msg, "error");
            }
        },
        error: function () {
            Swal.fire("Error", "No se pudo desactivar el cliente", "error");
        }
    });
}

// ============================
// ACCIÓN: HISTORIAL CLIENTE
// ============================
$(document).off("click", ".btnHistorialCliente");
$(document).on("click", ".btnHistorialCliente", function () {
    const id = $(this).data("id");
    alog("Clic en HISTORIAL cliente ID: " + id);

    // Abrir modal (Bootstrap 5)
    let modal = new bootstrap.Modal(document.getElementById('modalHistorialCliente'));
    modal.show();

    // Spinner mientras carga
    $("#tablaHistorialCliente tbody").html(`
        <tr>
            <td colspan="5" class="text-center p-3">
                <div class="spinner-border text-primary"></div>
                <p class="mt-2">Cargando historial...</p>
            </td>
        </tr>
    `);

    // Cargar historial por AJAX
    $.ajax({
        url: "/modulos/clientes/ajax/historial_cliente.php",
        type: "POST",
        data: { id: id },
        success: function (response) {
            $("#tablaHistorialCliente tbody").html(response);
        },
        error: function () {
            $("#tablaHistorialCliente tbody").html(`
                <tr>
                    <td colspan="5" class="text-center text-danger">
                        Error cargando historial.
                    </td>
                </tr>
            `);
        }
    });
});
