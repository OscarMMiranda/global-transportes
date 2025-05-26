<?php
session_start();
require_once '../../includes/conexion.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

if (!isset($_GET['numero_ot']) || empty($_GET['numero_ot'])) {
    echo "<script>alert('❌ Error: No se ha especificado la orden a editar.'); window.location.href='orden_trabajo.php';</script>";
    exit();
}

$numeroOT = $_GET['numero_ot'];

$sqlOrden = "SELECT * FROM ordenes_trabajo WHERE numero_ot = ?";
$stmtOrden = $conn->prepare($sqlOrden);
$stmtOrden->bind_param("s", $numeroOT);
$stmtOrden->execute();
$resultOrden = $stmtOrden->get_result();

if ($resultOrden->num_rows === 0) {
    echo "<script>alert('❌ Error: La orden de trabajo no existe.'); window.location.href='orden_trabajo.php';</script>";
    exit();
}

$orden = $resultOrden->fetch_assoc();

// Determinamos el valor del campo dinámico según el tipo de OT
$valorDinamico = '';
switch ($orden['tipo_ot_id']) {
    case 2: // Importación
        $valorDinamico = $orden['numero_dam'];
        break;
    case 3: // Exportación
        $valorDinamico = $orden['numero_booking'];
        break;
    case 1: // Nacional
        $valorDinamico = $orden['otros'];
        break;
}

$sqlClientes = "SELECT id, nombre FROM clientes ORDER BY nombre ASC";
$resultClientes = $conn->query($sqlClientes);

$sqlTipoOT = "SELECT id, codigo, nombre FROM tipo_ot ORDER BY nombre ASC";
$resultTipoOT = $conn->query($sqlTipoOT);

$sqlEmpresa = "SELECT id, razon_social FROM empresa ORDER BY razon_social ASC";
$resultEmpresa = $conn->query($sqlEmpresa);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Orden</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
  <div class="container mt-4">
    <h2 class="text-center text-primary mb-4">✏️ Editar Orden #<?= htmlspecialchars($orden['numero_ot']) ?></h2>

    <form method="POST" action="procesar_edicion.php" class="mt-4">
      <!-- Número de OT oculta -->
      <input type="hidden" name="numero_ot" value="<?= htmlspecialchars($orden['numero_ot']) ?>">

      <!-- Fecha -->
      <div class="mb-3">
        <label for="fechaInput">Fecha</label>
        <input type="date" name="fecha" id="fechaInput" class="form-control" value="<?= htmlspecialchars($orden['fecha']) ?>" required>
      </div>

      <!-- Cliente -->
      <div class="mb-3">
        <label for="clienteSelect">Cliente</label>
        <select name="cliente_id" id="clienteSelect" class="form-control" required>
          <option value="">Selecciona un cliente</option>
          <?php while ($cliente = $resultClientes->fetch_assoc()) { ?>
            <option value="<?= htmlspecialchars($cliente['id']) ?>" <?= $cliente['id'] == $orden['cliente_id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($cliente['nombre']) ?>
            </option>
          <?php } ?>
        </select>
      </div>

      <!-- Tipo de OT -->
      <div class="mb-3">
        <label for="tipoOTSelect">Tipo de OT</label>
        <select name="tipo_ot_id" id="tipoOTSelect" class="form-control" required>
          <option value="">Selecciona un tipo de OT</option>
          <?php while ($tipo = $resultTipoOT->fetch_assoc()) { ?>
            <option value="<?= htmlspecialchars($tipo['id']) ?>" <?= $tipo['id'] == $orden['tipo_ot_id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($tipo['codigo']) ?> - <?= htmlspecialchars($tipo['nombre']) ?>
            </option>
          <?php } ?>
        </select>
      </div>

      <!-- Empresa -->
      <div class="mb-3">
        <label for="empresaSelect">Empresa</label>
        <select name="empresa_id" id="empresaSelect" class="form-control" required>
          <option value="">Selecciona una empresa</option>
          <?php while ($empresa = $resultEmpresa->fetch_assoc()) { ?>
            <option value="<?= htmlspecialchars($empresa['id']) ?>" <?= $empresa['id'] == $orden['empresa_id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($empresa['razon_social']) ?>
            </option>
          <?php } ?>
        </select>
      </div>

      <!-- Orden de Cliente y Campo Dinámico -->
      <div class="mb-3 d-flex align-items-center">
        <label for="oc_cliente" class="me-2">Orden de Cliente</label>
        <input type="text" id="oc_cliente" name="oc_cliente" class="form-control w-25" 
               value="<?= htmlspecialchars($orden['oc_cliente']) ?>" required>
        
        <!-- Campo dinámico según el Tipo de OT -->
        <div id="campo_dinamico_oc" class="d-flex align-items-center ms-3" style="display: none;">
          <label id="label_dinamico_oc" class="me-2"></label>
          <input type="text" id="campo_dinamico_input" name="" class="form-control w-25"
                 value="<?= htmlspecialchars($valorDinamico) ?>">
        </div>
      </div>

      <button type="submit" class="btn btn-success">✅ Guardar Cambios</button>
      <a href="orden_trabajo.php" class="btn btn-secondary">⬅️ Volver a Órdenes</a>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function actualizarCampoDinamico() {
      var tipo = parseInt(document.getElementById('tipoOTSelect').value) || 0;
      var campoDinamico = document.getElementById('campo_dinamico_oc');
      var labelDinamico = document.getElementById('label_dinamico_oc');
      var inputDinamico = document.getElementById('campo_dinamico_input');

      // Ocultamos el campo y removemos el atributo "name" sin limpiar el valor pre-cargado
      campoDinamico.style.display = 'none';
      inputDinamico.removeAttribute('name');
      // Se ha comentado la línea de reseteo para mantener el valor que viene del servidor
      // inputDinamico.value = '';

      // Configuramos según el tipo de OT seleccionado
      if (tipo === 2) { // Importación
        labelDinamico.textContent = 'Número DAM:';
        campoDinamico.style.display = 'flex';
        inputDinamico.setAttribute('name', 'numero_dam');
      } else if (tipo === 3) { // Exportación
        labelDinamico.textContent = 'Número de Booking:';
        campoDinamico.style.display = 'flex';
        inputDinamico.setAttribute('name', 'numero_booking');
      } else if (tipo === 1) { // Nacional
        labelDinamico.textContent = 'Otros:';
        campoDinamico.style.display = 'flex';
        inputDinamico.setAttribute('name', 'otros');
      }
    }
    
    document.getElementById('tipoOTSelect').addEventListener('change', actualizarCampoDinamico);
    window.onload = actualizarCampoDinamico;
  </script>
</body>
</html>
