

modulos/vehiculos/
├── acciones/ 
│   ├── actualizar.php
│   ├── eliminar.php
│   ├── guardar.php
│   └── restaurar.php     	← lógica pura: store, update, delete, restore
├── includes/ 
│   └── funciones.php 
├── layout/
│   ├── header_vehiculos.php
│   └── footer_vehiculos.php
├── modelo.php            	← todas las funciones de acceso a datos
├── controlador.php       	← único punto de entrada
├── vistas/
│   ├── listado.php
│   ├── formulario.php
│   ├── formulario_editar.php
│   ├── formulario_eliminar.php
│   ├── tabla_inactivos.php
│   └── tabla_activos.php
├── js/
│   └── vehiculos.js        ← comportamiento interactivo (DataTables, modals)
└── index.php       		← 