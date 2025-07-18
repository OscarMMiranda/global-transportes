document.addEventListener('DOMContentLoaded', function () {
  const tabla = $('#tablaAsignaciones').DataTable({
    ajax: `${window.ASIGNACIONES_API_URL}?method=listar`,
    columns: [
      { data: 'conductor' },
      { data: 'tracto' },
      { data: 'carreta' },
      { data: 'inicio' },
      { data: 'fin' },
      { data: 'estado' },
      {
        data: 'id',
        render: function (id, _, row) {
          if (row.estado === 'activo') {
            return `<button class="btn btn-warning btn-finalizar" data-id="${id}">Finalizar</button>`;
          } else {
            return '<span class="text-muted">—</span>';
          }
        }
      }
    ]
  });

  // Modal carga
  document.getElementById('modalAsignar').addEventListener('show.bs.modal', () => {
    fetch(`${window.ASIGNACIONES_API_URL}?method=conductores`)
  .then(r => r.json()).then(data => {
    const select = document.getElementById('conductor_id');
    select.innerHTML = '<option value="">Selecciona...</option>';
    data.forEach(c => {
      select.innerHTML += `<option value="${c.id}">${c.nombre}</option>`;
    });
  });

    ['tracto', 'carreta'].forEach(tipo => {
      fetch(`${window.ASIGNACIONES_API_URL}?method=vehiculos&tipo=${tipo}`)
        .then(r => r.json()).then(data => {
          let s = document.getElementById(`vehiculo_${tipo}_id`);
          s.innerHTML = '<option value="">Selecciona...</option>';
          data.forEach(v => s.innerHTML += `<option value="${v.id}">${v.placa}</option>`);
        });
    });
  });

  // Guardar
  document.getElementById('formAsignacion').addEventListener('submit', e => {
    e.preventDefault();
    let fd = new FormData(e.target);
    fetch(`${window.ASIGNACIONES_API_URL}?method=guardar`, {
      method: 'POST',
      body: fd
    }).then(r => r.json()).then(resp => {
      if (resp.ok) {
        alert('Asignación guardada ✅');
        bootstrap.Modal.getInstance(document.getElementById('modalAsignar')).hide();
        tabla.ajax.reload();
      } else {
        alert('Error: ' + resp.error);
      }
    });
  });

  // Finalizar
  $('#tablaAsignaciones').on('click', '.btn-finalizar', function () {
    let id = $(this).data('id');
    if (confirm('¿Finalizar esta asignación?')) {
      fetch(`${window.ASIGNACIONES_API_URL}?method=finalizar&id=${id}`, { method: 'POST' })
        .then(r => r.json()).then(resp => {
          if (resp.ok) tabla.ajax.reload();
          else alert('Error: ' + resp.error);
        });
    }
  });
});
