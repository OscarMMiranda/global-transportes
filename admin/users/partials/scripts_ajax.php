<!-- jQuery y Bootstrap -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Carga modular de usuarios -->
<script>
$(document).ready(function () {
  cargarUsuarios('activos');
});

function cargarUsuarios(tipo) {
  const url = tipo === 'activos'
    ? '/admin/users/ajax/tabla_activos.php'
    : '/admin/users/ajax/tabla_eliminados.php';

  $('#contenidoTabla').html('<tr><td colspan="8" class="text-center text-muted">Cargando usuarios...</td></tr>');

  $.get(url, function (html) {
    $('#contenidoTabla').html(html);
  }).fail(function () {
    $('#contenidoTabla').html('<tr><td colspan="8" class="text-danger text-center">❌ Error al cargar usuarios.</td></tr>');
  });
}

$('#tabActivos').on('click', function () {
  $('#tabActivos').addClass('active');
  $('#tabEliminados').removeClass('active');
  cargarUsuarios('activos');
});

$('#tabEliminados').on('click', function () {
  $('#tabEliminados').addClass('active');
  $('#tabActivos').removeClass('active');
  cargarUsuarios('eliminados');
});
</script>

<!-- Modal para editar usuario -->
<script>
$(document).on('click', '.btn-editar', function () {
  const id = $(this).data('id');
  $('#contenedorModales').html('<div class="text-center text-muted p-3">Cargando formulario...</div>');

  $.get('/admin/users/modals/modal_editar_usuario.php?id=' + id, function (modalHtml) {
    $('#contenedorModales').html(modalHtml);
    const modal = new bootstrap.Modal(document.getElementById('modalEditarUsuario' + id));
    modal.show();
  });
});
</script>

<!-- AJAX para editar usuario -->
<script>
$(document).on('submit', '.form-editar-usuario', function (e) {
  e.preventDefault();
  const form = $(this);
  const id = form.data('id');
  const datos = form.serialize();

  $.post('/admin/users/ajax/ajax_editar_usuario.php?id=' + id, datos, function (respuesta) {
    if (respuesta.estado === 'ok') {
      alert(respuesta.mensaje);
      const modal = bootstrap.Modal.getInstance(form.closest('.modal')[0]);
      modal.hide();
      cargarUsuarios('activos');
    } else {
      alert(respuesta.mensaje);
    }
  }, 'json');
});
</script>

<!-- AJAX para eliminar usuario -->
<script>
$(document).on('click', '.btn-eliminar', function () {
  const id = $(this).data('id');
  if (!confirm('¿Estás seguro de que deseas eliminar este usuario?')) return;

  $.post('/admin/users/ajax/ajax_eliminar_usuario.php', { id: id }, function (respuesta) {
    if (respuesta.estado === 'ok') {
      alert(respuesta.mensaje);
      cargarUsuarios('activos');
    } else {
      alert(respuesta.mensaje);
    }
  }, 'json');
});
</script>

<!-- Modal para ver detalles -->
<script>
$(document).on('click', '.btn-ver', function () {
  const id = $(this).data('id');
  $('#contenedorModales').html('<div class="text-center text-muted p-3">Cargando detalles...</div>');

  $.get('/admin/users/modals/modal_ver_usuario.php?id=' + id, function (modalHtml) {
    $('#contenedorModales').html(modalHtml);
    const modal = new bootstrap.Modal(document.getElementById('modalVerUsuario' + id));
    modal.show();
  }).fail(function () {
    $('#contenedorModales').html('<div class="text-danger text-center p-3">❌ Error al cargar detalles del usuario.</div>');
  });
});
</script>

<!-- Modal para crear usuario -->
<script>
$(document).on('click', '#btnCrearUsuario', function () {
  $('#contenedorModales').html('<div class="text-center text-muted p-3">Cargando formulario...</div>');

  $.get('/admin/users/modals/modal_crear_usuario.php', function (modalHtml) {
    $('#contenedorModales').html(modalHtml);
    const modal = new bootstrap.Modal(document.getElementById('modalCrearUsuario'));
    modal.show();
  }).fail(function () {
    $('#contenedorModales').html('<div class="text-danger text-center p-3">❌ Error al cargar formulario de creación.</div>');
  });
});
</script>

<!-- AJAX para crear usuario -->
<script>
$(document).on('submit', '.form-crear-usuario', function (e) {
  e.preventDefault();
  const form = $(this);
  const datos = form.serialize();

  $.post('/admin/users/ajax/ajax_crear_usuario.php', datos, function (respuesta) {
    if (respuesta.estado === 'ok') {
      alert(respuesta.mensaje);
      const modal = bootstrap.Modal.getInstance(document.getElementById('modalCrearUsuario'));
      modal.hide();
      cargarUsuarios('activos');
    } else {
      alert(respuesta.mensaje);
    }
  }, 'json');
});
</script>

<!-- AJAX para restaurar usuario -->
<script>
$(document).on('click', '.btn-restaurar', function () {
  const id = $(this).data('id');
  if (!confirm('¿Deseas restaurar este usuario?')) return;

  $.post('/admin/users/ajax/ajax_restaurar_usuario.php', { id: id }, function (respuesta) {
    if (respuesta.estado === 'ok') {
      alert(respuesta.mensaje);
      cargarUsuarios('activos'); // restaurado → activos
    } else {
      alert(respuesta.mensaje);
    }
  }, 'json');
});
</script>

<!-- AJAX para eliminar definitivamente -->
<script>
$(document).on('click', '.btn-eliminar-def', function () {
  const id = $(this).data('id');
  if (!confirm('⚠️ Esta acción es irreversible. ¿Eliminar definitivamente este usuario?')) return;

  $.post('/admin/users/ajax/ajax_eliminar_definitivo.php', { id: id }, function (respuesta) {
    if (respuesta.estado === 'ok') {
      alert(respuesta.mensaje);
      $('tr[data-id="' + id + '"]').fadeOut(); // elimina visualmente la fila
    } else {
      alert(respuesta.mensaje);
    }
  }, 'json');
});
</script>