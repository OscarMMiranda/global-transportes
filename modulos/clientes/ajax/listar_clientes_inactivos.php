<?php
// archivo: /modulos/clientes/ajax/listar_clientes_inactivos.php
// PHP 5.6 — Versión corporativa

header('Content-Type: application/json');

// ===============================
// CARGAR ENTORNO CORPORATIVO
// ===============================
require_once __DIR__ . '/../../../includes/config.php';

$conexion = getConnection();

if (!$conexion) {
    echo json_encode([
        "data" => [],
        "error" => "No se pudo conectar a la base de datos"
    ]);
    exit;
}

// ===============================
// CONSULTA REAL (SIN ESTADO)
// ===============================
$sql = "
    SELECT 
        id,
        nombre,
        tipo_cliente,
        ruc,
        direccion,
        telefono,
        correo
    FROM clientes
    WHERE estado = 'Inactivo'
      AND deleted_at IS NULL
    ORDER BY nombre ASC
";

$result = mysqli_query($conexion, $sql);

$data = [];

if ($result) {

    while ($row = mysqli_fetch_assoc($result)) {

        $acciones = '
            <button class="btn btn-info btn-sm btnVerCliente" data-id="'.$row['id'].'">
                <i class="fa fa-eye"></i>
            </button>
            <button class="btn btn-secondary btn-sm btnHistorialCliente" data-id="'.$row['id'].'">
				<i class="fa fa-history"></i>
			</button>
            <button class="btn btn-success btn-sm btnActivarCliente" data-id="'.$row['id'].'">
                <i class="fa fa-check"></i>
            </button>
        ';

        $data[] = [
            "nombre"        => $row["nombre"],
            "tipo_cliente"  => $row["tipo_cliente"],
            "ruc"           => $row["ruc"],
            "direccion"     => $row["direccion"],
            "telefono"      => $row["telefono"],
            "correo"        => $row["correo"],
            "acciones"      => $acciones
        ];
    }
}

// ===============================
// RESPUESTA JSON
// ===============================
echo json_encode(["data" => $data]);
exit;
