<?php
// archivo: /modulos/empleados/model/EmpleadoModel.php  

function obtenerEmpleados($conn)
{
    $sql = "
        SELECT 
            e.id,
            e.nombres,
            e.apellidos,
            e.dni,
            e.telefono,
            e.correo,
            e.empresa_id,
            emp.razon_social AS empresa_nombre,
            e.distrito_id,
            dist.nombre AS distrito_nombre,
            e.fecha_ingreso,
            e.estado,
            e.foto
        FROM empleados e
        LEFT JOIN empresa emp ON emp.id = e.empresa_id
        LEFT JOIN distritos dist ON dist.id = e.distrito_id
        ORDER BY e.id DESC
    ";

    $result = $conn->query($sql);

    if (!$result) {
        throw new Exception("Error SQL: " . $conn->error);
    }

    $rows = [];

    while ($row = $result->fetch_assoc()) {
        $row['roles'] = obtenerRolesEmpleado($conn, $row['id']);
        $rows[] = $row;
    }

    return $rows;
}

function obtenerRolesEmpleado($conn, $empleadoId)
{
    $sql = "
        SELECT r.nombre
        FROM empleados_roles er
        INNER JOIN roles r ON r.id = er.rol_id
        WHERE er.empleado_id = $empleadoId
    ";

    $result = $conn->query($sql);

    if (!$result) {
        return [];
    }

    $roles = [];
    while ($row = $result->fetch_assoc()) {
        $roles[] = $row['nombre'];
    }

    return $roles;
}
