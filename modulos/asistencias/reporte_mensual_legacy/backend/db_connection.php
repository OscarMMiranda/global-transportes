<?php
	//	archivo :	/modulos/asistencias/reporte_mensual/backend/db_connection.php


function rm_get_connection()
{
    $host = 'localhost';
    $user = 'USUARIO_DB';
    $pass = 'PASSWORD_DB';
    $db   = 'NOMBRE_DB';

    $cn = mysqli_connect($host, $user, $pass, $db);

    if (!$cn) {
        die('Error de conexión: ' . mysqli_connect_error());
    }

    mysqli_set_charset($cn, 'utf8');
    return $cn;
}
