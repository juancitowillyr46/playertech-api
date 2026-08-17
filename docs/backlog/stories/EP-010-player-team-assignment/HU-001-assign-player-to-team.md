# HU-001 Asignar jugador a un equipo

## Epic
EP-010 Player Team Assignment

## Prioridad
Alta

## MVP
Sí

## Estado
Done

## Actor Principal
Administrador académico

## Objetivo
Asignar un jugador a un equipo para registrar su participación deportiva.

## Reglas de Negocio
* El jugador debe pertenecer a la academia actual.
* El equipo debe pertenecer a la academia actual.
* La asignación no altera la matrícula administrativa.
* Un jugador puede estar asignado a múltiples equipos.
* La fecha de inicio se asigna automáticamente con la fecha actual al crear la relación.
* Si la solicitud incluye `isPrimary = true`, la asignación debe quedar como principal y desplazar la principal anterior activa del mismo jugador.

## Criterios de Aceptación
* Dado un jugador válido y un equipo válido de la misma academia, cuando se registra la asignación, entonces la participación queda guardada.
* Dado un jugador o un equipo de otra academia, cuando se intenta asignar, entonces la operación es rechazada.
