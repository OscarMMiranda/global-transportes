<?php
// archivo: /modulos/mantenimiento/valores/acciones/guardar.php

$error = procesarValorReferencial($_POST);
if ($error === '') {
  $_SESSION['msg'] = '✅ Valor referencial guardado correctamente.';
} else {
  $_SESSION['error'] = $error;
}
header('Location: index.php');
exit;