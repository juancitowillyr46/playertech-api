# HU-002 Cambiar acudiente principal

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-002 |
| Épica | EP-008 Gestión de Relaciones Jugador-Acudiente |
| Prioridad | Alta |
| MVP | Sí |
| Estado | Done |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir cambiar el acudiente principal de un jugador sin perder las demás asociaciones existentes.

---

# Historia de Usuario

Como administrador de academia

Quiero cambiar el acudiente principal de un jugador

Para mantener actualizado el responsable principal del menor según el contexto familiar o administrativo.

---

# Reglas de Negocio

* Todo jugador activo debe tener exactamente un acudiente principal.
* El cambio de principal debe ser atómico.
* Un jugador puede tener varios acudientes asociados, pero sólo uno principal a la vez.
* El nuevo acudiente principal debe estar previamente asociado al jugador.
* La relación debe pertenecer a la academia actual.

---

# Criterios de Aceptación

* Dado un jugador con múltiples acudientes asociados, cuando cambio el principal, entonces el sistema deja uno solo como principal.
* Dado un acudiente no asociado al jugador, cuando intento marcarlo como principal, entonces el sistema rechaza la operación.
* Dado un jugador sin acudientes asociados, cuando intento cambiar el principal, entonces el sistema rechaza la operación.

---

# Referencia Técnica

* Se recomienda resolver la operación mediante un handler CQRS específico.
* La validación de unicidad del principal debe quedar en la capa de aplicación o dominio.
* Debe responder con DTO JSON estándar.
