// archivo: /modulos/usuarios/js/usuarios.js
// Módulo Usuarios – JS principal

$(document).ready(function () {

    let estadoActual = 0;

    // DATATABLE PRINCIPAL
    const tabla = $('#tablaUsuarios').DataTable({
        ajax: {
            url: '/modulos/usuarios/acciones/listar.php',
            type: 'GET',
            data: function (d) {
                d.estado = estadoActual;
            },
            dataSrc: 'data'
        },
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
        },
        columns: [
            { data: 'id' },
            { data: 'nombre_completo' },
            { data: 'usuario' },
            {
                data: 'rol_nombre',
                className: 'text-center',
                render: function (rol) {
                    return `<span class="badge bg-info text-dark">${rol}</span>`;
                }
            },
            {
                data: 'estado',
                className: 'text-center',
                render: function (estado) {
                    return estado.includes("Activo")
                        ? `<span class="badge bg-success">Activo</span>`
                        : `<span class="badge bg-secondary">Inactivo</span>`;
                }
            },
            { data: 'acciones', className: 'text-center', orderable: false, searchable: false }
        ],
        responsive: true,
        pageLength: 10,
        order: [[0, 'asc']]
    });

    // FILTRO DE ESTADO
    $('.filtro-estado a').on('click', function () {

        $('.filtro-estado a').removeClass('active');
        $(this).addClass('active');

        estadoActual = $(this).data('estado');

        tabla.ajax.reload(function () {
            tabla.columns.adjust();
            if (tabla.responsive) tabla.responsive.recalc();
        });
    });

    // AJUSTE RESPONSIVE EN RESIZE
    $(window).on('resize', function () {
        tabla.columns.adjust();
        if (tabla.responsive) tabla.responsive.recalc();
    });

    // CREAR USUARIO
    $(document).on('click', '#btnCrearUsuario', function () {

        const form = $('#form-crear-usuario')[0];
        if (form) form.reset();

        $('#crear-alertas').html('');

        const modal = new bootstrap.Modal(document.getElementById('modalCrearUsuario'));
        modal.show();
    });

    $(document).on('submit', '#form-crear-usuario', function (e) {
        e.preventDefault();

        $.ajax({
            url: '/modulos/usuarios/acciones/guardar.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',

            success: function (r) {

                if (!r.ok) {
                    let html = '<div class="alert alert-danger"><ul>';
                    r.errores.forEach(e => html += `<li>${e}</li>`);
                    html += '</ul></div>';
                    $('#crear-alertas').html(html);
                    return;
                }

                $('#crear-alertas').html(
                    `<div class="alert alert-success">${r.mensaje}</div>`
                );

                tabla.ajax.reload();

                setTimeout(() => {
                    const modal = bootstrap.Modal.getInstance(
                        document.getElementById('modalCrearUsuario')
                    );
                    modal.hide();
                }, 800);
            },

            error: function (xhr) {
                $('#crear-alertas').html(
                    `<div class="alert alert-danger">
                        Error inesperado en el servidor (HTTP ${xhr.status})
                    </div>`
                );
            }
        });
    });

    // --------------------------------------------------------------
    // VER USUARIO
    // --------------------------------------------------------------
    $(document).on('click', '.ver-usuario', function () {

        const id = $(this).data('id');

        $.ajax({
            url: '/modulos/usuarios/acciones/ver.php',
            type: 'GET',
            data: { id: id },
            dataType: 'json',

            success: function (u) {

                $('#ver-id').text(u.id);
                $('#ver-nombre').text(u.nombre);
                $('#ver-apellido').text(u.apellido);
                $('#ver-usuario').text(u.usuario);
                $('#ver-correo').text(u.correo);
                $('#ver-rol').text(u.rol);
                $('#ver-estado').text(u.eliminado == 0 ? 'Activo' : 'Inactivo');
                $('#ver-creado').text(u.creado_en);

                const modal = new bootstrap.Modal(document.getElementById('modalVerUsuario'));
                modal.show();
            },

            error: function () {
                alert("Error al cargar los datos del usuario.");
            }
        });

    });

    // --------------------------------------------------------------
    // EDITAR USUARIO
    // --------------------------------------------------------------
    $(document).on("click", ".btn-editar", function () {

        const id = $(this).data("id");

        $("#edit_loader").show();
        $("#edit_info").addClass("d-none");
        $("#formEditarUsuario").addClass("d-none");
        $("#btnGuardarEdicion").addClass("d-none");

        const modal = new bootstrap.Modal(
            document.getElementById("modalEditarUsuario")
        );
        modal.show();

        $.post("/modulos/usuarios/acciones/obtener.php", { id }, function (res) {

            if (!res.ok) {
                $("#edit_info").text("Error: " + res.mensaje);
                return;
            }

            $("#edit_id").val(res.data.id);
            $("#edit_nombre").val(res.data.nombre);
            $("#edit_apellido").val(res.data.apellido);
            $("#edit_usuario").val(res.data.usuario);
            $("#edit_correo").val(res.data.correo);

            $("#edit_rol").html("");
            res.roles.forEach(r => {
                $("#edit_rol").append(
                    `<option value="${r.id}" ${r.id == res.data.rol ? "selected" : ""}>${r.nombre}</option>`
                );
            });

            $("#edit_loader").hide();
            $("#edit_info").text("Datos cargados correctamente.").removeClass("d-none");
            $("#formEditarUsuario").removeClass("d-none");
            $("#btnGuardarEdicion").removeClass("d-none");

        }, "json")
        .fail(function (xhr) {
            $("#edit_info").text("Error inesperado: " + xhr.status);
        });
    });

    // --------------------------------------------------------------
    // GUARDAR EDICIÓN
    // --------------------------------------------------------------
    $(document).on("click", "#btnGuardarEdicion", function () {

        const datos = $("#formEditarUsuario").serialize();

        $.post("/modulos/usuarios/acciones/editar.php", datos, function (res) {

            if (!res.ok) {
                Swal.fire("Error", res.mensaje, "error");
                return;
            }

            Swal.fire("OK", res.mensaje, "success");

            const modal = bootstrap.Modal.getInstance(
                document.getElementById("modalEditarUsuario")
            );
            modal.hide();

            tabla.ajax.reload(null, false);

        }, "json")
        .fail(function () {
            Swal.fire("Error inesperado", "No se pudo guardar los cambios", "error");
        });
    });

    // --------------------------------------------------------------
    // DESACTIVAR
    // --------------------------------------------------------------
    $(document).on('click', '.btn-desactivar', function () {
        const id = $(this).data('id');

        Swal.fire({
            title: "¿Desactivar usuario?",
            text: "El usuario pasará a estado inactivo.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, desactivar",
            cancelButtonText: "Cancelar"
        }).then((r) => {
            if (r.isConfirmed) {
                window.location.href = "/modulos/usuarios/acciones/desactivar.php?id=" + id;
            }
        });
    });

    // --------------------------------------------------------------
    // RESTAURAR
    // --------------------------------------------------------------
    $(document).on('click', '.btn-restaurar', function () {
        const id = $(this).data('id');

        Swal.fire({
            title: "¿Restaurar usuario?",
            text: "El usuario volverá a estar activo.",
            icon: "info",
            showCancelButton: true,
            confirmButtonText: "Sí, restaurar",
            cancelButtonText: "Cancelar"
        }).then((r) => {
            if (r.isConfirmed) {
                window.location.href = "/modulos/usuarios/acciones/restaurar.php?id=" + id;
            }
        });
    });

    // --------------------------------------------------------------
    // ELIMINAR DEFINITIVO
    // --------------------------------------------------------------
    $(document).on('click', '.btn-eliminar', function () {
        const id = $(this).data('id');

        Swal.fire({
            title: "¿Eliminar DEFINITIVAMENTE?",
            text: "Esta acción no se puede deshacer.",
            icon: "error",
            showCancelButton: true,
            confirmButtonText: "Eliminar",
            cancelButtonText: "Cancelar"
        }).then((r) => {
            if (r.isConfirmed) {
                window.location.href = "/modulos/usuarios/acciones/eliminar_definitivo.php?id=" + id;
            }
        });
    });

    // FIX ACCESIBILIDAD
    document.addEventListener('hidden.bs.modal', function () {
        if (document.activeElement) {
            document.activeElement.blur();
        }
    });

});

$(document).on("click", ".toggle-pass", function () {
    const input = $($(this).data("target"));
    const icon = $(this).find("i");

    if (input.attr("type") === "password") {
        input.attr("type", "text");
        icon.removeClass("fa-eye").addClass("fa-eye-slash");
    } else {
        input.attr("type", "password");
        icon.removeClass("fa-eye-slash").addClass("fa-eye");
    }
});