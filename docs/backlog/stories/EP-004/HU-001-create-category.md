# HU-013 Crear Sede

## Información General

| Campo           | Valor                        |
| --------------- | -----------------------------|
| ID              | HU-001                       |
| Épica           | EP-004 Gestión de Categorias |
| Prioridad       | Alta                         |
| MVP             | Sí                           |
| Estado          | Draft                        |
| Actor Principal | Academic Administrator       |

---

# Objetivo

Permitir registrar una nueva categoria para la academia.

---

# Historia de Usuario

Como administrador de academia

Quiero registrar una categoria

Para organizar a los jugadores por rango de edades.

---

# Dominios Involucrados

* Category

---

# Reglas de Negocio

## BR-001

Toda categoria pertenece a una academia.

## BR-002

El nombre de la categoria es obligatorio.

## BR-003

La categoria se crea inicialmente en estado ACTIVE.

## BR-004

La edad mínima y edad máxima son campos obligatorios y son valores númericos de 0 a 100

---

# Datos Requeridos

## Obligatorios

* Nombre
* Edad mínima
* Edad máxima

## Opcionales

* Descripción

---

# Flujo Principal

1. El administrador accede al módulo de categorias.
2. Selecciona crear una categoria.
3. Ingresa la información: Nombre, edad mínima, edad máxima y descripción (Opcional)
4. El sistema valida los datos.
5. El sistema registra la categoria.
6. El sistema confirma la creación.

---

# Criterios de Aceptación

## CA-001

Dado información válida

Cuando el administrador registra una categoria

Entonces el sistema crea la categoria exitosamente.

---

## CA-002

Dado que el nombre es obligatorio

Cuando intenta guardar sin nombre

Entonces el sistema informa el error.

---

## CA-003

Dado una categoria creada

Cuando finaliza el registro

Entonces el estado inicial es ACTIVE.

---

## CA-004

Dado que la edad mínima y edad máxima son obligatorios

Cuando intenta guardar esa rango de edades

Entonces el sistema informa el error.

---

# Permisos Requeridos

* Category.Create
