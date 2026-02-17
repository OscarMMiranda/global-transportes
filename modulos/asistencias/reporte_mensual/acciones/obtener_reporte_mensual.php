<?php
// archivo : /modulos/asistencias/reporte_mensual/acciones/obtener_reporte_mensual.php
// Acción para obtener los datos del reporte mensual de asistencias según los filtros seleccionados

header('Content-Type: application/json');

require_once '../../../../includes/config.php';
require_once __DIR__ . '/../core/rm_filtros.php';
require_once __DIR__ . '/../core/rm_dias.php';
require_once __DIR__ . '/../core/rm_query.php';
require_once __DIR__ . '/../core/rm_estado.php';
require_once __DIR__ . '/../core/rm_tabla.php';
require_once __DIR__ . '/../core/rm_matriz.php';

$cn = $GLOBALS['db'];

// ======================================================
// 1. LEER FILTROS
// ======================================================
$f = rm_leer_filtros();

// ======================================================
// 2. VALIDAR FILTROS
// ======================================================
$err = rm_validar_filtros($f);
if ($err !== "") {
    echo json_encode([
        'ok'  => false,
        'msg' => $err
    ]);
    exit;
}

// ======================================================
// 3. GENERAR LISTA DE DÍAS DEL MES
// ======================================================
$dias = rm_generar_dias_mes($f['mes'], $f['anio']);

// ======================================================
// 4. CONSTRUIR SQL
// ======================================================
$params = array();
$types  = "";
$sql    = rm_build_sql($f, $params, $types);

// ======================================================
// 5. EJECUTAR CONSULTA
// ======================================================
$q = rm_ejecutar_query($cn, $sql, $params, $types);

if ($q['error']) {
    echo json_encode($q);
    exit;
}

// ======================================================
// 6. FORMATEAR RESPUESTA SEGÚN VISTA
// ======================================================
if ($f['vista'] === 'tabla') {

    echo json_encode(
        rm_formato_tabla($q['res'])
    );
    exit;
}

if ($f['vista'] === 'matriz') {

    echo json_encode(
        rm_formato_matriz($q['res'], $dias)
    );
    exit;
}

// ======================================================
// 7. SI LLEGA AQUÍ, LA VISTA ES INVÁLIDA
// ======================================================
echo json_encode([
    'ok'  => false,
    'msg' => 'Vista no reconocida'
]);
exit;
