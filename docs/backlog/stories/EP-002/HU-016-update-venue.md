# HU-016 Actualizar Sede

## Información General

| Campo           | Valor                   |
| --------------- | ----------------------- |
| ID              | HU-016                  |
| Épica           | EP-002 Gestión de Sedes |
| Prioridad       | Alta                    |
| MVP             | Sí                      |
| Estado          | Draft                   |
| Actor Principal | Academic Administrator  |

---

# Objetivo

Permitir actualizar la información de una sede.

---

# Historia de Usuario

Como administrador de academia

Quiero actualizar una sede

Para mantener la información actualizada.

---

# Reglas de Negocio

## BR-001

La sede debe existir.

## BR-002

El nombre continúa siendo obligatorio.

---

# Flujo Principal

1. Selecciona una sede.
2. Modifica la información.
3. El sistema valida los datos.
4. El sistema guarda los cambios.

---

# Criterios de Aceptación

## CA-001

Dado información válida

Cuando actualiza una sede

Entonces el sistema guarda los cambios.

---

## CA-002

Dado información inválida

Cuando intenta guardar

Entonces el sistema informa los errores.

---

# Permisos Requeridos

* Venue.Update
