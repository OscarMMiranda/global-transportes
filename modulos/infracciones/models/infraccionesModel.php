<?php
// archivo: /modulos/infracciones/models/InfraccionesModel.php

class InfraccionesModel {

    private $db;

    public function __construct($db){
        $this->db = $db;
    }

    /* ============================================================
       OBTENER UIT VIGENTE
       ============================================================ */
    public function getUitVigente()
    {
        $sql = "SELECT valor FROM uit ORDER BY anio DESC LIMIT 1";
        $res = $this->db->query($sql);

        if ($res && $res->num_rows > 0) {
            $row = $res->fetch_assoc();
            return floatval($row['valor']);
        }

        return 0;
    }

    /* ============================================================
       LISTAR (SOLO ACTIVAS)
       ============================================================ */
    public function listar(){
        $sql = "SELECT i.*, e.nombre AS entidad_nombre
                FROM infracciones i
                LEFT JOIN entidad_emisora e ON e.id = i.entidad_emisora_id
                WHERE i.estado = 'Activo'
                ORDER BY i.codigo ASC";

        $res = $this->db->query($sql);
        $data = array();

        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }

    /* ============================================================
       ENTIDADES
       ============================================================ */
    public function entidades(){
        $sql = "SELECT * FROM entidad_emisora ORDER BY nombre ASC";

        $res = $this->db->query($sql);
        $data = array();

        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }

    /* ============================================================
       OBTENER POR ID
       ============================================================ */
    public function obtener($id){
        $id = intval($id);

        $sql = "SELECT i.*, e.nombre AS entidad_nombre
                FROM infracciones i
                LEFT JOIN entidad_emisora e ON e.id = i.entidad_emisora_id
                WHERE i.id = $id
                LIMIT 1";

        $res = $this->db->query($sql);

        if ($res && $res->num_rows > 0) {
            return $res->fetch_assoc();
        }

        return false;
    }

    /* ============================================================
       VALIDAR CÓDIGO ÚNICO (CORREGIDO)
       ============================================================ */
    public function existeCodigo($codigo, $entidad_emisora_id, $excluirId = 0)
    {
        $codigo  = $this->db->real_escape_string($codigo);
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
       GUARDAR (USANDO % UIT)
       ============================================================ */
    public function guardar($data){

        $codigo  = $this->db->real_escape_string($data['codigo']);
        $desc    = $this->db->real_escape_string($data['descripcion']);
        $grav    = $this->db->real_escape_string($data['gravedad']);
        $puntos  = intval($data['puntos']);
        $porc    = floatval($data['porcentaje_uit']);
        $entidad = intval($data['entidad_emisora_id']);

        $sql = "INSERT INTO infracciones 
                (codigo, descripcion, gravedad, puntos, porcentaje_uit, entidad_emisora_id, estado)
                VALUES 
                ('$codigo', '$desc', '$grav', $puntos, $porc, $entidad, 'Activo')";

        return $this->db->query($sql);
    }

    /* ============================================================
       ACTUALIZAR (USANDO % UIT)
       ============================================================ */
    public function actualizar($data){

        $id      = intval($data['id']);
        $codigo  = $this->db->real_escape_string($data['codigo']);
        $desc    = $this->db->real_escape_string($data['descripcion']);
        $grav    = $this->db->real_escape_string($data['gravedad']);
        $puntos  = intval($data['puntos']);
        $porc    = floatval($data['porcentaje_uit']);
        $entidad = intval($data['entidad_emisora_id']);

        $sql = "UPDATE infracciones SET
                    codigo = '$codigo',
                    descripcion = '$desc',
                    gravedad = '$grav',
                    puntos = $puntos,
                    porcentaje_uit = $porc,
                    entidad_emisora_id = $entidad,
                    fecha_modificacion = NOW()
                WHERE id = $id";

        return $this->db->query($sql);
    }

    /* ============================================================
       HARD DELETE (NO USAR)
       ============================================================ */
    public function eliminar($id){
        $id = intval($id);
        $sql = "DELETE FROM infracciones WHERE id = $id";
        return $this->db->query($sql);
    }

    /* ============================================================
       VALIDAR SI TIENE PAPELETAS ASOCIADAS
       ============================================================ */
    public function tienePapeletas($id)
    {
        $id = intval($id);
        if ($id <= 0) return false;

        $sql = "SELECT COUNT(*) AS total 
                FROM papeletas 
                WHERE infraccion_id = $id";

        $res = $this->db->query($sql);
        if (!$res) return false;

        $row = $res->fetch_assoc();
        return intval($row['total']) > 0;
    }

    /* ============================================================
       SOFT DELETE (DESACTIVAR)
       ============================================================ */
    public function desactivar($id)
    {
        $id = intval($id);
        if ($id <= 0) return false;

        $sql = "UPDATE infracciones 
                SET estado = 'Inactivo',
                    fecha_modificacion = NOW()
                WHERE id = $id";

        return $this->db->query($sql) ? true : false;
    }
}
?>
