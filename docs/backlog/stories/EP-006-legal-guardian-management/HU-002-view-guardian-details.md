# HU-002 Ver Detalle de Acudiente

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-002 |
| Épica | EP-006 Legal Guardian Management |
| Prioridad | Alta |
| MVP | Sí |
| Estado | Draft |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir consultar el detalle completo de un acudiente.

---

# Historia de Usuario

Como administrador de academia

Quiero ver el detalle de un acudiente

Para revisar su información completa antes de asociarlo o actualizarlo.

---

# Reglas de Negocio

* El acudiente debe pertenecer a la academia actual.
* El detalle debe mostrar la información registrada y su estado.
* El detalle debe exponer `phoneSingle` con el teléfono local sin prefijo internacional cuando aplique.

---

# Criterios de Aceptación

* Dado un acudiente existente, cuando consulto su detalle, entonces el sistema muestra la información completa.
* Dado un acudiente inexistente, cuando consulto el detalle, entonces el sistema informa el error.
* Dado un acudiente con teléfono internacional, cuando consulto el detalle, entonces el sistema expone también `phoneSingle` sin prefijo internacional cuando exista.

---

# Permisos Requeridos

* Guardian.Read
