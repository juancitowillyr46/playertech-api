# HU-005 Ver Detalle de Categoría

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-005 |
| Épica | EP-004 Gestión de Categorías |
| Prioridad | Media |
| MVP | Sí |
| Estado | Draft |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir consultar el detalle de una categoría existente.

---

# Historia de Usuario

Como administrador de academia

Quiero ver el detalle de una categoría

Para revisar su configuración sin modificarla.

---

# Reglas de Negocio

* La categoría debe existir.
* El acceso debe respetar el contexto tenant.
* El detalle no debe exponer datos fuera de la academia actual.

---

# Criterios de Aceptación

* Dado una categoría existente, cuando se consulta su detalle, entonces el sistema devuelve la información correcta.
* Dado un usuario sin contexto tenant, cuando intenta acceder, entonces el sistema rechaza la operación.

