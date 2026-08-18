<?php
	//	modulos/infracciones/models/infraccionesModel.php

class InfraccionesModel {

    private $db;

    public function __construct($db){
        $this->db = $db;
    }

    /* ============================================================
       UIT VIGENTE
       ============================================================ */
    public function getUitVigente(){
        $sql = "SELECT valor FROM uit WHERE estado = 1 LIMIT 1";
        $res = $this->db->query($sql);
        if ($res && $res->num_rows > 0) {
            return floatval($res->fetch_assoc()['valor']);
        }
        return 0;
    }

    /* ============================================================
       LISTAR (ACTIVAS / INACTIVAS)
       ============================================================ */
    public function listar($filtros = array()){

        $estado = isset($filtros['estado']) ? $this->db->real_escape_string($filtros['estado']) : 'Activo';

        $sql = "SELECT i.*, e.nombre AS entidad_nombre
                FROM infracciones i
                LEFT JOIN entidad_emisora e ON e.id = i.entidad_emisora_id
                WHERE i.estado = '$estado'
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
        if ($res) while ($row = $res->fetch_assoc()) $data[] = $row;
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
        return ($res && $res->num_rows > 0) ? $res->fetch_assoc() : false;
    }

    /* ============================================================
       VALIDAR CÓDIGO ÚNICO
       ============================================================ */
    public function existeCodigo($codigo, $entidad_emisora_id, $excluirId = 0){
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
       GUARDAR
       ============================================================ */
    public function guardar($data){

        $codigo  = $this->db->real_escape_string($data['codigo']);
        $desc    = $this->db->real_escape_string($data['descripcion']);
        $grav    = $this->db->real_escape_string($data['gravedad']);
        $puntos  = intval($data['puntos']);
        $porc    = floatval($data['porcentaje_uit']);
        $monto   = floatval($data['monto_base']);
        $entidad = intval($data['entidad_emisora_id']);

        $sql = "INSERT INTO infracciones 
                (codigo, descripcion, gravedad, puntos, porcentaje_uit, monto_base, entidad_emisora_id, estado, fecha_creacion)
                VALUES 
                ('$codigo', '$desc', '$grav', $puntos, $porc, $monto, $entidad, 'Activo', NOW())";

        return $this->db->query($sql);
    }

    /* ============================================================
       ACTUALIZAR
       ============================================================ */
    public function actualizar($data){

        $id      = intval($data['id']);
        $codigo  = $this->db->real_escape_string($data['codigo']);
        $desc    = $this->db->real_escape_string($data['descripcion']);
        $grav    = $this->db->real_escape_string($data['gravedad']);
        $puntos  = intval($data['puntos']);
        $porc    = floatval($data['porcentaje_uit']);
        $monto   = floatval($data['monto_base']);
        $entidad = intval($data['entidad_emisora_id']);

        $sql = "UPDATE infracciones SET
                    codigo = '$codigo',
                    descripcion = '$desc',
                    gravedad = '$grav',
                    puntos = $puntos,
                    porcentaje_uit = $porc,
                    monto_base = $monto,
                    entidad_emisora_id = $entidad,
                    fecha_modificacion = NOW()
                WHERE id = $id";

        return $this->db->query($sql);
    }

    /* ============================================================
       SOFT DELETE (DESACTIVAR)
       ============================================================ */
    public function desactivar($id){
        $id = intval($id);
        if ($id <= 0) return false;

        $sql = "UPDATE infracciones 
                SET estado = 'Inactivo',
                    eliminado_por = '" . $this->db->real_escape_string($_SESSION['usuario']) . "',
                    fecha_eliminacion = NOW(),
                    fecha_modificacion = NOW()
                WHERE id = $id";

        return $this->db->query($sql);
    }

    /* ============================================================
       REACTIVAR
       ============================================================ */
    public function reactivar($id){
        $id = intval($id);

        $sql = "UPDATE infracciones 
                SET estado = 'Activo',
                    eliminado_por = NULL,
                    fecha_eliminacion = NULL,
                    fecha_modificacion = NOW()
                WHERE id = $id
                LIMIT 1";

        return $this->db->query($sql);
    }
}
?>
