# HU-003 Ver Detalle de Jugador

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-003 |
| Épica | EP-007 Gestión de Jugadores |
| Prioridad | Media |
| MVP | Sí |
| Estado | Done |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir consultar el detalle de un jugador existente.

---

# Historia de Usuario

Como administrador de academia

Quiero ver el detalle de un jugador

Para revisar su información sin editarla.

---

# Reglas de Negocio

* El jugador debe existir.
* El acceso debe respetar el contexto tenant.
* El detalle no debe exponer datos de otra academia.

---

# Criterios de Aceptación

* Dado un jugador existente, cuando consulto su detalle, entonces el sistema devuelve sus datos.
* Dado un jugador de otra academia, cuando intento consultarlo, entonces el sistema lo rechaza.

---

# Implementación

* Endpoint: `GET /api/v1/academy/players/{playerId}`
* Respuesta: `PlayerResponse`
* Validación: contexto tenant obligatorio mediante `TenantContext`

