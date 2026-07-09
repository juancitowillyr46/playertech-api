# HU-002 Registrar Pago

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-002 |
| Épica | EP-012 Gestión de Cargos y Pagos |
| Prioridad | Alta |
| MVP | Sí |
| Estado | Done |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir registrar el pago de uno o varios cargos pendientes.

---

# Historia de Usuario

Como administrador de academia

Quiero registrar un pago

Para conciliar la deuda del acudiente principal y marcar cargos como `PAGADO`.

---

# Reglas de Negocio

* El cargo debe existir y estar pendiente.
* El pago debe registrar el medio utilizado.
* El pago debe quedar asociado al acudiente principal.
* El endpoint HTTP y la colección Postman ya existen.

---

# Criterios de Aceptación

* Dado un cargo pendiente, cuando registro el pago, entonces su estado cambia a `PAGADO`.
