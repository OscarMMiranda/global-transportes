Descripción de cada elemento


Archivo	Responsabilidad
index.php	        		Punto de entrada: incluye controlador.php.
controlador.php	    		Orquesta el flujo: valida sesión, obtiene datos del modelo y carga la vista.
modelo.php	        		Funciones de acceso a datos: obtiene IDs de estado, listados y finalización.
funciones.php	    		Helpers reutilizables: validaciones, sanitización y formateo de datos.
vista_listado.php			Muestra la tabla de asignaciones activas e historial.
vista_modal.php				Modal Bootstrap de confirmación para finalizar una asignación.
finalizar_asignacion.php	Endpoint que llama a finalizarAsignacion() y redirige con trazabilidad.
js/asignaciones.js			Scripts de frontend: inicialización de DataTables, eventos del modal, etc.	
css/asignaciones.css		Estilos propios del módulo (se añade al <head> en la vista).
error_log.txt				Archivo donde PHP vuelca errores para facilitar el debug en entorno DEV.





