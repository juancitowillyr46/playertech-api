# HU-005 Listar Acudientes de un Jugador

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-005 |
| Épica | EP-008 Gestión de Relaciones Jugador-Acudiente |
| Prioridad | Alta |
| MVP | Sí |
| Estado | Draft |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir consultar la lista de acudientes asociados a un jugador.

---

# Historia de Usuario

Como administrador de academia

Quiero ver los acudientes relacionados a un jugador

Para gestionar responsables, principal y relaciones históricas desde el detalle del jugador.

---

# Reglas de Negocio

* La consulta debe respetar el contexto de academia.
* Debe mostrar el acudiente principal y los secundarios.
* No debe incluir relaciones eliminadas lógicamente.

---

# Criterios de Aceptación

* Dado un jugador con acudientes asociados, cuando consulto la sección, entonces el sistema muestra la lista completa.
* Dado un jugador sin acudientes, cuando consulto la sección, entonces el sistema devuelve una lista vacía.

---

# Permisos Requeridos

* PlayerGuardian.Read
