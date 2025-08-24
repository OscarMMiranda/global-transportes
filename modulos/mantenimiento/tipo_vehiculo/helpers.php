<?php
// helpers.php
// Funciones auxiliares para vistas y validaciones en módulos legacy

/**
 * Muestra la fecha de actualización o creación, o un aviso si no existe.
 * @param array $tipo Array con claves 'fecha_actualizacion' y 'fecha_creado'
 * @return string Fecha formateada o mensaje de ausencia
 */
function mostrarFecha($tipo) {
    if (!empty($tipo['fecha_actualizacion'])) {
        return htmlspecialchars($tipo['fecha_actualizacion']);
    } elseif (!empty($tipo['fecha_creado'])) {
        return htmlspecialchars($tipo['fecha_creado']);
    } else {
        return '<span style="color:red">[Sin fecha]</span>';
    }
}

/**
 * Sanitiza un valor para evitar XSS en vistas HTML.
 * @param string|null $valor
 * @return string
 */
function limpiar($valor) {
    return htmlspecialchars(trim((string)$valor));
}

/**
 * Muestra un campo si existe, o un aviso visual si está vacío.
 * @param string|null $valor
 * @param string $campo Nombre del campo para mostrar en el aviso
 * @return string
 */
function mostrarCampo($valor, $campo = 'Dato') {
    if (!empty($valor)) {
        return limpiar($valor);
    } else {
        return '<span style="color:orange">[' . limpiar($campo) . ' vacío]</span>';
    }
}

/**
 * Muestra el estado como texto coloreado.
 * @param string|int $estado
 * @return string
 */
function mostrarEstado($estado) {
    switch ((string)$estado) {
        case '1':
        case 'activo':
            return '<span style="color:green">Activo</span>';
        case '0':
        case 'inactivo':
            return '<span style="color:gray">Inactivo</span>';
        default:
            return '<span style="color:red">[Estado desconocido]</span>';
    }
}

/**
 * Verifica si una clave existe en el array y la retorna sanitizada.
 * @param array $array
 * @param string $clave
 * @return string
 */
function obtenerValor($array, $clave) {
    return isset($array[$clave]) ? limpiar($array[$clave]) : '';
}
