# HU-004 Activar o Inactivar Categoría

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-004 |
| Épica | EP-004 Gestión de Categorías |
| Prioridad | Alta |
| MVP | Sí |
| Estado | Draft |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir cambiar el estado operativo de una categoría.

---

# Historia de Usuario

Como administrador de academia

Quiero activar o inactivar una categoría

Para controlar si sigue disponible en la operación.

---

# Reglas de Negocio

* Sólo se puede activar una categoría inactiva.
* Sólo se puede inactivar una categoría activa.
* El cambio de estado debe respetar el contexto tenant.

---

# Criterios de Aceptación

* Dado una categoría activa, cuando se inactiva, entonces cambia a estado inactivo.
* Dado una categoría inactiva, cuando se activa, entonces cambia a estado activo.
* Dado un usuario fuera del tenant, cuando intenta cambiar el estado, entonces el sistema rechaza la operación.

