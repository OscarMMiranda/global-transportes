<?php
session_start();
require_once '../../includes/conexion.php';

// Verificar acceso solo para administradores
if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol_nombre']) || $_SESSION['rol_nombre'] !== 'admin') {
    header("Location: ../sistema/login.php");
    exit();
}

// Obtener el ID del conductor
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header("Location: conductores.php");
    exit();
}

// Consultar el conductor
$sql = "SELECT * FROM conductores WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$conductor = $result->fetch_assoc();

if (!$conductor) {
    header("Location: conductores.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Conductor</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/base.css">
    <link rel="stylesheet" href="../../css/conductores.css">
    <style>
        .contenedor-formulario {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
        }

        .campo {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .campo label {
            width: 150px;
            font-weight: bold;
        }

        .campo input {
            flex-grow: 1;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .foto-conductor {
            display: flex;
            justify-content: flex-end;
        }

        .foto-conductor img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
        }

        .botones {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>

<div class="contenedor-formulario">
    <h2>Editar Conductor</h2>

    <form action="procesar_edicion.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $conductor['id'] ?>">

        <div class="campo">
            <label for="nombres">Nombres:</label>
            <input type="text" name="nombres" id="nombres" required value="<?= htmlspecialchars($conductor['nombres']) ?>" onkeyup="this.value = this.value.toUpperCase();">
        </div>

        <div class="campo">
            <label for="apellidos">Apellidos:</label>
            <input type="text" name="apellidos" id="apellidos" required value="<?= htmlspecialchars($conductor['apellidos']) ?>" onkeyup="this.value = this.value.toUpperCase();">
        </div>

        <div class="campo">
            <label for="dni">DNI:</label>
            <input type="text" name="dni" id="dni" required value="<?= htmlspecialchars($conductor['dni']) ?>" pattern="\d{8}">
        </div>

        <div class="campo">
            <label for="licencia_conducir">Licencia:</label>
            <input type="text" name="licencia_conducir" id="licencia_conducir" required value="<?= htmlspecialchars($conductor['licencia_conducir']) ?>" pattern="^[A-Z]\d{8}$">
        </div>

        <div class="campo">
            <label for="telefono">Teléfono:</label>
            <input type="text" name="telefono" id="telefono" required value="<?= htmlspecialchars($conductor['telefono']) ?>">
        </div>

        <div class="campo">
            <label for="correo">Correo:</label>
            <input type="email" name="correo" id="correo" required value="<?= htmlspecialchars($conductor['correo']) ?>">
        </div>

        <div class="campo">
            <label for="activo">Estado:</label>
            <input type="checkbox" name="activo" id="activo" <?= $conductor['activo'] ? 'checked' : '' ?>> Activo
        </div>

        <div class="campo">
            <label for="foto">Foto del Conductor:</label>
            <input type="file" name="foto" id="foto" accept="image/*">
        </div>

        <div class="foto-conductor">
            <?php if (!empty($conductor['foto'])) { ?>
                <img src="<?= htmlspecialchars($conductor['foto']) ?>" alt="Foto del Conductor">
            <?php } else { ?>
                <p>📷 No hay foto disponible.</p>
            <?php } ?>
        </div>

        <div class="botones">
            <button type="submit" class="btn-editar">Guardar Cambios</button>
            <a href="conductores.php" class="btn-volver">⬅️ Volver</a>
        </div>
    </form>
</div>

</body>
</html>
