<?php
// archivo: /modulos/infracciones/controllers/InfraccionesController.php

require_once __DIR__ . '/../models/InfraccionesModel.php';

class InfraccionesController {

    /**
     * @var InfraccionesModel
     */
    public $model;

    /**
     * @param mysqli $db
     */
    public function __construct($db){
        $this->model = new InfraccionesModel($db);
    }

    /* ============================================================
       LISTAR (con soporte para filtros GET)
       ============================================================ */
    /**
     * @param array $filtros
     * @return array
     */
    public function listar($filtros = []){
        return $this->model->listar($filtros);
    }

    /* ============================================================
       ENTIDADES
       ============================================================ */
    /**
     * @return array
     */
    public function entidades(){
        return $this->model->entidades();
    }

    /* ============================================================
       OBTENER POR ID
       ============================================================ */
    /**
     * @param int $id
     * @return array|null
     */
    public function obtener($id){
        return $this->model->obtener($id);
    }

    /* ============================================================
       VALIDAR CÓDIGO ÚNICO
       ============================================================ */
    /**
     * @param string $codigo
     * @param int $excluirId
     * @return bool
     */
  public function existeCodigo($codigo, $entidad_emisora_id, $excluirId = 0)
{
    $codigo = $this->db->real_escape_string($codigo);
    $entidad = intval($entidad_emisora_id);
    $excluirId = intval($excluirId);

    $sql = "SELECT id 
            FROM infracciones 
            WHERE codigo = '$codigo'
            AND entidad_emisora_id = $entidad
            AND estado = 'Activo'";

    if ($excluirId > 0) {
        $sql .= " AND id <> $excluirId";
    }

    $sql .= " LIMIT 1";

    $res = $this->db->query($sql);

    return ($res && $res->num_rows > 0);
}

    /* ============================================================
       GUARDAR
       ============================================================ */
    /**
     * @param array $data
     * @return array
     */
    public function guardar($data){

        if ($this->model->existeCodigo($data['codigo'])) {
            return array(
                "ok" => false,
                "msg" => "El código '{$data['codigo']}' ya está registrado."
            );
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
    /**
     * @param array $data
     * @return array
     */
    public function actualizar($data){

        $id = intval($data['id']);
        $codigo = $data['codigo'];

        if ($this->model->existeCodigo($codigo, $id)) {
            return array(
                "ok" => false,
                "msg" => "El código '$codigo' ya está registrado en otra infracción."
            );
        }

        $ok = $this->model->actualizar($data);

        return array(
            "ok" => $ok ? true : false,
            "msg" => $ok ? "Actualizado correctamente" : "No se pudo actualizar"
        );
    }

    /* ============================================================
       HARD DELETE (NO USAR)
       ============================================================ */
    /**
     * @param int $id
     * @return bool
     */
    public function eliminar($id){
        return $this->model->eliminar($id);
    }

    /* ============================================================
       VALIDAR SI TIENE PAPELETAS ASOCIADAS
       ============================================================ */
    /**
     * @param int $id
     * @return bool
     */
    public function tienePapeletas($id){
        return $this->model->tienePapeletas($id);
    }

    /* ============================================================
       SOFT DELETE (DESACTIVAR)
       ============================================================ */
    /**
     * @param int $id
     * @return bool
     */
    public function desactivar($id){
        return $this->model->desactivar($id);
    }
}
?>
