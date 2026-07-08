# HU-004 Consultar Historial de Matrícula y Cargos

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-004 |
| Épica | EP-009 Gestión de Matrículas y Cargos Iniciales |
| Prioridad | Media |
| MVP | Sí |
| Estado | Done |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir consultar el historial de matrícula y los cargos generados a partir de ella.

---

# Historia de Usuario

Como administrador de academia

Quiero consultar el historial de una matrícula y sus cargos

Para revisar cambios de estado y saber qué quedó pendiente o pagado.

---

# Reglas de Negocio

* El historial debe incluir cambios de estado de matrícula.
* El historial debe incluir los cargos generados al crear la matrícula.
* La consulta debe restringirse a la academia actual.

---

# Criterios de Aceptación

* Dado una matrícula existente, cuando consulto su historial, entonces el sistema devuelve sus eventos y cargos asociados.
* Dado una matrícula inexistente o ajena al tenant, cuando consulto, entonces el sistema rechaza la operación.

