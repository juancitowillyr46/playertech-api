# HU-005 Desactivar Jugador

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-005 |
| Épica | EP-007 Gestión de Jugadores |
| Prioridad | Media |
| MVP | Sí |
| Estado | Done |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir desactivar lógicamente un jugador.

---

# Historia de Usuario

Como administrador de academia

Quiero desactivar un jugador

Para retirarlo de la operación sin perder historial.

---

# Reglas de Negocio

* La desactivación debe ser lógica.
* No se debe eliminar físicamente el registro.
* El jugador debe pertenecer a la academia actual.

---

# Criterios de Aceptación

* Dado un jugador activo, cuando lo desactivo, entonces el sistema lo marca como inactivo.
* Dado un jugador de otra academia, cuando intento desactivarlo, entonces el sistema rechaza la operación.

---

# Implementación

* Endpoint: `PATCH /api/v1/academy/players/{playerId}/inactivate`
* Endpoint complementario: `PATCH /api/v1/academy/players/{playerId}/activate`
* Respuesta: `204 No Content`
* Validación: contexto tenant obligatorio

