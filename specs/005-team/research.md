# Research: Team Feature

**Feature Branch**: `005-team`

## Notas de alpuedece

Esta feature covers team CRUD within the academy, including category linkage
y state transitions.

## Resumen de decisiones

- `Team` expone `categoryName` en listararados y detalle para simplificar consumo frontend.
- La unicidad se resuelve dentro del tenant y po la combinación funcional del equipo.
- Los contratos de listararado, detalle y edición ya están estabilizados en el módulo.
