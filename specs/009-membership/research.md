# Research: Membership Feature

**Feature Branch**: `009-membership`

## Scope Notes

This feature covers membership creation, active view, history, status changes
and initial charge generation.

## Decision Snapshot

- `Membership` cubre ciclo activo, historial, suspensión y retiro.
- `Charge` y `Payment` viven como capacidades cercanas pero separadas por responsabilidad.
- El historial debe priorizar eventos de negocio útiles para operación y auditoría.
