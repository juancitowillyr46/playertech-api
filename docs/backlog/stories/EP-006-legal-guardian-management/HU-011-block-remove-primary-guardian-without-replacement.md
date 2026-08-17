# HU-011 - Bloquear desvinculación del acudiente principal sin candidato de reemplazo

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

Evitar que se elimine un acudiente principal cuando la operación dejaría al jugador sin un candidato válido para continuar como principal operativo.

---

# Historia de Usuario

Como administrador de academia

Quiero que el sistema valide la desvinculación de un acudiente principal cuando no exista un candidato de reemplazo

Para evitar inconsistencias operativas en la relación jugador-acudiente y mantener la continuidad de los datos que dependen del principal.

---

# Reglas de Negocio

* Si un acudiente es principal y la operación de desvinculación deja al jugador sin un candidato elegible para promoción, la operación debe ser rechazada.
* Si el acudiente principal tiene dependencias de negocio activas o históricas, el sistema debe rechazar la eliminación hasta que exista una reasignación explícita.
* El sistema no debe intentar resolver esta condición con borrado en cascada.
* La operación debe responder con un error de conflicto claro para que el frontend pueda informar el motivo.

---

# Criterios de Aceptación

* Dado un jugador cuyo acudiente principal no tiene reemplazo posible, cuando intento desvincularlo, entonces el sistema rechaza la operación.
* Dado un acudiente principal con dependencias de negocio bloqueantes, cuando intento eliminarlo, entonces el sistema devuelve un conflicto y mantiene la integridad del vínculo.

---

# Permisos Requeridos

* Guardian.Delete
