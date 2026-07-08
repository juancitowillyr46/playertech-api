# HU-017 Desactivar Equipo

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-017 |
| Épica | EP-005 Gestión de Equipos |
| Prioridad | Media |
| MVP | Sí |
| Estado | Done |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir desactivar un equipo.

---

# Historia de Usuario

Como administrador de academia

Quiero desactivar un equipo

Para evitar que continúe usándose en la operación.

---

# Reglas de Negocio

## BR-001

Los equipos no se eliminan físicamente.

## BR-002

El equipo cambia a estado INACTIVE.

---

# Criterios de Aceptación

## CA-001

Dado un equipo activo

Cuando se desactiva

Entonces el sistema cambia el estado a INACTIVE.

---

# Permisos Requeridos

* Team.Disable

