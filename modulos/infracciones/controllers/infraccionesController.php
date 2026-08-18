<?php
// archivo: /modulos/infracciones/controllers/InfraccionesController.php

require_once __DIR__ . '/../models/InfraccionesModel.php';

class InfraccionesController {

    /**
     * @var InfraccionesModel
     */
    public $model;

    /**
     * @var mysqli
     */
    public $db;

    /**
     * Constructor
     */
    public function __construct($db){
        $this->db = $db;                     // ← AHORA EL EDITOR RECONOCE $db
        $this->model = new InfraccionesModel($db);
    }

    /* ============================================================
       LISTAR (ACTIVAS / INACTIVAS)
       ============================================================ */
    public function listar($filtros = []){
        return $this->model->listar($filtros);
    }

    /* ============================================================
       ENTIDADES
       ============================================================ */
    public function entidades(){
        return $this->model->entidades();
    }

    /* ============================================================
       OBTENER POR ID
       ============================================================ */
    public function obtener($id){
        return $this->model->obtener($id);
    }

    /* ============================================================
       VALIDAR CÓDIGO ÚNICO
       ============================================================ */
    public function existeCodigo($codigo, $entidad_emisora_id, $excluirId = 0){
        return $this->model->existeCodigo($codigo, $entidad_emisora_id, $excluirId);
    }

    /* ============================================================
       GUARDAR
       ============================================================ */
    public function guardar($data){

        $codigo  = $data['codigo'];
        $entidad = intval($data['entidad_emisora_id']);

        if ($this->model->existeCodigo($codigo, $entidad)) {
            return array(
                "ok" => false,
                "msg" => "El código '{$codigo}' ya está registrado como Activo para esta entidad."
            );
        }

        $porc = floatval($data['porcentaje_uit']);
        $uit  = $this->model->getUitVigente();

        if ($porc > 0 && $uit > 0) {
            $data['monto_base'] = ($porc / 100) * $uit;
        } else {
            $data['monto_base'] = isset($data['monto_base']) ? floatval($data['monto_base']) : 0.00;
        }

        $ok = $this->model->guardar($data);

        return array(
            "ok" => $ok ? true : false,
            "msg" => $ok ? "Registrado correctamente" : "No se pudo registrar"
        );
    }

    /* ============================================================
       ACTUALIZAR
       ============================================================ */
    public function actualizar($data){

        $id      = intval($data['id']);
        $codigo  = $data['codigo'];
        $entidad = intval($data['entidad_emisora_id']);

        if ($this->model->existeCodigo($codigo, $entidad, $id)) {
            return array(
                "ok" => false,
                "msg" => "El código '$codigo' ya está registrado como Activo para esta entidad."
            );
        }

        $actual = $this->model->obtener($id);
        $monto_guardado = floatval($actual['monto_base']);

        $porc = floatval($data['porcentaje_uit']);
        $uit  = $this->model->getUitVigente();

        if ($porc > 0 && $uit > 0) {
            $data['monto_base'] = ($porc / 100) * $uit;
        } else {
            $data['monto_base'] = $monto_guardado;
        }

        $ok = $this->model->actualizar($data);

        return array(
            "ok" => $ok ? true : false,
            "msg" => $ok ? "Actualizado correctamente" : "No se pudo actualizar"
        );
    }

    /* ============================================================
       SOFT DELETE (DESACTIVAR)
       ============================================================ */
    public function desactivar($id){
        return $this->model->desactivar($id);
    }

    /* ============================================================
       REACTIVAR
       ============================================================ */
    public function reactivar($id){
        return $this->model->reactivar($id);
    }

    /* ============================================================
       DESACTIVAR CON AUDITORÍA
       ============================================================ */
    public function desactivarConAuditoria($id, $usuario, $usuario_id, $ip, $host, $user_agent, $motivo)
    {
        $inf = $this->model->obtener($id);
        if (!$inf) {
            return false;
        }

        $sqlAud = "
            INSERT INTO infracciones_auditoria (
                infraccion_id,
                usuario,
                usuario_id,
                ip,
                host,
                user_agent,
                motivo,
                fecha
            ) VALUES (
                $id,
                '" . $this->db->real_escape_string($usuario) . "',
                $usuario_id,
                '" . $this->db->real_escape_string($ip) . "',
                '" . $this->db->real_escape_string($host) . "',
                '" . $this->db->real_escape_string($user_agent) . "',
                '" . $this->db->real_escape_string($motivo) . "',
                NOW()
            )
        ";

        $this->db->query($sqlAud);

        return $this->model->desactivar($id);
    }

    /* ============================================================
       REACTIVAR CON AUDITORÍA
       ============================================================ */
    public function reactivarConAuditoria($id, $usuario, $usuario_id, $ip, $host, $user_agent, $motivo)
    {
        $inf = $this->model->obtener($id);
        if (!$inf) {
            return false;
        }

        $sqlAud = "
            INSERT INTO infracciones_auditoria (
                infraccion_id,
                usuario,
                usuario_id,
                ip,
                host,
                user_agent,
                motivo,
                fecha
            ) VALUES (
                $id,
                '" . $this->db->real_escape_string($usuario) . "',
                $usuario_id,
                '" . $this->db->real_escape_string($ip) . "',
                '" . $this->db->real_escape_string($host) . "',
                '" . $this->db->real_escape_string($user_agent) . "',
                '" . $this->db->real_escape_string($motivo) . "',
                NOW()
            )
        ";

        $this->db->query($sqlAud);

        return $this->model->reactivar($id);
    }

}
?>
