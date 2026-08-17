# HU-012 - Bloquear eliminación del acudiente principal con dependencias de negocio

| Campo | Valor |
| --- | --- |
| Épica | EP-006 Legal Guardian Management |
| Tipo | Historia de Usuario |
| Prioridad | Media |
| MVP | Sí |
| Estado | Draft |
| Actor Principal | Academic Administrator |

---

# Objetivo

Evitar que se elimine un acudiente principal cuando tenga relaciones de negocio activas o históricas que requieran una reasignación explícita antes de la desvinculación.

---

# Historia de Usuario

Como administrador de academia

Quiero que el sistema rechace la eliminación de un acudiente principal con dependencias de negocio

Para preservar la integridad financiera y operativa de la información relacionada con ese acudiente.

---

# Reglas de Negocio

* Si un acudiente principal tiene pagos, conceptos, matrículas, cargos u otras dependencias relevantes, la operación de eliminación debe ser rechazada.
* El sistema no debe realizar borrado en cascada sobre esas dependencias.
* La reasignación o inactivación debe hacerse antes de intentar eliminar el registro.
* La operación debe responder con un error de conflicto claro para informar al usuario del bloqueo.

---

# Criterios de Aceptación

* Dado un acudiente principal con dependencias activas o históricas, cuando intento eliminarlo, entonces el sistema rechaza la operación.
* Dado un acudiente con relaciones de negocio bloqueantes, cuando intento eliminarlo, entonces el sistema mantiene los registros intactos y devuelve un conflicto.

---

# Permisos Requeridos

* Guardian.Delete
