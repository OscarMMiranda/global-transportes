<?php
// archivo: /modulos/usuarios/acciones/exportar_csv.php
// --------------------------------------------------------------
// Exporta usuarios a CSV según estado (0 = activos, 1 = inactivos)
// --------------------------------------------------------------

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/auditoria.php';

require_once __DIR__ . '/../controllers/usuarios_controller.php';

// Conexión
$conn = getConnection();
if (!$conn) {
    die("Error de conexión");
}

// Leer estado (0 activos / 1 inactivos)
$estado = isset($_GET['estado']) ? intval($_GET['estado']) : 0;

// Obtener datos
$usuarios = listarUsuarios($conn, $estado);

// Nombre del archivo
$nombreArchivo = "usuarios_" . ($estado == 0 ? "activos" : "inactivos") . "_" . date("Ymd_His") . ".csv";

// Encabezados para descarga
header("Content-Type: text/csv; charset=utf-8");
header("Content-Disposition: attachment; filename={$nombreArchivo}");

// Abrir salida
$salida = fopen("php://output", "w");

// Escribir encabezados del CSV
fputcsv($salida, [
    "ID",
    "Nombre",
    "Apellido",
    "Usuario",
    "Correo",
    "Rol",
    "Creado en"
]);

// Escribir filas
foreach ($usuarios as $u) {
    fputcsv($salida, [
        $u['id'],
        $u['nombre'],
        $u['apellido'],
        $u['usuario'],
        $u['correo'],
        $u['rol'],
        $u['creado_en']
    ]);
}

fclose($salida);
exit;