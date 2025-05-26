<?php require_once '../../includes/conexion.php'; ?>
<?php require_once '../../includes/header_erp.php'; ?>


<script>
document.addEventListener("DOMContentLoaded", function () {
    const inputs = document.querySelectorAll("input[type='text'], input[type='number'], select");

    inputs.forEach(input => {
        if (input.name !== "observaciones") { // Excluir campo Observaciones
            input.addEventListener("input", function () {
                this.value = this.value.toUpperCase();
            });
        }
    });
});
</script>




<div class="container mt-4">
    <h2 class="text-center mb-4">Registrar Vehículo</h2>

    <form action="registrar_vehiculos.php" method="POST">
        
    <div class="mb-3">
            <label class="form-label">Placa:</label>
            <input type="text" name="placa" class="form-control" required>
</div>


        
    <div class="mb-3">
        <label class="form-label">Tipo de Vehículo:</label>
        <select name="tipo_id" class="form-select">
            <option value="" selected disabled>Seleccione un tipo...</option> <!-- Opción inicial en blanco -->
            <?php
                $sql = "SELECT id, nombre FROM tipo_vehiculo ORDER BY nombre ASC";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) 
                    {
                    while ($row = $result->fetch_assoc()) 
                        {
                        echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
                        }
                    } 
                else 
                    {
                        echo "<option disabled>No hay tipos de vehículos registrados</option>";
                    }
            ?>
        </select>
    </div>


    <div class="mb-3">
        <label class="form-label">Marca:</label>
        <select name="marca_id" class="form-select">
            <option value="" selected disabled>Seleccione una marca...</option>
            <?php
                $sql = "SELECT id, nombre FROM marca_vehiculo ORDER BY nombre ASC";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) 
                    {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
                    }
                    } 
                else 
                    {
                    echo "<option disabled>No hay marcas registradas</option>";
                    }
            ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Modelo:</label>
        <input type="text" name="modelo" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Año:</label>
        <input type="number" name="anio" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Empresa:</label>
        <select name="empresa_id" class="form-select">
            <option value="" selected disabled>Seleccione una empresa...</option>
            <?php
                $sql = "SELECT id, razon_social FROM empresa ORDER BY razon_social ASC";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) 
                    {
                    while ($row = $result->fetch_assoc()) 
                        {
                        echo "<option value='{$row['id']}'>{$row['razon_social']}</option>";
                        }
                    } 
                else 
                    {
                    echo "<option disabled>No hay empresas registradas</option>";
                    }
            ?>
        </select>
    </div>

        <div class="mb-3">
            <label class="form-label">Estado del Vehículo:</label>
            <select name="estado_id" class="form-select">
            <option value="" selected disabled>Seleccione un estado...</option>
            <?php
                $sql = "SELECT id, nombre FROM estado_vehiculo ORDER BY nombre ASC";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
                    }
                } else {
                echo "<option disabled>No hay estados registrados</option>";
                }
            ?>
            </select>
        </div>



        <div class="mb-3">
            <label class="form-label">Observaciones:</label>
            <textarea name="observaciones" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-success">Registrar Vehículo</button>
    </form>
</div>

<?php require_once '../../includes/footer_erp.php'; ?>

