<?php
// archivo: /modulos/mantenimiento/tipo_vehiculo/ajax/form_edit_loader.php

require_once __DIR__ . '/../modelo/TipoVehiculoModel.php';
require_once __DIR__ . '/../controller.php';
require_once __DIR__ . '/../../../../includes/config.php';

$conn = getConnection();
$ctrl = new TipoVehiculoController($conn);

// Validar ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$vehiculo = $ctrl->buscarPorId($id); // Método que retorna array o null
$categorias = $ctrl->listarCategorias(); // Método que retorna array

$conn->close();

// Incluir vista con datos
include __DIR__ . '/../vistas/form_edit.php';