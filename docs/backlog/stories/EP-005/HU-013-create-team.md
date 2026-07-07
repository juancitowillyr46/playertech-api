# HU-013 Crear Equipo

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-013 |
| Épica | EP-005 Gestión de Equipos |
| Prioridad | Alta |
| MVP | Sí |
| Estado | Draft |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir registrar un nuevo equipo deportivo dentro de una academia.

---

# Historia de Usuario

Como administrador de academia

Quiero registrar un equipo

Para organizar a los jugadores por categoría competitiva.

---

# Dominios Involucrados

* Team
* Category

---

# Reglas de Negocio

## BR-001

Todo equipo pertenece a una academia.

## BR-002

Todo equipo pertenece a una categoría.

## BR-003

El nombre del equipo es obligatorio.

## BR-004

El equipo se crea inicialmente en estado ACTIVE.

---

# Datos Requeridos

## Obligatorios

* Nombre
* Categoría

---

# Flujo Principal

1. El administrador accede al módulo de equipos.
2. Selecciona crear equipo.
3. Ingresa la información requerida.
4. El sistema valida los datos.
5. El sistema registra el equipo.
6. El sistema confirma la creación.

---

# Criterios de Aceptación

## CA-001

Dado información válida

Cuando el administrador registra un equipo

Entonces el sistema crea el equipo exitosamente.

## CA-002

Dado que el nombre es obligatorio

Cuando intenta guardar sin nombre

Entonces el sistema informa el error.

## CA-003

Dado que el equipo pertenece a una categoría

Cuando la categoría no existe en la academia

Entonces el sistema rechaza la operación.

## CA-004

Dado un equipo creado

Cuando finaliza el registro

Entonces el estado inicial es ACTIVE.

---

# Permisos Requeridos

* Team.Create
