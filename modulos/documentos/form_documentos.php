<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Documentos</title>
    <link rel="stylesheet" href="../css/estilos.css"> <!-- Estilos del ERP -->
</head>
<body>
    <header>
        <div class="contenedor">
            <h1 class="titulo-pagina">📄 Registro de Documentos</h1>
        </div>
    </header>

    <main class="contenido">
        <section class="formulario-contenedor">
            <form action="registrar_documento.php" method="POST" enctype="multipart/form-data" class="formulario">
                <label for="vehiculo_id">🚗 Vehículo:</label>
                <select name="vehiculo_id" required class="input-form">
                    <!-- Aquí se llenarán los vehículos desde la base de datos -->
                </select>

                <label for="tipo_documento_id">📜 Tipo de documento:</label>
                <select name="tipo_documento_id" required class="input-form">
                    <!-- Aquí se llenarán los tipos de documentos desde la base de datos -->
                </select>

                <label for="numero_documento">🔢 Número de documento:</label>
                <input type="text" name="numero_documento" required class="input-form">

                <label for="empresa_emisora">🏢 Empresa emisora:</label>
                <input type="text" name="empresa_emisora" required class="input-form">

                <label for="fecha_emision">📅 Fecha de emisión:</label>
                <input type="date" name="fecha_emision" required class="input-form">

                <label for="fecha_vencimiento">⏳ Fecha de vencimiento:</label>
                <input type="date" name="fecha_vencimiento" required class="input-form">

                <label for="archivo">📎 Subir documento:</label>
                <input type="file" name="archivo" required class="input-form">

                <button type="submit" class="boton-accion">✅ Registrar Documento</button>
            </form>
        </section>
    </main>

    <footer class="footer">
    <?php include '../../includes/footer.php'; ?>
    </footer>
</body>
</html>



