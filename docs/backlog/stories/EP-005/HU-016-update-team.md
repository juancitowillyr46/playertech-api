# HU-016 Actualizar Equipo

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-016 |
| Épica | EP-005 Gestión de Equipos |
| Prioridad | Alta |
| MVP | Sí |
| Estado | Draft |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir actualizar la información de un equipo.

---

# Historia de Usuario

Como administrador de academia

Quiero actualizar un equipo

Para mantener su configuración al día.

---

# Reglas de Negocio

## BR-001

El equipo debe existir.

## BR-002

El nombre continúa siendo obligatorio.

## BR-003

El equipo debe pertenecer a una categoría válida de la academia.

---

# Criterios de Aceptación

## CA-001

Dado información válida

Cuando actualiza un equipo

Entonces el sistema guarda los cambios.

## CA-002

Dado información inválida

Cuando intenta guardar

Entonces el sistema informa los errores.

---

# Permisos Requeridos

* Team.Update
