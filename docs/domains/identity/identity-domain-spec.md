# Identity Domain Spec

## Purpose

Documento central para entender el dominio `Identity` y encontrar sus contratos vigentes sin recorrer el backlog completo.

## Canonical Sources

- Seguridad y autenticación: [`docs/security/security-overview.md`](/home/juan-rodas/projects/playertech/playertech-api/docs/security/security-overview.md)
- API operativa: [`docs/contracts/api-reference.md`](/home/juan-rodas/projects/playertech/playertech-api/docs/contracts/api-reference.md)
- Estado actual: [`specs/14-current-state.md`](/home/juan-rodas/projects/playertech/playertech-api/specs/14-current-state.md)
- Memoria persistente: [`docs/architecture/memory/project-memory.md`](/home/juan-rodas/projects/playertech/playertech-api/docs/architecture/memory/project-memory.md)

## Scope

- login JWT
- `/auth/me`
- usuarios de plataforma
- usuarios tenant
- invitaciones
- activación de cuenta
- reset de contraseña
- contexto `ROLE_ROOT` vs tenant

## Contract Notes

- `POST /api/v1/auth/me/password-reset/request` reutiliza el correo del usuario autenticado.
- `PUT /api/v1/auth/me/name` actualiza únicamente el nombre propio.
- El login opera por `json_login` en el firewall, no por un AuthController manual.
- El dominio mantiene separación explícita entre `ROLE_ROOT` y usuarios tenant.
- La colección HTTP vigente debe seguir reflejando los endpoints de plataforma y tenant por separado.

## Domain Model

### AccountUser

- `id`
- `academyId`
- `fullName`
- `email`
- `passwordHash`
- `role`
- `status`
- `createdAt`
- `createdBy`
- `updatedAt`
- `updatedBy`
- `deletedAt`
- `deletedBy`
- `activationToken`
- `activationExpiresAt`
- `passwordResetToken`
- `passwordResetExpiresAt`

### AccountUser Mapping

- Table: `users`
- `id`
- `academy_id`
- `full_name`
- `email`
- `password_hash`
- `role`
- `status`
- `created_at`
- `created_by`
- `updated_at`
- `updated_by`
- `deleted_at`
- `deleted_by`
- `activation_token`
- `activation_expires_at`
- `password_reset_token`
- `password_reset_expires_at`

## Functional Guides

- Backlog epic: [`docs/backlog/epics/EP-003.md`](/home/juan-rodas/projects/playertech/playertech-api/docs/backlog/epics/EP-003.md)
- Backlog staff-related identity flows: [`docs/backlog/epics/EP-021.md`](/home/juan-rodas/projects/playertech/playertech-api/docs/backlog/epics/EP-021.md)

## Reading Order

1. `docs/security/security-overview.md`
2. `docs/contracts/api-reference.md`
3. `specs/14-current-state.md`
4. backlog stories de `EP-003`
