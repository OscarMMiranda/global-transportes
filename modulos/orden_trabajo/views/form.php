<?php
    //  archivo    :   /modulos/orden_trabajo/controllers/form.php

session_start();
require_once '../../includes/conexion.php';
require_once '../../includes/header_erp.php';

// Obtener el año actual
$añoActual = date('Y');

// Consultar el último número de orden correctamente en `numero_ot`
$sqlUltimaOrden = "SELECT numero_ot FROM ordenes_trabajo WHERE YEAR(fecha) = $añoActual ORDER BY CAST(SUBSTRING_INDEX(numero_ot, '-', 1) AS UNSIGNED) DESC LIMIT 1";
$resultUltimaOrden = $conn->query($sqlUltimaOrden);

if ($resultUltimaOrden && $resultUltimaOrden->num_rows > 0) {
    $filaUltimaOrden = $resultUltimaOrden->fetch_assoc();
    $ultimoNumeroOT = intval(explode("-", $filaUltimaOrden['numero_ot'])[0]); // Extraer solo el número
    $nuevoNumeroOT = $ultimoNumeroOT + 1;
} else {
    $nuevoNumeroOT = 1; // Si no hay órdenes en el año, comienza desde "1"
}

// Obtener la fecha actual
$fechaActual = date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Crear Nueva Orden</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <script>
    function confirmarCancelacion() {
      return confirm('❌ ¿Seguro que quieres cancelar? Se perderán los datos ingresados.');
    }
  </script>
</head>
<body>
  <div class="container mt-4">
    <h2 class="text-center text-primary mb-4">➕ Crear Nueva Orden</h2>

    <!-- Mostrar mensajes de error si existen -->
    <?php if(isset($_GET['error'])) { ?>
      <div class="alert alert-danger"><?= $_GET['error'] ?></div>
    <?php } ?>

    <form action="guardar_orden.php" method="POST">
      <div class="row">
        <div class="col-md-6">
          <label for="numeroCorrelativo">Número OT</label>
          <input type="text" id="numeroCorrelativo" name="numero_correlativo" class="form-control" value="<?= $nuevoNumeroOT ?>" required>
        </div>
        <div class="col-md-3">
          <label for="anioOT">Año</label>
          <input type="text" id="anioOT" name="anio_ot" class="form-control text-center fw-bold" value="<?= $añoActual ?>" readonly>
        </div>
        <div class="col-md-3">
          <label for="fechaOT">Fecha</label>
          <input type="date" id="fechaOT" name="fecha" class="form-control" value="<?= $fechaActual ?>" required>
        </div>
      </div>
      
      <div class="row mt-3">
        <div class="col-md-6">
          <label for="clienteOT">Cliente</label>
          <select id="clienteOT" name="cliente_id" class="form-control" required>
            <option value="">Selecciona un cliente</option>
            <?php
              $sqlClientes = "SELECT id, nombre FROM clientes ORDER BY nombre ASC";
              $resultClientes = $conn->query($sqlClientes);
              while($cliente = $resultClientes->fetch_assoc()){
                echo "<option value='{$cliente['id']}'>{$cliente['nombre']}</option>";
              }
            ?>
          </select>
        </div>
        <div class="col-md-6">
          <label for="tipoOT">Tipo de OT</label>
          <select id="tipoOT" name="tipo_ot_id" class="form-control" required>
            <option value="">Selecciona un tipo de OT</option>
            <?php
              $sqlTipoOT = "SELECT id, codigo, nombre FROM tipo_ot ORDER BY nombre ASC";
              $resultTipoOT = $conn->query($sqlTipoOT);
              while($tipo = $resultTipoOT->fetch_assoc()){
                echo "<option value='{$tipo['id']}'>{$tipo['codigo']} - {$tipo['nombre']}</option>";
              }
            ?>
          </select>
        </div>
      </div>
      
      <div class="mb-3 mt-3">
        <label for="empresaOT">Empresa</label>
        <select id="empresaOT" name="empresa_id" class="form-control" required>
          <option value="">Selecciona una empresa</option>
          <?php
            $sqlEmpresa = "SELECT id, razon_social FROM empresa ORDER BY razon_social ASC";
            $resultEmpresa = $conn->query($sqlEmpresa);
            while($empresa = $resultEmpresa->fetch_assoc()){
              echo "<option value='{$empresa['id']}'>{$empresa['razon_social']}</option>";
            }
          ?>
        </select>
      </div>
      
      <!-- Campo Orden de Cliente y campo adicional dinámico según Tipo OT -->
      <div class="mb-3 d-flex align-items-center">
        <label for="oc_cliente" class="me-2">Orden de Cliente</label>
        <input type="text" id="oc_cliente" name="oc_cliente" class="form-control w-25" required>
        
        <!-- Contenedor para el campo dinámico -->
        <div id="campo_dinamico_oc" class="d-flex align-items-center ms-3" style="display: none;">
          <label id="label_dinamico_oc" class="me-2"></label>
          <input type="text" id="campo_dinamico_input" name="" class="form-control w-25">
        </div>
      </div>
      
      <button type="submit" class="btn btn-success">✅ Guardar Orden</button>
      <a href="orden_trabajo.php" class="btn btn-secondary" onclick="return confirmarCancelacion()">❌ Cancelar</a>
    </form>
  </div>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Función para actualizar el campo dinámico según el tipo de OT seleccionado
    document.getElementById('tipoOT').addEventListener('change', function() {
      var tipo = parseInt(this.value) || 0;
      var campoDinamico = document.getElementById('campo_dinamico_oc');
      var labelDinamico = document.getElementById('label_dinamico_oc');
      var inputDinamico = document.getElementById('campo_dinamico_input');
      
      // Por defecto ocultamos el campo dinámico y quitamos su atributo "name"
      campoDinamico.style.display = 'none';
      inputDinamico.removeAttribute('name');
      
      // Actualizamos el campo según el valor seleccionado
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
    });
  </script>
</body>
</html>
