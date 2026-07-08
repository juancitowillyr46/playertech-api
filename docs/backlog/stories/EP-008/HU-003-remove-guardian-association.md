# HU-003 Eliminar asociación jugador-acudiente

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-003 |
| Épica | EP-008 Gestión de Relaciones Jugador-Acudiente |
| Prioridad | Media |
| MVP | Sí |
| Estado | Done |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir eliminar la relación entre un jugador y un acudiente cuando ya no exista vigencia administrativa o legal.

---

# Historia de Usuario

Como administrador de academia

Quiero eliminar la asociación entre un jugador y un acudiente

Para mantener la información de responsables del jugador actualizada y libre de relaciones obsoletas.

---

# Reglas de Negocio

* La relación debe existir antes de eliminarse.
* No se debe permitir dejar a un jugador activo sin acudiente principal.
* Si la asociación eliminada era la principal, se debe reasignar otra antes de completar la operación o rechazar la solicitud.
* La operación debe respetar el aislamiento por academia.

---

# Criterios de Aceptación

* Dado una relación existente, cuando la elimino y el jugador sigue teniendo principal válido, entonces el sistema la elimina correctamente.
* Dado una relación principal única, cuando intento eliminarla sin reasignar otra, entonces el sistema rechaza la operación.
* Dado una relación inexistente, cuando intento eliminarla, entonces el sistema rechaza la solicitud.

---

# Referencia Técnica

* Se recomienda manejar la eliminación como un caso de uso CQRS.
* La operación debe conservar integridad del agregado `Player`.
* Debe responder con DTO JSON estándar.

