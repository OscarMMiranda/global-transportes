<?php
// archivo: diagnostico_extremo.php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

echo "<h2>🔍 Diagnóstico extremo del módulo zonas</h2>";

function testArchivo($rutaRelativa) {
  $ruta = __DIR__ . '/' . $rutaRelativa;
  if (!file_exists($ruta)) {
    echo "<p>❌ Archivo no encontrado: <strong>$rutaRelativa</strong></p>";
    return false;
  }
  echo "<p>✅ Archivo encontrado: <strong>$rutaRelativa</strong></p>";
  return true;
}

// 🔗 Verificar conexión
echo "<h3>🔗 Conexión a base de datos</h3>";
require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();
if ($conn instanceof mysqli) {
  echo "<p>✅ Conexión establecida correctamente.</p>";
} else {
  echo "<p>❌ Error en conexión. Verifica getConnection().</p>";
  exit;
}

// 📁 Verificar archivos clave
echo "<h3>📁 Archivos requeridos</h3>";
$archivos = [
  'controllers/zonas_controller.php',
  'acciones/eliminar.php',
  'acciones/activar.php',
  'acciones/guardar.php',
  'componentes/encabezado.php',
  'componentes/mensajes_flash.php',
  'componentes/tabla_subzonas.php',
  'modales/modal_agregar.php',
  'modales/modal_confirmar.php',
  'modales/modal_editar.php'
];
foreach ($archivos as $archivo) {
  testArchivo($archivo);
}

// 📦 Verificar funciones y datos
echo "<h3>📦 Validación de funciones y datos</h3>";
require_once __DIR__ . '/controllers/zonas_controller.php';

try {
  $zonasPadre = listarZonasPadre();
  $distritos  = listarDistritosDisponibles();
  $subzonas   = listarDistritos();
  $registro   = obtenerDistrito(1); // prueba con ID 1

  echo "<p>✅ Funciones ejecutadas correctamente.</p>";
  echo "<ul>";
  echo "<li>Zonas padre: " . count($zonasPadre) . "</li>";
  echo "<li>Distritos disponibles: " . count($distritos) . "</li>";
  echo "<li>Subzonas: " . count($subzonas) . "</li>";
  echo "<li>Registro ejemplo: zona_id=" . $registro['zona_id'] . ", distrito_id=" . $registro['distrito_id'] . "</li>";
  echo "</ul>";
} catch (Exception $e) {
  echo "<p>❌ Error al ejecutar funciones: " . $e->getMessage() . "</p>";
}

echo "<h3>✅ Diagnóstico completado.</h3>";
?>