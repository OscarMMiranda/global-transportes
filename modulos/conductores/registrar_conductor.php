<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Conductor</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/base.css">
    <link rel="stylesheet" href="../../css/conductores.css">
    <script>
        function validarDNI(input) {
            let valor = input.value.replace(/\D/g, ''); // Eliminar caracteres no numéricos
            if (valor.length > 8) valor = valor.substring(0, 8); // Limitar a 8 dígitos
            input.value = valor;
        }

        function validarLicencia(input) {
            let valor = input.value.toUpperCase().replace(/[^A-Z0-9]/g, ''); // Eliminar caracteres no válidos
            if (valor.length > 9) valor = valor.substring(0, 9); // Limitar a 9 caracteres (1 letra + 8 números)
            input.value = valor;
        }
    </script>
</head>
<body>

<div class="contenedor-formulario">
    <h2>Registrar Nuevo Conductor</h2>
    
    <form method="POST" action="procesar_registro.php">
        <label>Nombres:</label>
        <input type="text" name="nombres" id="nombres" required placeholder="Ej: Juan Carlos" onkeyup="this.value = this.value.toUpperCase();">
        
        <label>Apellidos:</label>
        <input type="text" name="apellidos" id="apellidos" required placeholder="Ej: Pérez Gómez" onkeyup="this.value = this.value.toUpperCase();">
        
        <label>DNI:</label>
        <input type="text" name="dni" id="dni" required placeholder="Ej: 12345678" pattern="\d{8}" oninput="validarDNI(this)">
        
        <label>Licencia:</label>
        <input type="text" name="licencia_conducir" id="licencia" required placeholder="Ej: A12345678" pattern="^[A-Z]\d{8}$" oninput="validarLicencia(this)">
        
        <label>Teléfono:</label>
        <input type="text" name="telefono" required placeholder="Ej: 987654321">
        
        <label>Correo:</label>
        <input type="email" name="correo" placeholder="Ej: ejemplo@mail.com">
        
        <button type="submit" class="btn-registrar">Registrar Conductor</button>
    </form>

    <a href="conductores.php" class="btn-volver">⬅️ Volver a Conductores</a>
</div>

</body>
</html>
