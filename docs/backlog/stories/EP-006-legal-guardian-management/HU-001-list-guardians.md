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
* El listado debe soportar filtros por `documentNumber`, `documentType`, `firstName`, `lastName` y `fullName`.
* Los filtros de texto deben comportarse como case-insensitive y accent-insensitive.
* El response debe incluir `relationshipName` como etiqueta visible del parentesco.
* No debe exponer acudientes de otras academias.

---

# Criterios de Aceptación

* Dado una academia con acudientes, cuando consulto el listado, entonces el sistema muestra los acudientes paginados.
* Dado un criterio de ordenamiento válido, cuando consulto el listado, entonces el sistema aplica el alias seguro correspondiente.
* Dado un filtro por nombre, apellido o nombre completo, cuando consulto el listado, entonces el sistema devuelve sólo los acudientes que coinciden con el criterio.
* Dado un acudiente listado, cuando consulto la respuesta, entonces el sistema expone `relationshipName` con el label del parentesco.
* Dado una academia sin acudientes, cuando consulto el listado, entonces el sistema devuelve una lista vacía.

---

# Permisos Requeridos

* Guardian.Read
