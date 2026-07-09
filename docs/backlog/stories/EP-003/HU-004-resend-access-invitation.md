# HU-004 Reenviar invitación de acceso

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
Reenviar la invitación de acceso a un usuario administrativo que aún no ha activado su cuenta.

## Reglas de Negocio
* Solo aplica a usuarios pendientes de activación.
* El reenvío debe generar una nueva invitación válida.

## Criterios de Aceptación
* Dado un usuario pendiente de activación, cuando se reenvía la invitación, entonces recibe un nuevo correo con un token válido.
* Dado un usuario ya activo, cuando se intenta reenviar la invitación, entonces la operación es rechazada.

