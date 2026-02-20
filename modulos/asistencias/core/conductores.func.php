<?php
	// archivo: /modulos/asistencias/core/conductores.func.php
	// FUNCIONES DEL SUBMÓDULO: CONDUCTORES

	// Obtener lista de conductores por empresa
function obtener_conductores_por_empresa($conn, $empresa_id)
{
    if ($empresa_id == 0) {
        // Todas las empresas
        $sql = "SELECT id, CONCAT(nombres, ' ', apellidos) AS nombre
                FROM conductores
                WHERE activo = 1
                ORDER BY apellidos ASC, nombres ASC";
        $res = $conn->query($sql);

        $lista = [];
        while ($row = $res->fetch_assoc()) {
            $lista[] = $row;
        }
        return $lista;
    }

    // Empresa específica
    $sql = "SELECT id, CONCAT(nombres, ' ', apellidos) AS nombre
            FROM conductores
            WHERE empresa_id = ?
              AND activo = 1
            ORDER BY apellidos ASC, nombres ASC";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $empresa_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id, $nombre);

    $lista = [];
    while (mysqli_stmt_fetch($stmt)) {
        $lista[] = ['id' => $id, 'nombre' => $nombre];
    }

    mysqli_stmt_close($stmt);
    return $lista;
}



// Obtener conductor por ID
function obtener_conductor_por_id($conn, $id)
{
    $sql = "SELECT id, nombres, empresa_id 
            FROM conductores 
            WHERE id = ? LIMIT 1";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result($stmt, $rid, $nombres, $empresa_id);

    if (mysqli_stmt_fetch($stmt)) {
        mysqli_stmt_close($stmt);
        return array(
            'id' => $rid,
            'nombres' => $nombres,
            'empresa_id' => $empresa_id
        );
    }

    mysqli_stmt_close($stmt);
    return null;
}



// Obtener todos los conductores (uso general del módulo)
function obtener_conductores($conn)
{
    $sql = "SELECT id, nombres, apellidos, empresa_id
            FROM conductores
            WHERE activo = 1
            ORDER BY nombres, apellidos";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result($stmt, $id, $nombres, $apellidos, $empresa_id);

    $lista = array();
    while (mysqli_stmt_fetch($stmt)) {
        $lista[] = array(
            'id' => $id,
            'nombres' => $nombres,
            'apellidos' => $apellidos,
            'empresa_id' => $empresa_id
        );
    }

    mysqli_stmt_close($stmt);
    return $lista;
}

