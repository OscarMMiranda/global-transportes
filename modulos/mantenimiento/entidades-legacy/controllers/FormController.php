<?php
	// archivo	: /modulos/mantenimiento/entidades/controllers/FormController.php

	require_once __DIR__ . '/../models/TerritorioModel.php';
	require_once __DIR__ . '/../models/TipoLugarModel.php';
	require_once __DIR__ . '/../models/EntidadModel.php';

	$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
	$entidad = $id > 0 ? obtenerEntidadPorId($conn, $id) : null;

	if ($id > 0) {
    	error_log("🛠 FormController: cargando edición para ID $id");
    	if (!$entidad) {
    	    error_log("⚠️ FormController: entidad no encontrada para ID $id");
    		}
		}

	// ✅ Definir IDs para marcar <option selected>
	$depId  = $entidad ? $entidad['departamento_id'] : 15;
	$provId = $entidad ? $entidad['provincia_id']    : 127;
	$distId = $entidad ? $entidad['distrito_id']     : 1251;

	$departamentos = getDepartamentos($conn);
	$provincias    = getProvincias($conn, $depId);
	$distritos     = getDistritos($conn, $provId);
	$tipos         = obtenerTiposActivos($conn);

	include __DIR__ . '/../views/FormEditarEntidad.php';