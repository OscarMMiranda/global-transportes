<?php
// form_edit.php

// Validación de existencia del registro
if (!isset($registro) || !is_array($registro)) {
    echo "<p>Error: No se encontró el registro a editar.</p>";
    return;
}

// Capturar mensaje de error (nombre duplicado, etc.)
$error = isset($_GET['error'])
    ? htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8')
    : '';

// Preservar valores: si viene de POST (hubo error), los usamos; si no, los originales
$oldNombre      = isset($_POST['nombre'])       ? trim($_POST['nombre'])            : $registro['nombre'];
$oldDescripcion = isset($_POST['descripcion'])  ? trim($_POST['descripcion'])       : $registro['descripcion'];
$oldCategoriaId = isset($_POST['categoria_id']) ? (int)$_POST['categoria_id']       : (int)$registro['categoria_id'];
?>

<?php if ($error): ?>
  <div class="alert alert-danger">
    <?= $error ?>
  </div>
<?php endif; ?>

<h2 class="mb-4">Editar Tipo de Vehículo</h2>

<form method="post" action="index.php?action=update&id=<?= (int)$registro['id'] ?>">
  <table>
    <tr>
      <td><label for="nombre">Nombre:</label></td>
      <td>
        <input
          type="text"
          name="nombre"
          id="nombre"
          value="<?= htmlspecialchars($oldNombre, ENT_QUOTES, 'UTF-8') ?>"
          required
          maxlength="100"
        >
      </td>
    </tr>

    <tr>
      <td><label for="categoria_id">Categoría:</label></td>
      <td>
        <select name="categoria_id" id="categoria_id" required>
          <option value="">[Seleccionar]</option>
          <?php foreach ($categorias as $cat): ?>
            <option
              value="<?= (int)$cat['id'] ?>"
              <?= $oldCategoriaId === (int)$cat['id'] ? 'selected' : '' ?>
            >
              <?= htmlspecialchars($cat['nombre'], ENT_QUOTES, 'UTF-8') ?>
            </option>
          <?php endforeach; ?>
        </select>
      </td>
    </tr>

    <tr>
      <td><label for="descripcion">Descripción:</label></td>
      <td>
        <textarea
          name="descripcion"
          id="descripcion"
          rows="4"
          cols="50"
          required
        ><?= htmlspecialchars($oldDescripcion, ENT_QUOTES, 'UTF-8') ?></textarea>
      </td>
    </tr>

    <tr>
      <td><strong>Última modificación:</strong></td>
      <td>
        <?= !empty($registro['fecha_modificacion'])
            ? htmlspecialchars($registro['fecha_modificacion'], ENT_QUOTES, 'UTF-8')
            : '<span style="color:red">[Sin fecha registrada]</span>' ?>
      </td>
    </tr>

    <tr>
      <td colspan="2" style="text-align:right;">
        <button type="submit" name="actualizar">Actualizar</button>
        <a href="index.php">Cancelar</a>
      </td>
    </tr>
  </table>
</form>
