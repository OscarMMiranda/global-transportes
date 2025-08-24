<?php

use Modules\Asignaciones\Controller\AsignacionesController;

/**
 * Rutas del módulo Asignaciones
 * 
 * Asume que $router ya está instanciado (por ejemplo, AltoRouter, FastRoute, etc.)
 * y que este archivo se incluye dentro del bootstrap de rutas.
 */

$router->get(
    '/asignaciones',
    [AsignacionesController::class, 'index']
);

$router->get(
    '/asignaciones/create',
    [AsignacionesController::class, 'create']
);

$router->post(
    '/asignaciones/store',
    [AsignacionesController::class, 'store']
);

$router->get(
    '/asignaciones/edit/(\d+)',
    [AsignacionesController::class, 'edit']
);

$router->post(
    '/asignaciones/update/(\d+)',
    [AsignacionesController::class, 'update']
);

$router->post(
    '/asignaciones/delete/(\d+)',
    [AsignacionesController::class, 'delete']
);
