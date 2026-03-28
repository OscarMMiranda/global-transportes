// archivo : /modulos/mantenimiento/tipo_destinos/script.js

function abrirFormulario() {
  $('#modalTitulo').text('Agregar tipo de destino');
  $('#tipo_id').val('');
  $('#nombre').val('');
  $('#descripcion').val('');
  $('#modalTipoDestino').modal('show');
}

function cerrarFormulario() {
  document.getElementById('formulario').style.display = 'none';
}

// 📝 Cargar datos en el formulario para editar
function editarTipo(id) {
  var fila = document.querySelector('tr[data-id="' + id + '"]');
  var nombre = fila.querySelector('.nombre').textContent;
  var descripcion = fila.getAttribute('data-descripcion');

  $('#modalTitulo').text('Editar tipo de destino');
  $('#tipo_id').val(id);
  $('#nombre').val(nombre);
  $('#descripcion').val(descripcion);
  $('#modalTipoDestino').modal('show');
}

// 🗑️ Confirmar eliminación
function eliminarTipo(id) {
  if (confirm("¿Está seguro de que desea eliminar este tipo?")) {
    window.location.href = "controller.php?eliminar=" + id;
  }
}
