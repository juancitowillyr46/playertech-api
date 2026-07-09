# HU-010 Crear usuario owner/admin inicial del tenant

## Epic
EP-003 Gestión de Usuarios y Accesos

## Prioridad
Alta

## MVP
Sí

## Estado
Draft

## Actor Principal
Platform Admin

## Objetivo
Crear el usuario owner/admin inicial asociado a una academia.

## Reglas de Negocio
* El usuario debe crearse asociado a una academia.
* El rol debe ser `ROLE_ACADEMIC_ADMIN`.
* Debe seguir el flujo de activación por correo.

## Criterios de Aceptación
* Dado un tenant nuevo, cuando se crea su admin inicial, entonces queda vinculado a la academia y pendiente de activación.

