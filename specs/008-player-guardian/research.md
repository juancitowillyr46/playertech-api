# Research: Player Guardian Feature

**Feature Branch**: `008-player-guardian`

## Scope Notes

This feature covers the association between players and guardians, including the
primary guardian lifecycle.

## Decision Snapshot

- La relación jugador-acudiente ya está modelada con historial y control de primario.
- Las operaciones de asociación, cambio de principal y eliminación viven en el módulo de `Player`.
- El contrato de lectura expone la relación con la metadata necesaria para UI operativa.
