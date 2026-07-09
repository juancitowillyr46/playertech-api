# HU-006 Actualizar usuario administrativo

## Epic
EP-003 Gestión de Usuarios y Accesos

## Prioridad
Media

## MVP
Sí

## Estado
Draft

## Actor Principal
Academy Admin

## Objetivo
Actualizar los datos de un usuario administrativo de academia.

## Reglas de Negocio
* Solo se pueden actualizar usuarios del tenant actual.
* No se puede convertir un usuario root en usuario tenant mediante este flujo.

## Criterios de Aceptación
* Dado un usuario administrativo válido, cuando se actualiza, entonces sus datos quedan persistidos.
* Dado un usuario de otra academia, cuando se intenta actualizar, entonces la operación es rechazada.

