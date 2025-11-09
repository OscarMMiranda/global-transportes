<?php
// archivo: /modulos/mantenimiento/zonas/helpers/datos_vista.php

// 🧭 Modo de vista: activas o eliminadas
$verEliminadas = isset($_GET['inactivas']) && $_GET['inactivas'] === '1';

// 📦 Datos principales
$subzonas      = listarRutas($verEliminadas);
$zonasPadre    = listarZonasPadre();
$distritos     = listarDistritosDisponibles();
$departamentos = listarDepartamentos();
$provincias    = listarProvincias();

// 🧩 Precarga extendida si se está editando
$registro = isset($_GET['id']) ? obtenerRutaExtendida($_GET['id']) : array(
  'id'         => 0,
  'zona_id'    => 0,
  'origen_id'  => 0,
  'destino_id' => 0,
  'kilometros' => ''
);

// 🛡️ Blindaje de campos faltantes
foreach (['id','zona_id','origen_id','destino_id','kilometros'] as $k) {
  if (!isset($registro[$k])) {
    $registro[$k] = ($k === 'kilometros') ? '' : 0;
  }
}

// 🧯 Mensajes de error
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['error']);