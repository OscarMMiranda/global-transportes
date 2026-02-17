<?php
// archivo: /modulos/asistencias/modelos/AsistenciasModel.php

class AsistenciasModel {

    private $cn;

    public function __construct() {
        // Conexión global del ERP
        $this->cn = $GLOBALS['db'];
    }

    /**
     * Obtiene asistencias por mes, año y conductor (opcional)
     */
    public function obtener_reporte_mensual($mes, $anio, $conductor = '') {

        list($sql, $types, $params) = $this->build_query($conductor);

        return $this->execute_query($sql, $types, $params, $mes, $anio);
    }

    /**
     * Construye la consulta SQL dinámica
     */
    private function build_query($conductor) {

        $sql = "
            SELECT 
                ac.fecha,
                CONCAT(c.nombres, ' ', c.apellidos) AS conductor,
                ac.hora_entrada,
                ac.hora_salida,
                ac.es_feriado,
                ac.observacion
            FROM asistencia_conductores ac
            INNER JOIN conductores c ON c.id = ac.conductor_id
            WHERE MONTH(ac.fecha) = ?
              AND YEAR(ac.fecha) = ?
        ";

        $types = "ii";
        $params = array();

        // Filtro opcional por conductor
        if ($conductor !== '') {
            $sql .= " AND ac.conductor_id = ? ";
            $types .= "i";
            $params[] = $conductor;
        }

        $sql .= " ORDER BY ac.fecha ASC";

        return array($sql, $types, $params);
    }

    /**
     * Ejecuta la consulta con bind_param dinámico (compatible con PHP 5.6)
     */
    private function execute_query($sql, $types, $params, $mes, $anio) {

        // ORDEN CORRECTO DE PARÁMETROS
        array_unshift($params, $anio); // segundo parámetro
        array_unshift($params, $mes);  // primer parámetro

        $stmt = $this->cn->prepare($sql);

        if (!$stmt) {
            return array(
                'ok' => false,
                'msg' => 'Error al preparar consulta',
                'error' => $this->cn->error
            );
        }

        // Bind dinámico (PHP 5.6)
        $bind = array();
        $bind[] = $types;

        foreach ($params as $k => $v) {
            $bind[] = &$params[$k];
        }

        call_user_func_array(array($stmt, 'bind_param'), $bind);

        // Ejecutar
        $stmt->execute();

        $result = $stmt->get_result();

        $data = array();

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        $stmt->close();

        return array(
            'ok' => true,
            'data' => $data
        );
    }
}
