# Research: Category Feature

**Feature Branch**: `004-category`

## Scope Notes

This feature covers category catalog, lifecycle and the stable `categoryKey`
used by teams, players and onboarding flows.

## Decision Snapshot

- `categoryKey` es la clave de negocio estable del catálogo.
- Las opciones activas sirven a flujos como team creation, player import y onboarding.
- El listado completo y los options comparten el mismo contrato base con diferencias de amplitud.
