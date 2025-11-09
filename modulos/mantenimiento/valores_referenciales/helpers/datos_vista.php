<?php
// archivo: /modulos/mantenimiento/valores/helpers/datos_vista.php

$valores        = listarValoresReferenciales();
$valores_pivot  = listarValoresPivot();
$zonas          = listarZonasPadre();
$tipos          = listarTiposMercaderia();

$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['error']);