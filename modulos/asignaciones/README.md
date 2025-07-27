# Módulo Asignaciones

Este README.md describe la estructura, instalación y uso del módulo **Asignaciones** siguiendo un patrón modular, limpio y profesional.

---

## Descripción

El módulo Asignaciones permite gestionar y auditar el proceso de asignación de conductores, tractos y carretas. Cada acción queda registrada en un historial de auditoría, garantizando trazabilidad y seguridad.

---

## Características

- Creación, edición y eliminación de asignaciones  
- Validación de disponibilidad de recursos antes de asignar  
- Listado de asignaciones activas e históricas  
- Registro de cada acción en historial de actividades  
- Soporte para estados: activa, terminada y cancelada  

---

## Requisitos

- PHP 5.6 o superior con extensiones `mysqli` y `openssl`  
- Composer con soporte PSR-4 autoloading  
- Base de datos MySQL con tablas:
  - `asignaciones`
  - `usuarios`
  - `roles`
  - `tractos`
  - `carretas`
  - `historial_actividades`

---

## Estructura de carpetas





---

## Instalación

1. Copiar `modulos/asignaciones` al directorio raíz de tu proyecto.  

2. Agregar entrada PSR-4 en `composer.json`:

   ```json
   {
     "autoload": {
       "psr-4": {
         "Modules\\Asignaciones\\": "modulos/asignaciones/src/"
       }
     }
   }






modulos/asignaciones/
├── config/                   # Configuración del módulo (estados y mensajes)
│   └── module.php
├── routes/                   # Definición de rutas y endpoints
│   └── routes.php
├── src/
│   ├── Controller/           # Gestión de solicitudes HTTP
│   │   └── AsignacionesController.php
│   ├── Service/              # Lógica de negocio
│   │   └── AsignacionService.php
│   ├── Repository/           # Acceso a datos
│   │   ├── AsignacionRepositoryInterface.php
│   │   └── MySQLAsignacionRepository.php
│   ├── Model/                # Entidades del dominio
│   │   └── Asignacion.php
│   ├── DTO/                  # Objetos de Transferencia de Datos
│   │   └── AsignacionDTO.php
│   └── Validator/            # Reglas de validación
│       └── AsignacionValidator.php
├── views/                    # Plantillas de interfaz (MVC)
│   └── asignaciones/
│       ├── index.php
│       ├── create.php
│       ├── edit.php
│       └── _form.php
├── public/
│   └── js/
│       └── asignaciones.js   # Scripts de interacción y AJAX
└── tests/                    # Pruebas unitarias y de integración
    ├── Controller/
    ├── Service/
    └── Repository/


