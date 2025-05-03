<?php
require_once '../includes/conexion.php';

// Obtener departamentos para el formulario
$departamentos = $conn->query("SELECT id, nombre FROM departamentos ORDER BY nombre ASC");

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registrar Cliente</title>
  <link rel="stylesheet" href="../css/estilo.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
  <h2>Registrar Nuevo Cliente</h2>
  <form action="guardar_cliente.php" method="POST">
    <label>Nombre:</label>
    <input type="text" name="nombre" required><br>

    <label>RUC:</label>
    <input type="text" name="ruc"><br>

    <label>Dirección:</label>
    <input type="text" name="direccion"><br>

    <label>Teléfono:</label>
    <input type="text" name="telefono"><br>

    <label>Correo:</label>
    <input type="email" name="correo"><br>

    <label>Departamento:</label>
    <select name="departamento_id" id="departamento" required>
      <option value="">Seleccione</option>
      <?php while ($dep = $departamentos->fetch_assoc()): ?>
        <option value="<?= $dep['id'] ?>"><?= $dep['nombre'] ?></option>
      <?php endwhile; ?>
    </select><br>

    <label>Provincia:</label>
    <select name="provincia_id" id="provincia" required></select><br>

    <label>Distrito:</label>
    <select name="distrito_id" id="distrito" required></select><br>

    <button type="submit">Guardar</button>
  </form>

  <script>
    $('#departamento').change(function() {
      let depId = $(this).val();
      $('#provincia').load('ajax_provincias.php?departamento_id=' + depId);
    });

    $('#provincia').change(function() {
      let provId = $(this).val();
      $('#distrito').load('ajax_distritos.php?provincia_id=' + provId);
    });
  </script>
</body>
</html>
