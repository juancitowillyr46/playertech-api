# HU-005 Activar cuenta y definir contraseña

## Epic
EP-003 Gestión de Usuarios y Accesos

## Prioridad
Alta

## MVP
Sí

## Estado
Draft

## Actor Principal
Usuario invitado

## Objetivo
Permitir que el usuario invitado active su cuenta y defina su contraseña.

## Reglas de Negocio
* El token de activación debe ser válido.
* La contraseña debe confirmarse antes de activarse la cuenta.
* La cuenta queda activa luego de validar el correo y definir la contraseña.

## Criterios de Aceptación
* Dado un token válido y una contraseña confirmada, cuando el usuario activa su cuenta, entonces queda habilitado para iniciar sesión.
* Dado un token inválido o expirado, cuando intenta activarse, entonces la operación es rechazada.

