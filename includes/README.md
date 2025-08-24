includes/
│
├── config.php
├── conexion.php
│
├── funciones/
│   ├── generales.php         # sanitize, flash, redirigir
│   ├── sesion.php            # validarSesionUsuario, validarSesionAdmin
│   ├── usuarios.php          # crear, editar, validar usuarios
│   ├── clientes.php          # funciones específicas de clientes
│   ├── seguridad.php         # validador.php + helpers de acceso
│
├── vistas/
│   ├── header.php
│   ├── footer.php
│   ├── navbar.php
│   ├── header_conductor.php
│   ├── footer_conductor.php
│   └── ... otros headers/footers
│
├── helpers.php              # si contiene funciones genéricas
├── auth.php                 # lógica de login y roles
├── validador.php            # validación de sesión (migrar a funciones/sesion.php)
├── crear_usuarios.php       # migrar a funciones/usuarios.php
