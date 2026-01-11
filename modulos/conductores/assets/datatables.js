// DataTables para Conductores
// ----------------------------------------------
// archivo: modulos/conductores/assets/datatables.js

$(function () {
  const listarActivosApi   = '/modulos/conductores/acciones/listar.php?estado=activo';
  const listarInactivosApi = '/modulos/conductores/acciones/listar.php?estado=inactivo';

  function accionesHTML(r) {
    const verBtn = `<button class="btn btn-sm btn-info btn-view" data-id="${r.id}" title="Ver"><i class="fa fa-eye"></i></button>`;
    if (parseInt(r.activo) === 1) {
      return `${verBtn}
        <button class="btn btn-sm btn-primary btn-edit" data-id="${r.id}" title="Editar"><i class="fa fa-edit"></i></button>
        <button class="btn btn-sm btn-warning btn-soft-delete" data-id="${r.id}" title="Desactivar"><i class="fa fa-ban"></i></button>`;
    } else {
      return `${verBtn}
        <button class="btn btn-sm btn-success btn-restore" data-id="${r.id}" title="Restaurar"><i class="fa fa-rotate-left"></i></button>
        <button class="btn btn-sm btn-danger btn-delete" data-id="${r.id}" title="Eliminar definitivo"><i class="fa fa-trash"></i></button>`;
    }
  }

  function initTable(selector, ajaxUrl) {
    if (!$(selector).length) return;

    $(selector).DataTable({
      ajax: {
        url: ajaxUrl,
        dataSrc: json => json.success ? json.data : []
      },
      columns: [
        { data: null, render: (d,t,r,m) => m.row + 1 },
        { data: null, render: r => `${r.apellidos}, ${r.nombres}` },
        { data: 'dni' },
        { data: 'licencia_conducir' },
        { data: 'telefono', defaultContent: '—' },
        { data: 'activo', render: d => d == 1 
            ? '<span class="badge bg-success">Activo</span>' 
            : '<span class="badge bg-secondary">Inactivo</span>' },
        { data: null, render: accionesHTML }
      ],
      language: { url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
      pageLength: 10,
      responsive: true
    });
  }

  initTable('#tblActivos', listarActivosApi);
  initTable('#tblInactivos', listarInactivosApi);

  window.ConductoresDT = {
    reloadActivos: () => $('#tblActivos').DataTable().ajax.reload(null, false),
    reloadInactivos: () => $('#tblInactivos').DataTable().ajax.reload(null, false)
  };

  console.log('✅ datatables.js inicializado correctamente');
});