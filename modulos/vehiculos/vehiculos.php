<?php
require_once '../../includes/conexion.php'; 
require_once '../../includes/header_erp.php'; 
require_once '../../includes/funciones.php'; 

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Obtener la lista de vehículos
$vehiculos = obtenerVehiculos($conn);
?>

<div class="container mt-4">
    <h2 class="text-center mb-4">Listado de Vehículos</h2>

    <div class="d-flex justify-content-end mb-3">
        <a href="form_vehiculos.php" class="btn btn-primary">+ Agregar Nuevo Vehículo</a>
    </div>

    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th><th>Placa</th><th>Marca</th><th>Modelo</th><th>Tipo</th><th>Año</th><th>Empresa</th><th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $vehiculos->fetch_assoc()) { ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['placa']) ?></td>
                <td><?= obtenerMarcaNombre($conn, $row['marca_id']) ?></td>
                <td><?= htmlspecialchars($row['modelo']) ?></td>
                <td><?= obtenerTipoNombre($conn, $row['tipo_id']) ?></td>
                <td><?= htmlspecialchars($row['anio']) ?></td>
                <td><?= obtenerEmpresaNombre($conn, $row['empresa_id']) ?></td>
                <td>
                    <a href="editar_vehiculo.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">✏️ Editar</a>

                    <!-- Formulario para eliminar el vehículo usando POST -->
                    <form action="eliminar_vehiculo.php" method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>">
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que quieres eliminar este vehículo?')">🗑️ Eliminar</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php
require_once '../../includes/footer_erp.php'; 
$conn->close();
?>
