# HU-002 Generar Cargos Iniciales

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-002 |
| Épica | EP-009-membership-management Gestión de Matrículas |
| Prioridad | Alta |
| MVP | Sí |
| Estado | Done |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir que al crear una matrícula se generen automáticamente los cargos de matrícula y primera mensualidad.

---

# Historia de Usuario

Como administrador de academia

Quiero que el sistema genere los cargos iniciales al crear una matrícula

Para dejar pendiente desde el inicio lo que debe pagar el acudiente principal.

---

# Reglas de Negocio

* Los cargos iniciales se documentan como referencia financiera y se resuelven en el bloque financiero.
* La matrícula conserva trazabilidad hacia esos cargos, pero no los materializa dentro del core administrativo.

---

# Criterios de Aceptación

* Dado una matrícula creada, cuando el sistema termina la operación, entonces existen dos cargos pendientes generados.
* Dado una matrícula nueva, cuando consulto sus cargos iniciales, entonces el sistema muestra matrícula y primera mensualidad en pendiente.
