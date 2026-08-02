# HU-001 Listar Acudientes

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-001 |
| Épica | EP-006 Legal Guardian Management |
| Prioridad | Alta |
| MVP | Sí |
| Estado | Draft |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir consultar el listado de acudientes registrados en la academia actual.

---

# Historia de Usuario

Como administrador de academia

Quiero listar los acudientes

Para identificar rápidamente a los responsables legales disponibles y reutilizarlos en la operación diaria.

---

# Reglas de Negocio

* El listado debe respetar el contexto de la academia autenticada.
* El listado debe ser paginado.
* El listado debe soportar ordenamiento por `created_at`, `document_number`, `first_name`, `last_name` y `status`.
* El listado debe soportar filtros por `firstName` y `lastName`.
* No debe exponer acudientes de otras academias.

---

# Criterios de Aceptación

* Dado una academia con acudientes, cuando consulto el listado, entonces el sistema muestra los acudientes paginados.
* Dado un criterio de ordenamiento válido, cuando consulto el listado, entonces el sistema aplica el alias seguro correspondiente.
* Dado un filtro por nombre o apellido, cuando consulto el listado, entonces el sistema devuelve sólo los acudientes que coinciden con el criterio.
* Dado una academia sin acudientes, cuando consulto el listado, entonces el sistema devuelve una lista vacía.

---

# Permisos Requeridos

* Guardian.Read
