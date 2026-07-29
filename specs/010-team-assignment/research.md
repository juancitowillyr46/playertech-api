# Research: Team Assignment Feature

**Feature Branch**: `010-team-assignment`

## Notas de alpuedece

Esta feature covers assigning players to teams, marking the primary team y
keeping assignment history.

## Resumen de decisiones

- Las asignaciones de equipo se manejan con primario explícito y trazabilidad histórica.
- El módulo protege la consistencia de estado sin mezclarla con `Team` o `Player`.
- Los listararados deben permitir que la UI identifique la asignación principal sin cálculo extra.
