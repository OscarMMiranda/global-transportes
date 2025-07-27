// modulos/asignaciones/js/asignaciones.js

$(function() {
  const apiUrl  = window.ASIGNACIONES_API_URL;
  const tableId = '#tablaAsignaciones';
  let tabla;

  // 1) Inicializar DataTable solo una vez
  if (!$.fn.DataTable.isDataTable(tableId)) {
    tabla = $(tableId).DataTable({
      ajax: {
        url: `${apiUrl}?method=listar`,
        dataSrc: ''
      },
      columns: [
        { data: 'conductor' },
        { data: 'tracto' },
        { data: 'carreta' },
        {
          data: 'inicio',
          render: fecha => fecha
            ? new Date(fecha).toLocaleString()
            : ''
        },
        {
          data: 'fin',
          render: fecha => fecha
            ? new Date(fecha).toLocaleString()
            : ''
        },
        { data: 'estado' },
        {
          data: 'id',
          orderable: false,
          render: (id, _, row) => {
            if (row.estado === 'activo' || row.estado === 'activa') {
              return `<button class="btn btn-warning btn-finalizar" data-id="${id}">
                        Finalizar
                      </button>`;
            }
            return '<span class="text-muted">—</span>';
          }
        }
      ]
    });
  } else {
    // Si ya estaba inicializado, recuperamos la instancia
    tabla = $(tableId).DataTable();
  }

  // 2) Al abrir el modal, cargar los selects
 $(function() {


  // Se dispara cuando ya está en pantalla
  $('#modalAsignar').on('shown.bs.modal', function() {
    cargarSelect('api.php?method=conductores', '[data-role="conductor"]', 'Conductor');
    cargarSelect('api.php?method=tractos',     '[data-role="tracto"]',    'Tracto');
    cargarSelect('api.php?method=carretas',    '[data-role="carreta"]',   'Carreta');
  });

  
});

function cargarSelect(url, selector, label) {
  var $select = $(selector);
  $select.prop('disabled', true).empty()
         .append('<option value="">Cargando ' + label + '...</option>');

  $.getJSON(url)
    .done(function(data) {
      $select.empty()
             .append('<option value="">Seleccione ' + label + '</option>');
      $.each(data, function(_, item) {
        $select.append(
          $('<option>', { value: item.id, text: item.nombre || item.placa })
        );
      });
    })
    .fail(function(jqXHR) {
      console.error('Error al cargar ' + label + ':', jqXHR.responseText);
      $select.empty()
             .append('<option value="">Error al cargar ' + label + '</option>');
    })
    .always(function() {
      $select.prop('disabled', false);
    });
}


	
    // Cargar conductores
    fetch(`${apiUrl}?method=conductores`)
      .then(r => r.json())
      .then(data => {
        selC.empty().append('<option value="">Selecciona conductor</option>');
        data.forEach(c => {
          selC.append(`<option value="${c.id}">${c.nombre}</option>`);
        });
      })
      .catch(e => console.error('Error cargando conductores:', e));

    // Cargar tractos
    fetch(`${apiUrl}?method=vehiculos&tipo=tracto`)
      .then(r => r.json())
      .then(data => {
        selT.empty().append('<option value="">Selecciona tracto</option>');
        data.forEach(v => {
          selT.append(`<option value="${v.id}">${v.placa}</option>`);
        });
      })
      .catch(e => console.error('Error cargando tractos:', e));

    // Cargar carretas
    fetch(`${apiUrl}?method=vehiculos&tipo=carreta`)
      .then(r => r.json())
      .then(data => {
        selR.empty().append('<option value="">Selecciona carreta</option>');
        data.forEach(v => {
          selR.append(`<option value="${v.id}">${v.placa}</option>`);
        });
      })
      .catch(e => console.error('Error cargando carretas:', e));
  });

  // 3) Envío del formulario de asignación
  $('#formAsignacion').on('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch(`${apiUrl}?method=guardar`, {
      method: 'POST',
      body: formData
    })
      .then(r => r.json())
      .then(resp => {
        if (resp.ok) {
          $('#modalAsignar').modal('hide');
          tabla.ajax.reload();
        } else {
          alert('Error al guardar: ' + resp.error);
        }
      })
      .catch(err => alert('Fallo en la petición: ' + err));
  });

  // 4) Finalizar asignación
  $(tableId).on('click', '.btn-finalizar', function() {
    const id = $(this).data('id');
    if (!confirm('¿Deseas finalizar esta asignación?')) return;

    fetch(`${apiUrl}?method=finalizar&id=${id}`, { method: 'POST' })
      .then(r => r.json())
      .then(resp => {
        if (resp.ok) {
          tabla.ajax.reload();
        } else {
          alert('Error al finalizar: ' + resp.error);
        }
      })
      .catch(err => alert('Fallo en la petición: ' + err));
  });
});


function cargarConductores() {
  $.getJSON('api.php?method=conductores', function(data) {
    var select = $('#select-conductor');
    select.empty();
    select.append('<option value="">Seleccione</option>');
    data.forEach(function(c) {
      select.append($('<option>', {
        value: c.id,
        text: c.nombre
      }));
    });
  });
}
