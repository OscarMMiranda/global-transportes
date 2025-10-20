<?php
// archivo: /modulos/mantenimiento/agencia_aduana/modelo/AgenciaModel.php

class AgenciaModel {
  private $conn;

  public function __construct($conexion) {
    $this->conn = $conexion;
  }

  // 🔍 Listar agencias activas
  public function listarActivas() {
    $sql = "SELECT id, nombre, ruc, direccion, fecha_creacion 
            FROM agencias_aduanas 
            WHERE estado = 1 
            ORDER BY fecha_creacion DESC";

    $resultado = $this->conn->query($sql);
    if (!$resultado) {
      error_log("❌ Error SQL en listarActivas: " . $this->conn->error);
      return array();
    }

    $agencias = array();
    while ($fila = $resultado->fetch_assoc()) {
      error_log("📌 Activa: " . $fila['id'] . " | " . $fila['nombre']);
      $agencias[] = $fila;
    }

    return $agencias;
  }

  // 🔴 Listar agencias eliminadas
  public function listarEliminadas() {
    $sql = "SELECT id, nombre, ruc, direccion, fecha_creacion, ubigeo
            FROM agencias_aduanas
            WHERE estado = 0
            ORDER BY fecha_creacion DESC";

    $resultado = $this->conn->query($sql);
    if (!$resultado) {
      error_log("❌ Error SQL en listarEliminadas: " . $this->conn->error);
      return array();
    }

    $agencias = array();
    while ($fila = $resultado->fetch_assoc()) {
      error_log("🗑 Eliminada: " . $fila['id'] . " | Ubigeo: " . $fila['ubigeo']);
      $agencias[] = $fila;
    }

    return $agencias;
  }

  // 🗑 Eliminar agencia
  public function eliminar($id) {
    $stmt = $this->conn->prepare("UPDATE agencias_aduanas SET estado = 0 WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
  }

  // ⟳ Reactivar agencia
  public function reactivar($id) {
    $stmt = $this->conn->prepare("UPDATE agencias_aduanas SET estado = 1 WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
  }

  // 🆕 Guardar nueva agencia
  public function guardar($data) {
    $stmt = $this->conn->prepare("
      INSERT INTO agencias_aduanas 
      (ruc, nombre, direccion, ubigeo, estado, fecha_creacion) 
      VALUES (?, ?, ?, ?, 1, NOW())
    ");
    $stmt->bind_param("ssss", $data['ruc'], $data['nombre'], $data['direccion'], $data['ubigeo']);
    return $stmt->execute();
  }

  // ✎ Obtener una agencia por ID
  	public function obtenerPorId($id) {
    	$stmt = $this->conn->prepare(
        	"SELECT 
      a.*, 
      d.nombre AS distrito_nombre, 
      p.nombre AS provincia_nombre, 
      dep.nombre AS departamento_nombre
    FROM agencias_aduanas a
    LEFT JOIN distritos d ON a.distrito_id = d.id
    LEFT JOIN provincias p ON a.provincia_id = p.id
    LEFT JOIN departamentos dep ON a.departamento_id = dep.id
    WHERE a.id = ?
		");
    	$stmt->bind_param("i", $id);
    	$stmt->execute();
    	$resultado = $stmt->get_result();
    	return $resultado ? $resultado->fetch_assoc() : null;
  }

  // 💾 Actualizar agencia existente
  public function actualizar($data) {
    $stmt = $this->conn->prepare("
      UPDATE agencias_aduanas 
      SET nombre = ?, ruc = ?, direccion = ?, ubigeo = ?, telefono = ?, correo_general = ?, contacto = ? 
      WHERE id = ?
    ");
    $stmt->bind_param(
      "sssssssi",
      $data['nombre'],
      $data['ruc'],
      $data['direccion'],
      $data['ubigeo'],
      $data['telefono'],
      $data['correo_general'],
      $data['contacto'],
      $data['id']
    );
    return $stmt->execute();
  }
}