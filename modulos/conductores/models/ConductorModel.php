<?php
// archivo: /modulos/conductores/models/ConductorModel.php

require_once __DIR__ . '/../../../includes/db/prep.php';

class ConductorModel {

    public static function listarConductores($conn, $estado = 'activo') {

        $sql = "SELECT 
                    id, nombres, apellidos, dni, licencia_conducir, telefono, correo, direccion,
                    distrito_id, provincia_id, departamento_id, activo, created_at, foto
                FROM conductores";

        if ($estado === 'activo') {
            $sql .= " WHERE activo = 1";
        } elseif ($estado === 'inactivo') {
            $sql .= " WHERE activo = 0";
        }

        $sql .= " ORDER BY apellidos, nombres";

        $stmt = prep($conn, $sql);

        if (!$stmt->execute()) {
            throw new Exception("Error en execute(): " . $stmt->error);
        }

        $stmt->bind_result(
            $id, $nombres, $apellidos, $dni, $licencia, $telefono, $correo,
            $direccion, $distrito_id, $provincia_id, $departamento_id,
            $activo, $created_at, $foto
        );

        $rows = array();

        while ($stmt->fetch()) {
            $rows[] = array(
                'id'                => $id,
                'nombres'           => $nombres,
                'apellidos'         => $apellidos,
                'dni'               => $dni,
                'licencia_conducir' => $licencia,
                'telefono'          => $telefono,
                'correo'            => $correo,
                'direccion'         => $direccion,
                'distrito_id'       => $distrito_id,
                'provincia_id'      => $provincia_id,
                'departamento_id'   => $departamento_id,
                'activo'            => (int)$activo,
                'created_at'        => $created_at,
                'foto'              => $foto
            );
        }

        $stmt->close();
        return $rows;
    }
}
