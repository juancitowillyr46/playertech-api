# HU-003 Cambiar el equipo principal

## Epic
EP-010 Gestión de Asignaciones Deportivas

## Prioridad
Alta

## MVP
Sí

## Estado
Draft

## Actor Principal
Administrador académico

## Objetivo
Cambiar el equipo principal de un jugador sin perder el historial de sus otras asignaciones.

## Reglas de Negocio
* El nuevo equipo principal debe ser una asignación activa del jugador.
* El cambio debe conservar el historial.
* La asignación anterior deja de ser principal, pero sigue activa si no se finaliza explícitamente.

## Criterios de Aceptación
* Dado un jugador con varias asignaciones activas, cuando se cambia su equipo principal, entonces el nuevo equipo queda como principal.
* Dado un jugador sin asignación activa para el equipo elegido, cuando se intenta cambiar el principal, entonces la operación es rechazada.
