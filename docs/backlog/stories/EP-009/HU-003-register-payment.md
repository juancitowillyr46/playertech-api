# HU-003 Registrar Pago sobre Matrícula

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-003 |
| Épica | EP-009 Gestión de Matrículas y Seguimiento de Pagos |
| Prioridad | Alta |
| MVP | Sí |
| Estado | Draft |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir registrar un pago asociado a la matrícula de un jugador para controlar el abono realizado por su acudiente principal.

---

# Historia de Usuario

Como administrador de academia

Quiero registrar un pago sobre una matrícula

Para llevar el control de la deuda y los abonos realizados por el acudiente principal.

---

# Reglas de Negocio

* Todo pago debe pertenecer a una matrícula válida.
* Todo pago debe tener un concepto.
* Todo pago debe quedar asociado al acudiente principal responsable.
* El pago debe registrarse dentro de la academia actual.

---

# Criterios de Aceptación

* Dado una matrícula válida, cuando registro un pago, entonces el sistema lo guarda correctamente.
* Dado un concepto ausente o inválido, cuando registro un pago, entonces el sistema rechaza la operación.
* Dado una matrícula inexistente o ajena al tenant, cuando registro un pago, entonces el sistema rechaza la operación.

---

# Referencia Técnica

* Implementación futura sobre `Payment`.
* Debe conservar trazabilidad y auditoría.
