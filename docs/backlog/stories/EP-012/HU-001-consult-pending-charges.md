# HU-001 Consultar Cargos Pendientes

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-001 |
| Épica | EP-012 Gestión de Cargos y Pagos |
| Prioridad | Alta |
| MVP | Sí |
| Estado | Done |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir ver los cargos pendientes de una matrícula o de su acudiente principal.

---

# Historia de Usuario

Como administrador de academia

Quiero consultar los cargos pendientes

Para identificar rápidamente qué matrícula o acudiente aún tiene deuda.

---

# Reglas de Negocio

* La consulta debe mostrar cargos en estado `PENDIENTE`.
* Los cargos deben pertenecer a la academia actual.
* El endpoint HTTP y la colección Postman ya existen.

---

# Criterios de Aceptación

* Dado un jugador con cargos pendientes, cuando consulto, entonces el sistema los muestra correctamente.
