# HU-002 Registrar Pago

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-002 |
| Épica | EP-012 Gestión de Pagos |
| Prioridad | Alta |
| MVP | Sí |
| Estado | Draft |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir registrar el pago de un cargo pendiente.

---

# Historia de Usuario

Como administrador de academia

Quiero registrar un pago

Para cambiar un cargo de `PENDIENTE` a `PAGADO`.

---

# Reglas de Negocio

* El cargo debe existir y estar pendiente.
* El pago debe registrar el medio utilizado.
* El pago debe quedar asociado a la matrícula y al acudiente principal.

---

# Criterios de Aceptación

* Dado un cargo pendiente, cuando registro el pago, entonces su estado cambia a `PAGADO`.

