# HU-005 Consultar Saldo o Deuda Pendiente

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-005 |
| Épica | EP-009 Gestión de Matrículas y Seguimiento de Pagos |
| Prioridad | Alta |
| MVP | Sí |
| Estado | Draft |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir consultar cuánto debe una matrícula para identificar rápidamente la deuda del acudiente principal.

---

# Historia de Usuario

Como administrador de academia

Quiero consultar el saldo pendiente de una matrícula

Para saber cuánto debe el acudiente principal y tomar decisiones de seguimiento.

---

# Reglas de Negocio

* El saldo debe calcularse sobre la matrícula activa y sus pagos asociados.
* La consulta debe restringirse a la academia actual.
* El resultado debe ser consistente con el historial de pagos.

---

# Criterios de Aceptación

* Dado una matrícula con pagos parciales, cuando consulto el saldo, entonces el sistema muestra el monto pendiente.
* Dado una matrícula sin pagos, cuando consulto el saldo, entonces el sistema muestra el total adeudado.

---

# Referencia Técnica

* Implementación futura sobre `Payment`.
* Puede resolverse mediante query dedicada y DTO de lectura.
