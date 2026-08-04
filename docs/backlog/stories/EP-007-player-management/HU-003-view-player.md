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
* La respuesta del detalle debe exponer los labels visibles enriquecidos: `categoryName`, `documentTypeName`, `nationalityName`, `genderName` y `dominantFootName`.
* La respuesta del detalle debe exponer `legalGuardianMain` y `teamMain` como objetos resumidos o `null`.

---

# Criterios de Aceptación

* Dado un jugador existente, cuando consulto su detalle, entonces el sistema devuelve sus datos.
* Dado un jugador de otra academia, cuando intento consultarlo, entonces el sistema lo rechaza.
* Dado un jugador con valores de catálogo asociados, cuando consulto su detalle, entonces el sistema devuelve los nombres visibles correspondientes.

---

# Implementación

* Endpoint: `GET /api/v1/academy/players/{playerId}`
* Respuesta: `PlayerResponse`
* Validación: contexto tenant obligatorio mediante `TenantContext`
