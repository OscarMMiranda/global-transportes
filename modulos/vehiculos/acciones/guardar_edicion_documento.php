<?php
// archivo: /modulos/vehiculos/acciones/guardar_edicion_documento.php

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

$id = intval($_POST['id']);

$numero = $conn->real_escape_string($_POST['numero']);
$tipo = intval($_POST['tipo_documento_id']);
$fi = $_POST['fecha_inicio'] ? "'".$_POST['fecha_inicio']."'" : "NULL";
$fv = $_POST['fecha_vencimiento'] ? "'".$_POST['fecha_vencimiento']."'" : "NULL";
$obs = $conn->real_escape_string($_POST['observaciones']);

$sql = "UPDATE documentos SET
        tipo_documento_id = $tipo,
        numero = '$numero',
        fecha_inicio = $fi,
        fecha_vencimiento = $fv,
        observaciones = '$obs',
        updated_at = NOW()
        WHERE id = $id";

$conn->query($sql);

echo json_encode(["success" => true]);
