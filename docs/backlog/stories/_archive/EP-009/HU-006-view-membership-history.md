# HU-006 Consultar Historial de Matrícula y Pagos

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-006 |
| Épica | EP-009 Gestión de Matrículas y Seguimiento de Pagos |
| Prioridad | Media |
| MVP | Sí |
| Estado | Done |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir consultar el historial completo de una matrícula y sus pagos asociados.

---

# Historia de Usuario

Como administrador de academia

Quiero consultar el historial de una matrícula y sus pagos

Para revisar cambios de estado, pagos realizados y trazabilidad financiera.

---

# Reglas de Negocio

* El historial debe incluir cambios de estado de matrícula.
* El historial debe incluir pagos y evidencias asociadas.
* La consulta debe restringirse a la academia actual.

---

# Criterios de Aceptación

* Dado una matrícula existente, cuando consulto su historial, entonces el sistema devuelve sus eventos y pagos asociados.
* Dado una matrícula inexistente o ajena al tenant, cuando consulto, entonces el sistema rechaza la operación.

---

# Referencia Técnica

* Implementación futura sobre `Membership` y `Payment`.
* Debe usar una query de lectura optimizada.
