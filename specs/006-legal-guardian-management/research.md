# Investigación: Legal Guardian Management

**Feature Branch**: `006-legal-guardian-management`

## Notas de alcance

Esta feature cubre la gestión de acudientes legales como entidad base de contacto para flujos de jugadores, membresías y pagos.

## Resumen de decisiones

- `LegalGuardian` es el contacto base para flujos de jugadores, membresías y pagos.
- El alcance es tenant-scoped para todas las operaciones del negocio.
- La normalización de contacto se mantiene pragmática y orientada al contrato API.
