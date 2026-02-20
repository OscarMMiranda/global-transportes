<?php
	//	archivo: modulos/asistencias/ajax/get_conductores.php

	require_once __DIR__ . '/../../../includes/config.php';
	require_once __DIR__ . '/../core/conductores.func.php';

	$conn = getConnection();

	$empresa_id = isset($_GET['empresa_id']) ? intval($_GET['empresa_id']) : 0;

	if ($empresa_id == 0) {
    // Todas las empresas → obtener todos los conductores activos
    $sql = "SELECT id, CONCAT(nombres, ' ', apellidos) AS nombre
            FROM conductores
            WHERE activo = 1
            ORDER BY apellidos ASC, nombres ASC";

    $res = $conn->query($sql);

    $lista = [];
    while ($row = $res->fetch_assoc()) {
        $lista[] = $row;
    }

    echo json_encode($lista);
    exit;
}


	$lista = obtener_conductores_por_empresa($conn, $empresa_id);

	echo json_encode($lista);
