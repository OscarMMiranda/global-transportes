<?php
// ======================================================
//  ARCHIVO: /modulos/orden_trabajo/controllers/OrdenTrabajoController.php
//  CONTROLADOR: OrdenTrabajoController.php
//  MÓDULO: Órdenes de Trabajo (OT)
//  RESPONSABILIDAD: Orquestar flujo del módulo OT
//  GLOBAL 2026 — Arquitectura Limpia
// ======================================================

require_once __DIR__ . '/../models/OrdenTrabajoModel.php';

class OrdenTrabajoController
{
    private $conn;
    private $model;

    public function __construct($conn)
    {
        $this->conn  = $conn;
        $this->model = new OrdenTrabajoModel($conn);
    }

    // --------------------------------------------------
    // Vista principal del listado OT
    // --------------------------------------------------
    public function listado()
    {
        $semana = isset($_GET['semana']) ? trim($_GET['semana']) : '';
        $estado = isset($_GET['estado']) ? trim($_GET['estado']) : 'TODAS';

        $semanas = $this->model->getSemanas();

        return array(
            'semanas'     => $semanas,
            'semana_sel'  => $semana,
            'estado_sel'  => $estado
        );
    }

    // --------------------------------------------------
    // Crear OT (API)
    // --------------------------------------------------
    public function crear($post)
    {
        return $this->model->crearOT($post);
    }

    // --------------------------------------------------
    // Actualizar OT (API)
    // --------------------------------------------------
    public function actualizar($post)
    {
        return $this->model->actualizarOT($post);
    }

    // --------------------------------------------------
    // Obtener OT por ID (para modal editar)
    // --------------------------------------------------
    public function obtenerPorId($id)
    {
        return $this->model->getOTById($id);
    }
}
