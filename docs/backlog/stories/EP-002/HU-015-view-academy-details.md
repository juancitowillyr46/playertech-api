# HU-015 Consultar Detalle de Sede

## Información General

| Campo           | Valor                   |
| --------------- | ----------------------- |
| ID              | HU-015                  |
| Épica           | EP-002 Gestión de Sedes |
| Prioridad       | Media                   |
| MVP             | Sí                      |
| Estado          | Draft                   |
| Actor Principal | Academic Administrator  |

---

# Objetivo

Permitir consultar la información completa de una sede.

---

# Historia de Usuario

Como administrador de academia

Quiero consultar una sede

Para visualizar toda su información.

---

# Flujo Principal

1. Selecciona una sede.
2. El sistema consulta la información.
3. El sistema muestra el detalle.

---

# Criterios de Aceptación

## CA-001

Dado una sede existente

Cuando consulta el detalle

Entonces el sistema muestra toda la información registrada.

---

## CA-002

Dado una sede inexistente

Cuando intenta consultarla

Entonces el sistema informa el error.

---

# Permisos Requeridos

* Venue.Read
