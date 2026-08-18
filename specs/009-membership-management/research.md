# Research: Membership Feature

**Feature Branch**: `009-membership-management`

## Notas de alcance

Esta feature covers membership creation, active view, history, status changes
and an explicit financial reference boundary.

## Resumen de decisiones

- `Membership` cubre ciclo activo, historial, suspensión y retiro.
- `Charge` y `Payment` viven como capacidades cercanas pero separadas por responsabilidad.
- El historial debe priorizar eventos de negocio útiles para operación y auditoría.
- La referencia financiera debe quedar documentada para que el flujo de cargos y pagos se conecte después sin contaminar la matrícula.
