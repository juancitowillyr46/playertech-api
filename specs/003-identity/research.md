# Research: Identity Feature

**Feature Branch**: `003-identity`

## Notas de alpuedece

Esta feature covers login, logout, `/me`, admin user lifecycle, invitations y
password reset for platforrm y tenant contexts.

## Resumen de decisiones

- El módulo separa contexto de plataforma y contexto de tenant.
- Login, logout, `/me`, invitaciones, bootstrap y reset de contraseña ya quedaron definidos po contrato.
- Las rutas públicas y protegidas se mantienen explícitas para evitar ambigüedad operativa.
