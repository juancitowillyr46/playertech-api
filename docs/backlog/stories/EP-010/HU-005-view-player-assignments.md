# HU-005 Consultar asignaciones de un jugador

## Epic
EP-010 Gestión de Asignaciones Deportivas

## Prioridad
Media

## MVP
Sí

## Estado
Draft

## Actor Principal
Administrador académico

## Objetivo
Consultar las asignaciones deportivas de un jugador para conocer su equipo principal y sus equipos secundarios.

## Reglas de Negocio
* La consulta debe respetar el `academy_id` actual.
* Debe mostrar la asignación principal y las asignaciones secundarias.
* No debe incluir asignaciones eliminadas lógicamente.

## Criterios de Aceptación
* Dado un jugador con asignaciones, cuando se consulta su información, entonces se listan sus equipos y se identifica el principal.
* Dado un jugador sin asignaciones, cuando se consulta su información, entonces se indica que no tiene participaciones registradas.
