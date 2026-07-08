# HU-006 Retirar Matrícula

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-006 |
| Épica | EP-009 Gestión de Matrículas y Cargos Iniciales |
| Prioridad | Alta |
| MVP | Sí |
| Estado | Done |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir retirar una matrícula para cerrar definitivamente la vinculación administrativa del jugador con la academia.

---

# Historia de Usuario

Como administrador de academia

Quiero retirar una matrícula

Para finalizar la vinculación del jugador con la academia y dejar su historial completo.

---

# Reglas de Negocio

* Solo se puede retirar una matrícula vigente o suspendida.
* El retiro no debe borrar el historial de cargos.
* El retiro debe conservar trazabilidad histórica.

---

# Criterios de Aceptación

* Dado una matrícula vigente o suspendida, cuando la retiro, entonces el sistema la marca como finalizada correctamente.
* Dado una matrícula inexistente o ya retirada, cuando intento retirarla, entonces el sistema rechaza la operación.

