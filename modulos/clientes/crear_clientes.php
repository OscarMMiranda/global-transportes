<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/errores_php.log');
error_reporting(E_ALL);

require_once '../../includes/conexion.php';

if (!isset($_SESSION['rol_nombre']) || !in_array($_SESSION['rol_nombre'], ['admin', 'empleado'])) {
    header("Location: ../../sistema/login.php");
    exit();
}

$departamentos = $conn->query("SELECT id, nombre FROM departamentos ORDER BY nombre");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Cliente</title>
    <link rel="stylesheet" href="../../css/estilo.css">
    <style>
        .formulario {
            max-width: 600px;
            margin: auto;
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .formulario h1 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #333;
        }

        .formulario label {
            display: block;
            margin-top: 1rem;
            font-weight: bold;
            color: #444;
        }

        .formulario input,
        .formulario select {
            width: 100%;
            padding: 0.6rem;
            margin-top: 0.3rem;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .formulario button,
        .formulario .boton-accion {
            display: inline-block;
            margin-top: 1.5rem;
            padding: 0.7rem 1.5rem;
            border: none;
            border-radius: 5px;
            background-color: #007BFF;
            color: #fff;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .formulario .boton-accion {
            background-color: #6c757d;
            margin-left: 1rem;
        }

        .formulario button:hover,
        .formulario .boton-accion:hover {
            background-color: #0056b3;
        }

        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
        }

        .container {
            padding: 2rem 1rem;
        }
    </style>
</head>
<body>
<div class="container">
    <form method="POST" action="guardar_clientes.php" class="formulario">
        <h1>Registrar Cliente</h1>

        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="ruc">RUC:</label>
        <input type="text" id="ruc" name="ruc" maxlength="11" required>

        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion" required>

        <label for="telefono">Teléfono:</label>
        <input type="text" id="telefono" name="telefono" required>

        <label for="correo">Correo:</label>
        <input type="email" id="correo" name="correo" required>

        <label for="departamento">Departamento:</label>
        <select name="departamento_id" id="departamento" required>
            <option value="">Seleccione</option>
            <?php while ($dep = $departamentos->fetch_assoc()): ?>
                <option value="<?= $dep['id'] ?>"><?= htmlspecialchars($dep['nombre']) ?></option>
            <?php endwhile; ?>
        </select>

        <label for="provincia">Provincia:</label>
        <select name="provincia_id" id="provincia" required>
            <option value="">Seleccione un departamento</option>
        </select>

        <label for="distrito">Distrito:</label>
        <select name="distrito_id" id="distrito" required>
            <option value="">Seleccione una provincia</option>
        </select>

        <button type="submit">Guardar Cliente</button>
        <a href="clientes.php" class="boton-accion">Cancelar</a>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function () {
    $('#departamento').change(function () {
        const depId = $(this).val();
        if (depId) {
            $('#provincia').load('../ubicaciones/ajax_provincias.php?departamento_id=' + depId);
            $('#distrito').html('<option value="">Seleccione una provincia</option>');
        } else {
            $('#provincia').html('<option value="">Seleccione un departamento</option>');
            $('#distrito').html('<option value="">Seleccione una provincia</option>');
        }
    });

    $('#provincia').change(function () {
        const provId = $(this).val();
        if (provId) {
            $('#distrito').load('../ubicaciones/ajax_distritos.php?provincia_id=' + provId);
        } else {
            $('#distrito').html('<option value="">Seleccione una provincia</option>');
        }
    });
});
</script>
</body>
</html>
