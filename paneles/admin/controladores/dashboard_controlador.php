<?php
// archivo: /paneles/admin/controladores/dashboard_controlador.php

session_start();

// Solo admin puede entrar a este panel
if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol_nombre']) || $_SESSION['rol_nombre'] !== 'admin') {
    header("Location: /login.php");
    exit();
}

// Aquí podrías cargar datos específicos del dashboard si algún día lo necesitas
// Ejemplo: métricas, contadores, etc.

// Cargar la vista del dashboard
require_once __DIR__ . '/../vistas/dashboard.php';