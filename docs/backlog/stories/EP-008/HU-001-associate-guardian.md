# HU-001 Asociar acudiente a jugador

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-001 |
| Épica | EP-008 Gestión de Relaciones Jugador-Acudiente |
| Prioridad | Alta |
| MVP | Sí |
| Estado | Done |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir asociar un acudiente existente a un jugador dentro de la academia actual.

---

# Historia de Usuario

Como administrador de academia

Quiero asociar un acudiente a un jugador

Para registrar a los responsables legales del jugador y mantener su información de contacto.

---

# Reglas de Negocio

* La relación debe pertenecer a la academia actual.
* El jugador debe existir.
* El acudiente debe existir.
* Un jugador puede tener múltiples acudientes asociados.
* Al menos una relación debe poder marcarse como principal.
* La asociación no debe permitir duplicados exactos del mismo jugador y acudiente.

---

# Criterios de Aceptación

* Dado un jugador y un acudiente válidos, cuando los asocio, entonces el sistema crea la relación correctamente.
* Dado un acudiente ya asociado al mismo jugador, cuando intento repetir la operación, entonces el sistema rechaza la solicitud.
* Dado un jugador o acudiente inexistente, cuando intento asociarlos, entonces el sistema rechaza la operación.

---

# Referencia Técnica

* Se recomienda exponer esta operación desde un comando CQRS sobre `PlayerGuardian`.
* Debe responder con DTO JSON estándar.
* Debe respetar el aislamiento por `academy_id`.
