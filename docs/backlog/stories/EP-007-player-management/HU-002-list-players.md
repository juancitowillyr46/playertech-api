# HU-002 Listar Jugadores

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-002 |
| Épica | EP-007 Gestión de Jugadores |
| Prioridad | Alta |
| MVP | Sí |
| Estado | Done |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir listar los jugadores de la academia actual.

---

# Historia de Usuario

Como administrador de academia

Quiero listar los jugadores

Para revisar rápidamente quiénes forman parte de la operación.

---

# Reglas de Negocio

* El listado debe respetar el contexto tenant.
* No se deben exponer jugadores de otra academia.
* El listado debe ser paginado desde el contrato de API.
* El listado puede filtrarse por `gender`, `categoryId`, `documentNumber`, `documentType`, `firstName`, `lastName`, `fullName`, `createdAtFrom`, `createdAtTo`, `birthDateFrom` y `birthDateTo`.
* Los filtros de texto deben comportarse como case-insensitive y accent-insensitive.
* `age` es un campo derivado de salida; el filtro canónico por rango de edad debe resolverse sobre `birthDateFrom` y `birthDateTo`.

---

# Criterios de Aceptación

* Dado un tenant autenticado, cuando consulta el listado, entonces sólo ve jugadores de su academia.
* Dado un usuario sin contexto tenant, cuando consulta, entonces el sistema rechaza la operación.

---

# Referencia Técnica

* Implementado con `GET /api/v1/academy/players`.
* Usa `PlayerListItemResponse` como DTO de salida.
* El listado se resuelve por `academy_id` desde `TenantContext`.
* El contrato de filtros debe mantenerse explícito en query params simples, no en un objeto anidado.
