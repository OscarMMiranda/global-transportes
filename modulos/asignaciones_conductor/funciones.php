<?php
	// archivo:		/asignaciones_conductor/funciones.php

	// Si la sesión no está activa, la inicia
	if (session_status() === PHP_SESSION_NONE) {
    	session_start();
		}

	/**
	 * Valida que exista un usuario autenticado.
	 * Si no, redirige al login.
	 */
	function validarSesionUsuario() {
    if (
        empty($_SESSION['usuario']) ||
        empty($_SESSION['id'])
    	) 	{
        	setFlash('danger', 'Usuario no autenticado.');
			header("Location: http://www.globaltransportes.com/login.php");

        	// header('Location: ' . BASE_URL . 'login.php');
        	exit;
    		}
		}

	/**
	 * Valida que el usuario autenticado tenga rol 'admin'.
	 * Antes verifica que esté autenticado.
	 */
	function validarSesionAdmin() {
    	// Primero valida que esté autenticado
    	validarSesionUsuario();

    	if (
    	    empty($_SESSION['rol_nombre']) ||
    	    $_SESSION['rol_nombre'] !== 'admin'
    	) 	{
        	setFlash('danger', 'Requiere permisos de administrador.');
        	header('Location: ' . BASE_URL . 'login.php');
        	exit;
    		}
		}


	/**
	 * Sanitiza datos para salida en HTML.
	 */
		function sanitize($data) {
	    	return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
			}


	/**
	 * Mensajes flash (tipo: success, danger, info, warning).
	 * Se guardan en $_SESSION['flash'].
	 */
	function setFlash($type, $message) {
		$_SESSION['flash'] = ['type' => $type, 'msg' => $message];
		}

	/**
	 * Recupera y elimina el mensaje flash de la sesión.
	 */
	function getFlash() {
		if (!empty($_SESSION['flash'])) {
	    	$flash = $_SESSION['flash'];
	    	unset($_SESSION['flash']);
	    	return $flash;
			}
		return null;
		}
