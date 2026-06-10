# HU-013 Crear Sede

## Información General

| Campo           | Valor                   |
| --------------- | ----------------------- |
| ID              | HU-013                  |
| Épica           | EP-002 Gestión de Sedes |
| Prioridad       | Alta                    |
| MVP             | Sí                      |
| Estado          | Draft                   |
| Actor Principal | Academic Administrator  |

---

# Objetivo

Permitir registrar una nueva sede para la academia.

---

# Historia de Usuario

Como administrador de academia

Quiero registrar una sede

Para organizar los lugares donde se realizan las actividades deportivas.

---

# Dominios Involucrados

* Venue

---

# Reglas de Negocio

## BR-001

Toda sede pertenece a una academia.

## BR-002

El nombre de la sede es obligatorio.

## BR-003

La sede se crea inicialmente en estado ACTIVE.

---

# Datos Requeridos

## Obligatorios

* Nombre

## Opcionales

* Dirección
* Ciudad
* Teléfono
* Observaciones

---

# Flujo Principal

1. El administrador accede al módulo de sedes.
2. Selecciona crear sede.
3. Ingresa la información.
4. El sistema valida los datos.
5. El sistema registra la sede.
6. El sistema confirma la creación.

---

# Criterios de Aceptación

## CA-001

Dado información válida

Cuando el administrador registra una sede

Entonces el sistema crea la sede exitosamente.

---

## CA-002

Dado que el nombre es obligatorio

Cuando intenta guardar sin nombre

Entonces el sistema informa el error.

---

## CA-003

Dado una sede creada

Cuando finaliza el registro

Entonces el estado inicial es ACTIVE.

---

# Permisos Requeridos

* Venue.Create
