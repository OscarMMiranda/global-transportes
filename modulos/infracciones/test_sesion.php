<?php
// archivo: test_sesion.php

session_start();

echo "<h2>Prueba de Sesión</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

?>
