<?php
// archivo: /modulos/mantenimiento/tipo_documento/componentes/tabla_tipos_documento.php

// 1. Decodificar datos recibidos por AJAX
$tipos = [];

if (isset($_POST['tipos'])) {
  // Si viene como JSON string, decodificar
  if (is_string($_POST['tipos'])) {
    $tipos = json_decode($_POST['tipos'], true);
  } elseif (is_array($_POST['tipos'])) {
    $tipos = $_POST['tipos'];
  }
}

// 2. Validar que sea un array válido
if (!is_array($tipos) || count($tipos) === 0) {
  echo "<div class='alert alert-warning text-center'>⚠️ No hay tipos de documento disponibles.</div>";
  return;
}
?>
<link rel="stylesheet" href="css/estilos.css">

<div class="table-responsive">
  <table class="table table-striped table-hover align-middle table-sm">
    <thead class="table-light">
      <tr>
        <th>ID</th>
        <th>Icono</th>
        <th>Nombre</th>
        <th>Grupo</th>
        <th>Categoría</th>
        <th>Descripción</th>
        <th>Duración</th>
        <th>Flags</th>
        <th>Estado</th>
        <th class="text-center">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($tipos as $t): ?>
        <tr>
          <td><?= (int)$t['id'] ?></td>

          <!-- Icono -->
          <td>
            <?php if (!empty($t['icono'])): ?>
              <i class="<?= htmlspecialchars($t['icono']) ?> text-secondary"></i>
            <?php endif; ?>
          </td>

          <!-- Nombre con color -->
          <td>
            <span class="badge text-dark" style="background-color:<?= htmlspecialchars($t['color_etiqueta']) ?>">
              <?= htmlspecialchars($t['nombre']) ?>
            </span>
          </td>

          <!-- Grupo -->
          <td><?= htmlspecialchars($t['grupo']) ?></td>

          <!-- Categoría -->
          <td><?= htmlspecialchars($t['categoria']) ?></td>

          <!-- Descripción -->
          <td><?= htmlspecialchars($t['descripcion']) ?></td>

          <!-- Duración -->
          <td><?= (int)$t['duracion_meses'] ?> meses</td>

          <!-- Flags -->
          <td>
            <?php if (!empty($t['requiere_inicio'])): ?>
              <span class="badge bg-info text-dark" title="Requiere inicio"><i class="fas fa-play"></i></span>
            <?php endif; ?>
            <?php if (!empty($t['requiere_vencimiento'])): ?>
              <span class="badge bg-warning text-dark" title="Requiere vencimiento"><i class="fas fa-hourglass-end"></i></span>
            <?php endif; ?>
            <?php if (!empty($t['requiere_archivo'])): ?>
              <span class="badge bg-secondary" title="Requiere archivo"><i class="fas fa-paperclip"></i></span>
            <?php endif; ?>
          </td>

          <!-- Estado -->
          <td>
            <span class="badge <?= $t['estado'] == 1 ? 'bg-success' : 'bg-secondary' ?>">
              <?= $t['estado'] == 1 ? 'Activo' : 'Inactivo' ?>
            </span>
          </td>

         <!-- Acciones -->
<td class="text-center">
  <?php if ($t['estado'] == 1): ?>
    <!-- Editar solo si está activo -->
    <a href="index.php?id=<?= $t['id'] ?>" class="btn btn-sm btn-outline-primary me-1" title="Editar">
      <i class="fas fa-edit"></i>
    </a>
    <!-- Desactivar -->
    <button class="btn btn-sm btn-outline-danger btn-desactivar me-1" data-id="<?= $t['id'] ?>" title="Desactivar">
      <i class="fas fa-trash-alt"></i>
    </button>
  <?php else: ?>
    <!-- Activar -->
    <button class="btn btn-sm btn-outline-success btn-activar me-1" data-id="<?= $t['id'] ?>" title="Activar">
      <i class="fas fa-check-circle"></i>
    </button>
  <?php endif; ?>

  <!-- Ver (siempre disponible) -->
  <a href="index.php?id=<?= $t['id'] ?>&ver=1" class="btn btn-sm btn-outline-secondary" title="Ver">
    <i class="fas fa-eye"></i>
  </a>
</td>


          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>