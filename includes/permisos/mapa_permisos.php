<?php
	// archivo: /includes/permisos/mapa_permisos.php

// Mapa oficial de permisos por rol → módulos → acciones

return [

    'admin' => [
        'usuarios'            => ['ver', 'crear', 'editar', 'eliminar'],
        'roles'               => ['ver', 'crear', 'editar', 'eliminar'],
        'permisos'            => ['ver', 'editar'],
        'vehiculos'           => ['ver', 'crear', 'editar', 'eliminar'],
        'mantenimiento'       => ['ver', 'crear', 'editar', 'eliminar'],
        'documentos_vehiculos'=> ['ver', 'crear', 'editar', 'eliminar'],
        'clientes'            => ['ver', 'crear', 'editar', 'eliminar'],
        'asignaciones'        => ['ver', 'crear', 'editar', 'eliminar'],
        'auditoria'           => ['ver'],
        'erp_completo'        => ['ver']
    ],

    'chofer' => [
        'asignaciones'        => ['ver'],
        'documentos_vehiculos'=> ['ver'],
        'orden_trabajo'       => ['ver'],
        'perfil'              => ['ver', 'editar']
    ],

    'cliente' => [
        'documentos'          => ['ver'],
        'orden_trabajo'       => ['ver'],
        'facturacion'         => ['ver']
    ],

    'logistica' => [
        'asignaciones'        => ['ver', 'crear', 'editar'],
        'vehiculos'           => ['ver'],
        'rutas'               => ['ver', 'crear']
    ],

    'mantenimiento' => [
        'mantenimiento'       => ['ver', 'crear', 'editar'],
        'vehiculos'           => ['ver']
    ],

    'rrhh' => [
        'empleados'           => ['ver', 'crear', 'editar'],
        'contratos'           => ['ver', 'crear', 'editar']
    ],

    'auditoria' => [
        'auditoria'           => ['ver'],
        'reportes'            => ['ver']
    ],

    // Puedes seguir agregando roles aquí...
];