# HU-003 Crear usuario administrativo por invitación

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
Crear un usuario administrativo de academia mediante invitación por correo.

## Reglas de Negocio
* El usuario debe pertenecer a una academia.
* El rol funcional permitido para este flujo es `ROLE_ACADEMIC_ADMIN`.
* El usuario debe quedar pendiente de activación hasta confirmar su cuenta.

## Criterios de Aceptación
* Dado un correo válido y una academia válida, cuando se crea la invitación, entonces el usuario queda registrado en estado pendiente de activación.
* Dado un usuario que ya existe en la academia, cuando se intenta crear de nuevo, entonces la operación es rechazada.

