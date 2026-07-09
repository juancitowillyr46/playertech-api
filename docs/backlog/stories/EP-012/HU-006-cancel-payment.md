# HU-006 Anular Pago

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-006 |
| Épica | EP-012 Gestión de Cargos y Pagos |
| Prioridad | Baja |
| MVP | Sí |
| Estado | Done |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir anular un pago cuando corresponda.

---

# Historia de Usuario

Como administrador de academia

Quiero anular un pago

Para corregir errores o reversar una transacción mal registrada.

---

# Reglas de Negocio

* Solo se puede anular un pago válido.
* La anulación debe conservar trazabilidad.
* El endpoint HTTP y la colección Postman ya existen.

---

# Criterios de Aceptación

* Dado un pago válido, cuando lo anulo, entonces el sistema lo marca como anulado correctamente.
