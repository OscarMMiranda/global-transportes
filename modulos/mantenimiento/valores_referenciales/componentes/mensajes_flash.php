<?php
	// archivo: componentes/mensajes_flash.php

	// Mostrar mensaje de éxito
	if (isset($_SESSION['msg'])) {
    	echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['msg']) . '</div>';
    	unset($_SESSION['msg']);
		}

	// Mostrar mensaje de error
	if (isset($_SESSION['error'])) {
    	echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['error']) . '</div>';
    	unset($_SESSION['error']);
		}
?>