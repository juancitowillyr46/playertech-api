# HU-014 Consultar Equipos

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-014 |
| Épica | EP-005 Gestión de Equipos |
| Prioridad | Alta |
| MVP | Sí |
| Estado | Done |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir visualizar los equipos registrados dentro de la academia autenticada.

---

# Historia de Usuario

Como administrador de academia

Quiero consultar los equipos

Para administrar la estructura competitiva del tenant.

---

# Reglas de Negocio

## BR-001

Solo se visualizan equipos de la academia autenticada.

---

# Criterios de Aceptación

## CA-001

Dado equipos registrados

Cuando el administrador consulta el listado

Entonces el sistema muestra los equipos disponibles.

## CA-002

Dado una academia sin equipos

Cuando consulta el listado

Entonces el sistema muestra una lista vacía.

---

# Permisos Requeridos

* Team.Read

