# HU-010 Persistir Categoría de Inscripción

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-010 |
| Épica | EP-009-membership-management Gestión de Matrículas |
| Prioridad | Alta |
| MVP | Sí |
| Estado | Draft |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir guardar la categoría con la que se inscribe el jugador en la matrícula.

---

# Historia de Usuario

Como sistema de matrícula

Quiero persistir la categoría de inscripción

Para conservar trazabilidad histórica aunque la categoría actual cambie después.

---

# Reglas de Negocio

* La categoría de inscripción debe guardarse en la matrícula.
* La categoría guardada debe corresponder a la categoría enviada en el alta.
* La matrícula no debe depender de la categoría actual del jugador.

---

# Criterios de Aceptación

* Dado una matrícula creada, cuando consulto el registro, entonces el sistema conserva la categoría de inscripción.
* Dado un cambio posterior en la categoría del jugador, cuando consulto la matrícula histórica, entonces la categoría de inscripción original permanece intacta.

