# HU-017 Desactivar Sede

## Información General

| Campo           | Valor                   |
| --------------- | ----------------------- |
| ID              | HU-017                  |
| Épica           | EP-002 Gestión de Sedes |
| Prioridad       | Media                   |
| MVP             | Sí                      |
| Estado          | Draft                   |
| Actor Principal | Academic Administrator  |

---

# Objetivo

Permitir desactivar una sede.

---

# Historia de Usuario

Como administrador de academia

Quiero desactivar una sede

Para evitar que continúe siendo utilizada.

---

# Reglas de Negocio

## BR-001

Las sedes no se eliminan físicamente.

## BR-002

La sede cambia a estado INACTIVE.

---

# Flujo Principal

1. Selecciona una sede.
2. Solicita desactivación.
3. El sistema cambia el estado.
4. El sistema registra la operación.

---

# Criterios de Aceptación

## CA-001

Dado una sede activa

Cuando se desactiva

Entonces el sistema cambia el estado a INACTIVE.

---

# Permisos Requeridos

* Venue.Disable
