# HU-013 Validar Categoría de Matrícula

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-013 |
| Épica | EP-009-membership-management Gestión de Matrículas |
| Prioridad | Alta |
| MVP | Sí |
| Estado | Draft |
| Actor Principal | System |

---

# Objetivo

Validar que la categoría de inscripción exista y pertenezca al tenant antes de crear la matrícula.

---

# Historia de Usuario

Como sistema de matrícula

Quiero validar la `categoryId` enviada en el alta

Para evitar inscripciones inconsistentes o fuera del contexto de la academia.

---

# Reglas de Negocio

* La categoría debe existir.
* La categoría debe pertenecer al tenant autenticado.
* La matrícula no debe aceptar categorías de otro contexto.

---

# Criterios de Aceptación

* Dado una `categoryId` válida, cuando creo la matrícula, entonces la operación continúa.
* Dado una `categoryId` inexistente o ajena al tenant, cuando creo la matrícula, entonces el sistema rechaza la operación.

