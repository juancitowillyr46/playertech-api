# Research: Player Team Assignment Feature

**Feature Branch**: `010-team-assignment`

## Notas de alcance

Esta feature covers assigning players to teams, marking the primary team, keeping assignment history y exposing an autocomplete selector for available teams.

## Resumen de decisiones

- Las asignaciones de equipo se manejan con primario explícito y trazabilidad histórica.
- El módulo protege la consistencia de estado sin mezclarla con `Team` o `Player`.
- Los listados deben permitir que la UI identifique la asignación principal sin cálculo extra.
- El selector de equipos debe ser liviano, sólo con equipos activos y apto para autocomplete.
