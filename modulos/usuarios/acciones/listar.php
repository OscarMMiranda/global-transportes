<?php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once __DIR__ . '/../controllers/usuarios_controller.php';

$conn = getConnection();
if (!$conn) {
    echo json_encode(["data" => []]);
    exit;
}

$estado = isset($_GET['estado']) ? intval($_GET['estado']) : 0;

try {

    $rows = listarUsuarios($conn, $estado);
    if (!is_array($rows)) {
        $rows = [];
    }

    $data = [];

    foreach ($rows as $u) {

        // BOTONES
        $btnVer = '
            <button type="button" class="btn btn-outline-info btn-sm ver-usuario"
                data-id="'.$u['id'].'" title="Ver usuario">
                <i class="fa fa-eye"></i>
            </button>';

        $btnEditar = '
            <button type="button" class="btn btn-outline-warning btn-sm btn-editar"
                data-id="'.$u['id'].'" title="Editar usuario">
                <i class="fa fa-edit"></i>
            </button>';

        $btnDesactivar = '';
        if ($estado == 0) {
            $btnDesactivar = '
                <button type="button" class="btn btn-outline-danger btn-sm btn-desactivar"
                    data-id="'.$u['id'].'" title="Desactivar usuario">
                    <i class="fa fa-user-slash"></i>
                </button>';
        }

        $btnRestaurar = '';
        if ($estado == 1) {
            $btnRestaurar = '
                <button type="button" class="btn btn-outline-success btn-sm btn-restaurar"
                    data-id="'.$u['id'].'" title="Restaurar usuario">
                    <i class="fa fa-undo"></i>
                </button>';
        }

        $btnEliminar = '
            <button type="button" class="btn btn-danger btn-sm btn-eliminar"
                data-id="'.$u['id'].'" title="Eliminar usuario definitivamente">
                <i class="fa fa-trash"></i>
            </button>';

        $acciones = '
            <div class="btn-group" role="group">
                '.$btnVer.'
                '.$btnEditar.'
                '.$btnDesactivar.'
                '.$btnRestaurar.'
                '.$btnEliminar.'
            </div>
        ';

        // FORMATO
        $nombreCompleto = $u['nombre'] . ' ' . $u['apellido'];

        $rolBadge = '<span class="badge bg-info text-dark">'.$u['rol_nombre'].'</span>';

        $estadoBadge = $u['eliminado'] == 0
            ? '<span class="badge bg-success">Activo</span>'
            : '<span class="badge bg-secondary">Inactivo</span>';

        $data[] = [
            "id"             => $u['id'],
            "nombre_completo"=> $nombreCompleto,
            "usuario"        => $u['usuario'],
            "rol_nombre"     => $rolBadge,
            "estado"         => $estadoBadge,
            "acciones"       => $acciones
        ];
    }

    echo json_encode(["data" => $data]);

} catch (Exception $e) {
    echo json_encode(["data" => []]);
}