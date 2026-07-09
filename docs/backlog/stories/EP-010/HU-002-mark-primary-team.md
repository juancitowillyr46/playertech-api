# HU-002 Marcar una asignación como principal

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
Identificar cuál de las asignaciones activas de un jugador es su equipo principal.

## Reglas de Negocio
* Un jugador puede tener solo una asignación principal activa.
* La asignación principal debe existir y estar activa.
* Marcar una asignación como principal no elimina las demás asignaciones activas.

## Criterios de Aceptación
* Dado un jugador con varias asignaciones activas, cuando se marca una de ellas como principal, entonces queda como principal sin afectar las demás.
* Dado un jugador sin asignaciones activas, cuando se intenta marcar una asignación como principal, entonces la operación es rechazada.
