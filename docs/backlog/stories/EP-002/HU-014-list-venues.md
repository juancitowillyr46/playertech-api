# HU-014 Consultar Sedes

## Información General

| Campo           | Valor                   |
| --------------- | ----------------------- |
| ID              | HU-014                  |
| Épica           | EP-002 Gestión de Sedes |
| Prioridad       | Alta                    |
| MVP             | Sí                      |
| Estado          | Draft                   |
| Actor Principal | Academic Administrator  |

---

# Objetivo

Permitir visualizar las sedes registradas.

---

# Historia de Usuario

Como administrador de academia

Quiero consultar las sedes

Para administrar la estructura física de la academia.

---

# Reglas de Negocio

## BR-001

Solo se visualizan sedes de la academia autenticada.

---

# Flujo Principal

1. El administrador ingresa al módulo.
2. El sistema consulta las sedes.
3. El sistema muestra el listado.

---

# Criterios de Aceptación

## CA-001

Dado sedes registradas

Cuando el administrador consulta el listado

Entonces el sistema muestra las sedes disponibles.

---

## CA-002

Dado una academia sin sedes

Cuando consulta el listado

Entonces el sistema muestra una lista vacía.

---

# Permisos Requeridos

* Venue.Read
