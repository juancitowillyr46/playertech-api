# HU-004 Finalizar una asignación

## Epic
EP-010 Gestión de Asignaciones Deportivas

## Prioridad
Alta

## MVP
Sí

## Estado
Done

## Actor Principal
Administrador académico

## Objetivo
Finalizar la participación de un jugador en un equipo.

## Reglas de Negocio
* Solo una asignación activa puede finalizarse.
* Finalizar una asignación no elimina el historial.
* Si la asignación finalizada era principal, el sistema debe dejar definido otro principal activo o rechazar la operación según la regla del caso de uso.

## Criterios de Aceptación
* Dado una asignación activa, cuando se finaliza, entonces deja de estar activa y conserva su historial.
* Dado una asignación inexistente o ya finalizada, cuando se intenta finalizar, entonces la operación es rechazada.
