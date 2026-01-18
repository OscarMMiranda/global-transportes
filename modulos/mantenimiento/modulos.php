<?php
// archivo: /modulos/mantenimiento/modulos.php

return [
    [
        "id"              => "tipo_mercaderia",
        "nombre"          => "Tipo de Mercadería",
        "descripcion"     => "Gestionar tipos de mercadería.",
        "icono"           => "fa-boxes-stacked",
        "ruta"            => "/modulos/mantenimiento/tipo_mercaderia/index.php",
        "archivo_tarjeta" => "tarjeta_tipo_mercaderia.php"
    ],
    [
        "id"              => "tipo_vehiculo",
        "nombre"          => "Tipo de Vehículo",
        "descripcion"     => "Gestionar categorías de vehículos utilizados en operaciones.",
        "icono"           => "fa-truck",
        "ruta"            => "/modulos/mantenimiento/tipo_vehiculo/index.php",
        "archivo_tarjeta" => "tarjeta_tipo_vehiculo.php"
    ],
    [
        "id"              => "agencia_aduana",
        "nombre"          => "Agencias de Aduana",
        "descripcion"     => "Administrar agencias de aduana.",
        "icono"           => "fa-building",
        "ruta"            => "/modulos/mantenimiento/agencia_aduana/index.php",
        "archivo_tarjeta" => "tarjeta_agencia_aduana.php"
    ],
    [
        "id"              => "tipo_documento",
        "nombre"          => "Tipos de Documento",
        "descripcion"     => "Configurar tipos de documentos.",
        "icono"           => "fa-file-lines",
        "ruta"            => "/modulos/mantenimiento/tipo_documento/index.php",
        "archivo_tarjeta" => "tarjeta_tipo_documento.php"
    ],
    [
        "id"              => "zonas",
        "nombre"          => "Zonas",
        "descripcion"     => "Gestionar zonas operativas.",
        "icono"           => "fa-map-location-dot",
        "ruta"            => "/modulos/mantenimiento/zonas/index.php",
        "archivo_tarjeta" => "tarjeta_zonas.php"
    ],
    [
        "id"              => "valores_referenciales",
        "nombre"          => "Valores Referenciales",
        "descripcion"     => "Administrar valores referenciales.",
        "icono"           => "fa-scale-balanced",
        "ruta"            => "/modulos/mantenimiento/valores_referenciales/index.php",
        "archivo_tarjeta" => "tarjeta_valores_referenciales.php"
    ],
    [
        "id"              => "conductores",
        "nombre"          => "Conductores",
        "descripcion"     => "Gestionar conductores registrados.",
        "icono"           => "fa-id-card",
        "ruta"            => "/modulos/conductores/index.php",
        "archivo_tarjeta" => "tarjeta_conductores.php"
    ],
    [
        "id"              => "tipo_lugar",
        "nombre"          => "Tipo de Lugar",
        "descripcion"     => "Configurar tipos de lugar.",
        "icono"           => "fa-location-dot",
        "ruta"            => "/modulos/mantenimiento/tipo_lugares/index.php",
        "archivo_tarjeta" => "tarjeta_tipo_lugar.php"
    ],
    [
        "id"              => "entidades",
        "nombre"          => "Entidades",
        "descripcion"     => "Administrar entidades relacionadas.",
        "icono"           => "fa-building-columns",
        "ruta"            => "/modulos/mantenimiento/entidades/index.php",
        "archivo_tarjeta" => "tarjeta_entidades.php"
    ],
];