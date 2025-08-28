/**
 * modulos/vehiculos/js/vehiculos.js
 * JavaScript para módulo Vehículos:
 * - Persistencia de pestaña activa
 * - DataTables (búsqueda, paginación, orden)
 * - AJAX para ver, eliminar y restaurar vehículos
 * Requiere jQuery, Bootstrap 5 y DataTables.
 */

$(document).ready(function() {
  // 1) Persistencia de pestaña activa con localStorage
  const tabButtons = $('button[data-bs-toggle="tab"]');
  tabButtons.on('shown.bs.tab', function(e) {
    const target = $(e.target).data('bs-target');
    localStorage.setItem('vehiculoTabActivo', target);
  });

  const lastTab = localStorage.getItem('vehiculoTabActivo');
  if (lastTab) {
    const btn = $(`button[data-bs-target="${lastTab}"]`);
    if (btn.length) {
      new bootstrap.Tab(btn[0]).show();
    }
  }

  // 2) Inicializar DataTables en ambas tablas
  const dtOptions = {
    language: { url: '/path/to/datatables/spanish.json' },
    pageLength: 10,
    responsive: true,
    columnDefs: [
      { orderable: false, targets: -1 }  // última columna (Acciones) no ordenable
    ]
  };

  const dtActivos   = $('#tabla-activos').DataTable(dtOptions);
  const dtInactivos = $('#tabla-inactivos').DataTable(dtOptions);

  // 3) Modal para “Ver” detalles de vehículo (carga vía AJAX)
  const modalEl = document.getElementById('modalVerVehiculo');
  const modal   = new bootstrap.Modal(modalEl);

  $('.btn-ver').on('click', function() {
    const id = $(this).data('id');
    if (!id) return;

    fetch(`index.php?action=view&id=${id}&ajax=1`)
      .then(res => res.text())
      .then(html => {
        modalEl.querySelector('.modal-body').innerHTML = html;
        modal.show();
      })
      .catch(err => {
        console.error('Error AJAX ver vehículo:', err);
        alert('Error cargando detalles del vehículo.');
      });
  });

  // 4) AJAX para eliminar (soft-delete) un vehículo desde la tabla de activos
  $('#tabla-activos').on('click', '.btn-delete', function(e) {
    e.preventDefault();
    const btn = $(this);
    const id  = btn.data('id');
    if (!id || !confirm('¿Eliminar este vehículo?')) return;

    $.ajax({
      url: 'index.php?action=delete',
      method: 'POST',
      data: { id },
      dataType: 'json'
    })
    .done(function(resp) {
      if (resp.success) {
        alert(resp.message);
        // Quitar fila de DataTable
        dtActivos.row(btn.closest('tr')).remove().draw();
      } else {
        alert('Error: ' + resp.message);
      }
    })
    .fail(function() {
      alert('Error en la solicitud AJAX.');
    });
  });

  // 5) AJAX para restaurar un vehículo desde la tabla de inactivos
  $('#tabla-inactivos').on('click', '.btn-restore', function(e) {
    e.preventDefault();
    const btn = $(this);
    const id  = btn.data('id');
    if (!id || !confirm('¿Restaurar este vehículo?')) return;

    $.ajax({
      url: 'index.php?action=restore',
      method: 'POST',
      data: { id },
      dataType: 'json'
    })
    .done(function(resp) {
      if (resp.success) {
        alert(resp.message);
        // Recarga sencilla; también podrías mover la fila manualmente:
        location.reload();
      } else {
        alert('Error: ' + resp.message);
      }
    })
    .fail(function() {
      alert('Error en la solicitud AJAX.');
    });
  });
});