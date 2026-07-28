# Research: Venue Feature

**Feature Branch**: `002-venue`

## Scope Notes

This feature captures the academy venue catalog, including contact metadata and
status transitions.

## Decision Snapshot

- `Venue` funciona como catálogo academico con aislamiento por tenant.
- La unicidad y la visibilidad de activos/inactivos se gobiernan por el contrato ya documentado en el módulo.
- El modelo prioriza la simplicidad del consumo API sobre normalización excesiva de campos de contacto.
