<?php
// conectar DB
include '../../conexion.php';

$idConductor = $_POST['conductor_id'];
$idTracto    = $_POST['vehiculo_tracto_id'];
$idCarreta   = $_POST['vehiculo_carreta_id'];
$fechaInicio = $_POST['fecha_inicio'];
$obs         = $_POST['observaciones'] ?? '';

$errors = [];

// verificar tipos
$tipoTracto  = $db->query("SELECT tipo FROM vehiculos WHERE id = $idTracto")->fetchColumn();
$tipoCarreta = $db->query("SELECT tipo FROM vehiculos WHERE id = $idCarreta")->fetchColumn();

if ($tipoTracto !== 'tracto')  $errors[] = "El vehículo tracto no es válido";
if ($tipoCarreta !== 'carreta') $errors[] = "El vehículo carreta no es válido";

// verificar asignación activa
$activo = $db->query("SELECT COUNT(*) FROM asignaciones WHERE id_conductor = $idConductor AND estado = 'activo'")->fetchColumn();
if ($activo > 0) $errors[] = "El conductor ya tiene una asignación activa";

if ($errors) {
  echo json_encode(['ok' => false, 'error' => implode(', ', $errors)]);
  exit;
}

// insertar
$stmt = $db->prepare("INSERT INTO asignaciones (id_conductor, id_vehiculo_tracto, id_vehiculo_carreta, fecha_inicio, estado, observaciones) VALUES (?, ?, ?, ?, 'activo', ?)");
$stmt->execute([$idConductor, $idTracto, $idCarreta, $fechaInicio, $obs]);

echo json_encode(['ok' => true]);
