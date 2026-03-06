<?php
    // archivo: /modulos/empleados/controllers/empleados_controller.php

require_once __DIR__ . '/../model/EmpleadoModel.php';

function listarEmpleados($conn)
{
    return obtenerEmpleados($conn);
}
