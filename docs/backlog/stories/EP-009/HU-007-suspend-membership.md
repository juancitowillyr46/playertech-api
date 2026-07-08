# HU-007 Suspender Matrícula

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-007 |
| Épica | EP-009 Gestión de Matrículas y Seguimiento de Pagos |
| Prioridad | Media |
| MVP | Sí |
| Estado | Done |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir suspender una matrícula para pausar temporalmente la vinculación administrativa del jugador.

---

# Historia de Usuario

Como administrador de academia

Quiero suspender una matrícula

Para pausar temporalmente la vinculación del jugador sin perder su historial.

---

# Reglas de Negocio

* Solo se puede suspender una matrícula vigente.
* La suspensión no debe eliminar el historial de pagos.
* La matrícula suspendida sigue existiendo como referencia histórica.

---

# Criterios de Aceptación

* Dado una matrícula vigente, cuando la suspendo, entonces el sistema cambia su estado correctamente.
* Dado una matrícula ya suspendida o inexistente, cuando intento suspenderla, entonces el sistema rechaza la operación.

---

# Referencia Técnica

* Implementación futura sobre `Membership`.
* Debe conservar auditoría y trazabilidad.
