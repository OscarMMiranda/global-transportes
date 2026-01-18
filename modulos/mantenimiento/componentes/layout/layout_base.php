<!-- archivo: layout_base.php -->
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Global Transportes</title>

  <!-- 1) Bootstrap 5 CSS -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    rel="stylesheet"
    crossorigin="anonymous"
  >

  <!-- 2) FontAwesome -->
  <link
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    rel="stylesheet"
    crossorigin="anonymous"
  >

  <!-- 3) DataTables Bootstrap5 CSS -->
  <link
    href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css"
    rel="stylesheet"
  >

  <!-- 4) Tus estilos -->
  <!-- <link rel="stylesheet" href="/modulos/mantenimiento/tipo_vehiculo/assets/estilos.css"> -->
</head>
<body class="bg-light">

  <?php require_once __DIR__ . '/header.php'; ?>

  <main class="container py-4">
    <?php require_once __DIR__ . '/../../tipo_vehiculo/vistas/view.php'; ?>
  </main>

  <?php require_once __DIR__ . '/footer.php'; ?>

  <!-- 5) jQuery (una sola vez) -->
  <script
    src="https://code.jquery.com/jquery-3.7.1.min.js"
    crossorigin="anonymous"
  ></script>

  <!-- 6) DataTables core -->
  <script
    src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"
  ></script>

  <!-- 7) DataTables + Bootstrap5 integration -->
  <script
    src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"
  ></script>

  <!-- 8) Bootstrap 5 Bundle (Popper incluido) -->
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    crossorigin="anonymous"
  ></script>

  <!-- 9) Inicialización de DataTables y tu lógica JS -->
  <script>
    $(document).ready(function() {
      // Asegurate de que el ID de tu tabla coincide con este selector
      $('#tabla_tipos').DataTable({
        language: {
          url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        },
        responsive: true
      });

      // Ejemplo de tu botón Editar abriendo modal
      $('#tabla_tipos').on('click', '.btn-editar', function() {
        var id = $(this).data('id');
        // tu llamada AJAX a ajax/form_edit.php?id=...
      });
    });
  </script>
</body>
</html>