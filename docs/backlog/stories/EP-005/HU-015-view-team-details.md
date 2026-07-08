# HU-015 Ver Detalle de Equipo

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-015 |
| Épica | EP-005 Gestión de Equipos |
| Prioridad | Alta |
| MVP | Sí |
| Estado | Done |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir consultar el detalle de un equipo registrado.

---

# Historia de Usuario

Como administrador de academia

Quiero consultar el detalle de un equipo

Para revisar su configuración y estado.

---

# Reglas de Negocio

## BR-001

El equipo debe existir dentro de la academia autenticada.

---

# Criterios de Aceptación

## CA-001

Dado un equipo existente

Cuando se consulta su detalle

Entonces el sistema muestra la información completa.

## CA-002

Dado un equipo inexistente

Cuando se consulta su detalle

Entonces el sistema rechaza la operación.

---

# Permisos Requeridos

* Team.Read

