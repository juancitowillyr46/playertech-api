# HU-007 Desactivar usuario administrativo

## Epic
EP-003 Gestión de Usuarios y Accesos

## Prioridad
Alta

## MVP
Sí

## Estado
Draft

## Actor Principal
Academy Admin

## Objetivo
Desactivar un usuario administrativo de academia.

## Reglas de Negocio
* No se puede desactivar el último administrador activo del tenant.
* Desactivar un usuario no desactiva la academia.

## Criterios de Aceptación
* Dado un usuario administrativo activo, cuando se desactiva, entonces queda inhabilitado para autenticarse.
* Dado el último administrador activo del tenant, cuando se intenta desactivar, entonces la operación es rechazada.

