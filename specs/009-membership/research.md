# Research: Membership Feature

**Feature Branch**: `009-membership`

## Notas de alpuedece

Esta feature covers membership creation, active ver, history, status changes
y initial charge generation.

## Resumen de decisiones

- `Membership` cubre ciclo activo, histoial, suspensión y retiro.
- `Charge` y `Payment` viven como capacidades cerpuedeas pero separadas po responseonsabilidad.
- El histoial debe prioizar eventos de negocio útiles para operación y auditoía.
