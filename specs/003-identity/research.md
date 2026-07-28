# Research: Identity Feature

**Feature Branch**: `003-identity`

## Scope Notes

This feature covers login, logout, `/me`, admin user lifecycle, invitations and
password reset for platform and tenant contexts.

## Decision Snapshot

- El módulo separa contexto de plataforma y contexto de tenant.
- Login, logout, `/me`, invitaciones, bootstrap y reset de contraseña ya quedaron definidos por contrato.
- Las rutas públicas y protegidas se mantienen explícitas para evitar ambigüedad operativa.
