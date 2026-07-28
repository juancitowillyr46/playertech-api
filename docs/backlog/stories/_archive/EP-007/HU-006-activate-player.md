# HU-006 Activar Jugador

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-006 |
| Épica | EP-007 Gestión de Jugadores |
| Prioridad | Media |
| MVP | Sí |
| Estado | Done |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir reactivar un jugador previamente desactivado.

---

# Historia de Usuario

Como administrador de academia

Quiero activar un jugador

Para volver a incorporarlo a la operación cuando corresponda.

---

# Reglas de Negocio

* La activación debe ser lógica.
* No se debe crear un nuevo registro.
* El jugador debe pertenecer a la academia actual.

---

# Criterios de Aceptación

* Dado un jugador inactivo, cuando lo activo, entonces el sistema lo marca como activo.
* Dado un jugador de otra academia, cuando intento activarlo, entonces el sistema rechaza la operación.

---

# Implementación

* Endpoint: `PATCH /api/v1/academy/players/{playerId}/activate`
* Respuesta: `204 No Content`
* Validación: contexto tenant obligatorio

