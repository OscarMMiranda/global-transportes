<?php
// archivo:

require_once __DIR__ . '/../models/InfraccionesModel.php';

class InfraccionesController {

    public $model;

    public function __construct($db){
        $this->model = new InfraccionesModel($db);
    }

    /* ============================================================
       LISTAR
       ============================================================ */
    public function listar(){
        return $this->model->listar();
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
    public function existeCodigo($codigo, $excluirId = 0){
        return $this->model->existeCodigo($codigo, $excluirId);
    }

    /* ============================================================
       GUARDAR
       ============================================================ */
    public function guardar($data){
        return $this->model->guardar($data);
    }

    /* ============================================================
       ACTUALIZAR
       ============================================================ */
    public function actualizar($data){
        return $this->model->actualizar($data);
    }

    /* ============================================================
       HARD DELETE (NO USAR)
       ============================================================ */
    public function eliminar($id){
        return $this->model->eliminar($id);
    }

    /* ============================================================
       VALIDAR SI TIENE PAPELETAS ASOCIADAS
       ============================================================ */
    public function tienePapeletas($id){
        return $this->model->tienePapeletas($id);
    }

    /* ============================================================
       SOFT DELETE (DESACTIVAR)
       ============================================================ */
    public function desactivar($id){
        return $this->model->desactivar($id);
    }
}
?>