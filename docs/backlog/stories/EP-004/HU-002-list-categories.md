# HU-002 Listar Categorías

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-002 |
| Épica | EP-004 Gestión de Categorías |
| Prioridad | Media |
| MVP | Sí |
| Estado | Draft |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir listar las categorías activas de la academia actual.

---

# Historia de Usuario

Como administrador de academia

Quiero listar las categorías registradas

Para organizar mejor la operación deportiva.

---

# Reglas de Negocio

* Una categoría pertenece a una academia.
* El listado debe respetar el contexto tenant.
* El listado no debe exponer datos fuera de la academia actual.

---

# Criterios de Aceptación

* Dado un tenant autenticado, cuando consulta categorías, entonces sólo ve las de su academia.
* Dado un usuario sin contexto tenant, cuando intenta acceder, entonces el sistema rechaza la operación.

