# 📦 Módulo de Asignaciones – Conductor, Tracto y Carreta

Este módulo forma parte del sistema ERP de transporte y permite gestionar de forma auditable las asignaciones entre conductores, vehículos tracto, y carretas o plataformas. Cada asignación está ligada a un período de tiempo y conserva historial completo para trazabilidad.

---

## 📁 Estructura de archivos

| Archivo                   | Descripción                                                                 |
|---------------------------|-----------------------------------------------------------------------------|
| `asignaciones.php`        | Vista principal: incluye la tabla de historial, botón para nueva asignación |
| `form_asignacion.php`     | Modal Bootstrap con formulario dinámico para nueva asignación               |
| `procesar_asignacion.php` | Backend PHP que valida y registra la asignación                             |
| `finalizar_asignacion.php`| Marca una asignación como finalizada registrando fecha de término           |
| `listar_asignaciones.php` | Genera JSON para tabla DataTable filtrable                                  |
| `api.php`                 | Enrutador central de peticiones AJAX mediante `method=`                     |
| `asignaciones.js`         | Script JS: controla modales, carga dinámica y envío por AJAX                |
| `README.md`               | Este archivo: documentación técnica del módulo                              |

---

## 🧠 Lógica de negocio

- Cada asignación vincula: **1 conductor + 1 tracto + 1 carreta/plataforma**
- El período se define por `fecha_inicio` y `fecha_fin` (opcional mientras esté activa)
- Un conductor puede mantener el mismo tracto pero cambiar de carreta
- Solo se permite **una asignación activa** por conductor y por tracto al mismo tiempo
- Las asignaciones finalizadas conservan historial para auditoría y consultas

---

## 📅 Esquema de base de datos

```sql
CREATE TABLE asignaciones (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_conductor INT NOT NULL,
  id_vehiculo_tracto INT NOT NULL,
  id_vehiculo_carreta INT NOT NULL,
  fecha_inicio DATE NOT NULL,
  fecha_fin DATE DEFAULT NULL,
  estado ENUM('activo', 'finalizado') DEFAULT 'activo',
  observaciones TEXT,

  FOREIGN KEY (id_conductor) REFERENCES conductores(id),
  FOREIGN KEY (id_vehiculo_tracto) REFERENCES vehiculos(id),
  FOREIGN KEY (id_vehiculo_carreta) REFERENCES vehiculos(id)
);

---

**Autor:** Oscar  
**Última edición:** [Fecha automática o manual]  
