function abrirFormulario() {
  document.getElementById('formulario').style.display = 'block';
  document.getElementById('tipo_id').value = '';
  document.getElementById('nombre').value = '';
}

function cerrarFormulario() {
  document.getElementById('formulario').style.display = 'none';
}

// 📝 Cargar datos en el formulario para editar
function editarTipo(id) {
  var fila = document.querySelector('tr[data-id="' + id + '"]');
  var nombre = fila.querySelector('.nombre').textContent;

  document.getElementById('formulario').style.display = 'block';
  document.getElementById('tipo_id').value = id;
  document.getElementById('nombre').value = nombre;
}

// 🗑️ Confirmar eliminación
function eliminarTipo(id) {
  if (confirm("¿Está seguro de que desea eliminar este tipo?")) {
    window.location.href = "controller.php?eliminar=" + id;
  }
}