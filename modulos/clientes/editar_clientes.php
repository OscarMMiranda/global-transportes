<?php
require_once '../../includes/conexion.php';
require_once '../../includes/header.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    echo "<p class='mensaje-sistema error'>ID de cliente inválido.</p>";
    include '../../includes/footer.php';
    exit;
}

$query = "SELECT * FROM clientes WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

if (!$resultado || $resultado->num_rows === 0) {
    echo "<p class='mensaje-sistema error'>Cliente no encontrado.</p>";
    include '../../includes/footer.php';
    exit;
}

$cliente = $resultado->fetch_assoc();

// Para precarga de ubicaciones
$dptos = $conn->query("SELECT * FROM departamentos ORDER BY nombre");
$provs = $conn->query("SELECT * FROM provincias WHERE departamento_id = {$cliente['departamento_id']} ORDER BY nombre");
$dists = $conn->query("SELECT * FROM distritos WHERE provincia_id = {$cliente['provincia_id']} ORDER BY nombre");
?>

<link rel="stylesheet" href="../../css/estilo.css">

<main class="contenido">
    <h1>Editar Cliente</h1>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'ok'): ?>
        <p class="mensaje-sistema exito">Cliente actualizado correctamente.</p>
    <?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'error'): ?>
        <p class="mensaje-sistema error">Error al actualizar el cliente.</p>
    <?php endif; ?>

    <div class="login-form">
        <form action="actualizar_clientes.php" method="POST" class="formulario">
            <input type="hidden" name="id" value="<?= htmlspecialchars($cliente['id']) ?>">

            <div class="campo-formulario">
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" required value="<?= htmlspecialchars($cliente['nombre']) ?>">
            </div>

            <div class="campo-formulario">
                <label for="ruc">RUC:</label>
                <input type="text" name="ruc" maxlength="11" pattern="\d{11}" value="<?= htmlspecialchars($cliente['ruc']) ?>">
            </div>

            <div class="campo-formulario">
                <label for="direccion">Dirección:</label>
                <input type="text" name="direccion" value="<?= htmlspecialchars($cliente['direccion']) ?>">
            </div>

            <div class="campo-formulario">
                <label for="telefono">Teléfono:</label>
                <input type="text" name="telefono" maxlength="15" pattern="\d+" value="<?= htmlspecialchars($cliente['telefono']) ?>">
            </div>

            <div class="campo-formulario">
                <label for="correo">Correo:</label>
                <input type="email" name="correo" value="<?= htmlspecialchars($cliente['correo']) ?>">
            </div>

            <div class="campo-formulario">
                <label for="departamento">Departamento:</label>
                <select id="departamento" name="departamento_id" required>
                    <option value="">Seleccione</option>
                    <?php while ($row = $dptos->fetch_assoc()) { ?>
                        <option value="<?= $row['id'] ?>" <?= $row['id'] == $cliente['departamento_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($row['nombre']) ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="campo-formulario">
                <label for="provincia">Provincia:</label>
                <select id="provincia" name="provincia_id" required>
                    <option value="">Seleccione</option>
                    <?php while ($row = $provs->fetch_assoc()) { ?>
                        <option value="<?= $row['id'] ?>" <?= $row['id'] == $cliente['provincia_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($row['nombre']) ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="campo-formulario">
                <label for="distrito">Distrito:</label>
                <select id="distrito" name="distrito_id" required>
                    <option value="">Seleccione</option>
                    <?php while ($row = $dists->fetch_assoc()) { ?>
                        <option value="<?= $row['id'] ?>" <?= $row['id'] == $cliente['distrito_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($row['nombre']) ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-botonera">
                <button type="submit" class="boton-accion guardar">💾 Actualizar</button>
                <a href="listar_clientes.php" class="boton-accion cancelar">Cancelar</a>
                <a href="../erp_dashboard.php" class="boton-accion volver">⬅ Volver al Dashboard</a>
            </div>
        </form>
    </div>
</main>

<!-- jQuery primero -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Luego ubigeo.js -->
<script src="../../js/ubigeo.js"></script>
<!-- Precarga para que ubigeo.js actualice correctamente si cambia el departamento -->
<script>
    $(document).ready(function () {
        $('#departamento').trigger('change');
        setTimeout(() => {
            $('#provincia').val('<?= $cliente['provincia_id'] ?>').trigger('change');
            $('#distrito').val('<?= $cliente['distrito_id'] ?>');
        }, 300); // Espera breve para permitir carga ajax
    });
</script>

<?php include '../../includes/footer.php'; ?>
