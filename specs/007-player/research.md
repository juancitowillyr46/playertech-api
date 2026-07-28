# Research: Player Feature

**Feature Branch**: `007-player`

## Scope Notes

This feature covers player registration, listing, detail, update, state,
photo upload, delete photo and async import.

## Decision Snapshot

- El listado usa filtros por `gender`, `categoryId`, `createdAtFrom`, `createdAtTo`, `birthDateFrom` y `birthDateTo`.
- `age` es un campo derivado de salida; no es el filtro canónico.
- La importación usa `categoryId` a nivel de job, no por fila.
- La plantilla oficial se genera desde backend con hojas `Datos` y `Referencias`.
