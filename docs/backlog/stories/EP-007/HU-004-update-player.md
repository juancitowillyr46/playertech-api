# HU-004 Actualizar Jugador

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-004 |
| Épica | EP-007 Gestión de Jugadores |
| Prioridad | Alta |
| MVP | Sí |
| Estado | Done |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir actualizar la información de un jugador existente.

---

# Historia de Usuario

Como administrador de academia

Quiero actualizar un jugador

Para mantener la información vigente.

---

# Reglas de Negocio

* El jugador debe existir.
* La edición debe respetar el contexto tenant.
* No se debe permitir cambiar de academia.
* El documento debe seguir siendo único según la regla del dominio.

---

# Criterios de Aceptación

* Dado un jugador válido, cuando actualizo su información, entonces el sistema guarda los cambios.
* Dado un intento cross-tenant, cuando intento actualizar, entonces el sistema rechaza la operación.

---

# Implementación

* Endpoint: `PUT /api/v1/academy/players/{playerId}`
* Handler: `UpdatePlayerHandler`
* Entrada: `UpdatePlayerInput`
* Respuesta: `PlayerResponse`
* Validación: contexto tenant obligatorio y documento único por academia
