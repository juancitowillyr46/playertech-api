# HU-018 Reactivar Sede

## Información General

| Campo           | Valor                   |
| --------------- | ----------------------- |
| ID              | HU-018                  |
| Épica           | EP-002 Gestión de Sedes |
| Prioridad       | Media                   |
| MVP             | Sí                      |
| Estado          | Draft                   |
| Actor Principal | Academic Administrator  |

---

# Objetivo

Permitir reactivar una sede previamente desactivada.

---

# Historia de Usuario

Como administrador de academia

Quiero reactivar una sede

Para volver a utilizarla dentro de la operación.

---

# Reglas de Negocio

## BR-001

Solo pueden reactivarse sedes INACTIVE.

---

# Flujo Principal

1. Selecciona una sede inactiva.
2. Solicita reactivación.
3. El sistema cambia el estado a ACTIVE.

---

# Criterios de Aceptación

## CA-001

Dado una sede inactiva

Cuando se reactiva

Entonces el sistema cambia el estado a ACTIVE.

---

# Permisos Requeridos

* Venue.Enable
