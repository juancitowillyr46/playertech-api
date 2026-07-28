# Research: Team Assignment Feature

**Feature Branch**: `010-team-assignment`

## Scope Notes

This feature covers assigning players to teams, marking the primary team and
keeping assignment history.

## Decision Snapshot

- Las asignaciones de equipo se manejan con primario explícito y trazabilidad histórica.
- El módulo protege la consistencia de estado sin mezclarla con `Team` o `Player`.
- Los listados deben permitir que la UI identifique la asignación principal sin cálculo extra.
