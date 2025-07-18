<?php
    error_reporting(E_ALL); 
    ini_set('display_errors',1);


    require_once $_SERVER['DOCUMENT_ROOT'].'/includes/config.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/modulos/clientes/models/Cliente.php';
    
    Cliente::init($conn);

    if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
		header('Location:?action=trash&msg=error'); exit;
		}

	$id = (int)$_GET['id'];
	$ok = Cliente::restore($id);
	$msg = $ok ? 'restored' : 'error';
	header('Location:?action=trash&msg='.$msg);
	exit;
