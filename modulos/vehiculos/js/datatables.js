// archivo: /modulos/vehiculos/js/datatables.js
// Inicialización de DataTables para Vehículos (Activos / Inactivos)

$(function () {

  console.log("✅ datatables.js de Vehículos inicializado");

  const listarActivosApi   = "/modulos/vehiculos/acciones/listar.php?estado=activo";
  const listarInactivosApi = "/modulos/vehiculos/acciones/listar.php?estado=inactivo";
  const listarTodosApi     = "/modulos/vehiculos/acciones/listar.php?estado=todos";

  // ---------------------------------------------------------
  // Render de botones de acción (CORREGIDO)
  // ---------------------------------------------------------
  function accionesHTML(r) {
    const verBtn = `
      <button class="btn btn-sm btn-info btn-view" data-id="${r.id}" title="Ver">
        <i class="fa fa-eye"></i>
      </button>`;

    // Vehículo activo → mostrar Editar + Desactivar
    if (parseInt(r.activo) === 1) {
      return `
        ${verBtn}
        <button class="btn btn-sm btn-primary btn-edit" data-id="${r.id}" title="Editar">
          <i class="fa fa-edit"></i>
        </button>
        <button class="btn btn-sm btn-warning btn-desactivar" data-id="${r.id}" title="Desactivar">
          <i class="fa fa-ban"></i>
        </button>`;
    }

    // Vehículo inactivo → mostrar Restaurar + Eliminar
    return `
      ${verBtn}
      <button class="btn btn-sm btn-success btn-restaurar" data-id="${r.id}" title="Restaurar">
        <i class="fa fa-rotate-left"></i>
      </button>
      <button class="btn btn-sm btn-danger btn-eliminar" data-id="${r.id}" title="Eliminar definitivo">
        <i class="fa fa-trash"></i>
      </button>`;
  }

  // ---------------------------------------------------------
  // Inicializador genérico
  // ---------------------------------------------------------
  function initTable(selector, ajaxUrl) {
    if (!$(selector).length) {
      console.warn("⚠️ No existe " + selector + " en el DOM");
      return;
    }

    $(selector).DataTable({
      ajax: {
        url: ajaxUrl,
        dataSrc: json => json.success ? json.data : []
      },
      columns: [
        { data: null, render: (d, t, r, m) => m.row + 1 },
        { data: "placa" },
        { data: "marca" },
        { data: "modelo" },
        { data: "anio" },

        {
          data: null,
          render: (d) => {
            return d.activo == 1
              ? '<span class="badge bg-success">Activo</span>'
              : '<span class="badge bg-secondary">Inactivo</span>';
          }
        },

        { data: null, render: accionesHTML }
      ],
      language: {
        url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
      },
      pageLength: 10,
      responsive: true
    });
  }

  // ---------------------------------------------------------
  // Inicializar tablas
  // ---------------------------------------------------------
  initTable("#tblActivosVehiculos", listarActivosApi);
  initTable("#tblInactivosVehiculos", listarInactivosApi);
  //initTable("#tblTodosVehiculos", listarTodosApi);

  // ---------------------------------------------------------
  // Exponer recargas globales
  // ---------------------------------------------------------
  window.VehiculosDT = {
    reloadActivos:   () => $("#tblActivosVehiculos").DataTable().ajax.reload(null, false),
    reloadInactivos: () => $("#tblInactivosVehiculos").DataTable().ajax.reload(null, false),
    //reloadTodos:     () => $("#tblTodosVehiculos").DataTable().ajax.reload(null, false)
  };

});