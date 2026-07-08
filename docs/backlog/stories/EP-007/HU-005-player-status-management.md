# HU-005 Gestión de Estado del Jugador

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

Permitir desactivar y reactivar lógicamente un jugador sin perder historial.

---

# Historia de Usuario

Como administrador de academia

Quiero gestionar el estado de un jugador

Para retirarlo o reincorporarlo a la operación según corresponda.

---

# Reglas de Negocio

* La gestión de estado debe ser lógica.
* No se debe eliminar físicamente el registro.
* El jugador debe pertenecer a la academia actual.
* Desactivar cambia el estado a inactivo.
* Activar cambia el estado a activo.

---

# Criterios de Aceptación

* Dado un jugador activo, cuando lo desactivo, entonces el sistema lo marca como inactivo.
* Dado un jugador inactivo, cuando lo activo, entonces el sistema lo marca como activo.
* Dado un jugador de otra academia, cuando intento cambiar su estado, entonces el sistema rechaza la operación.

---

# Implementación

* Endpoint: `PATCH /api/v1/academy/players/{playerId}/inactivate`
* Endpoint complementario: `PATCH /api/v1/academy/players/{playerId}/activate`
* Respuesta: `204 No Content`
* Validación: contexto tenant obligatorio

