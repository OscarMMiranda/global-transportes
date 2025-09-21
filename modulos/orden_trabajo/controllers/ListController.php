<?php
// archivo: /modulos/orden_trabajo/controllers/ListController.php

function cargarListado($conn) {
    require_once __DIR__ . '/../models/OrdenModel.php';

    $model = new OrdenModel($conn);

    $ordenesActivas    = $model->obtenerActivas();
    $ordenesAnuladas   = $model->obtenerPorEstado(7);
    $ordenesEliminadas = $model->obtenerPorEstado(8);

    require_once __DIR__ . '/../views/list.php';
}