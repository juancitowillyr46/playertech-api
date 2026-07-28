# Research: Team Feature

**Feature Branch**: `005-team`

## Scope Notes

This feature covers team CRUD within the academy, including category linkage
and state transitions.

## Decision Snapshot

- `Team` expone `categoryName` en listados y detalle para simplificar consumo frontend.
- La unicidad se resuelve dentro del tenant y por la combinación funcional del equipo.
- Los contratos de listado, detalle y edición ya están estabilizados en el módulo.
