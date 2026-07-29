# Staff Domain Spec

## Purpose

Documento central del dominio `Staff`.

## Canonical Sources

- API operativa: [`docs/contracts/api-reference.md`](/home/juan-rodas/projects/playertech/playertech-api/docs/contracts/api-reference.md)
- Estado actual: [`specs/14-current-state.md`](/home/juan-rodas/projects/playertech/playertech-api/specs/14-current-state.md)
- Memoria persistente: [`docs/architecture/memory/project-memory.md`](/home/juan-rodas/projects/playertech/playertech-api/docs/architecture/memory/project-memory.md)

## Scope

- invite staff member
- activate account and set password
- register user as staff member
- assign staff member to team
- assign technical role
- change technical role
- remove staff member from team
- view team staff
- create staff member with access

## Contract Notes

- `GET /api/v1/academy/staff/options` sirve como selector liviano para frontend.
- El módulo separa creación de miembro, invitación, rol técnico y asignación a equipo.
- Los listados y detalle deben mantenerse tenant-scoped.
- El contrato visible para UI debe evitar hidratar usuarios completos cuando solo se requiere un option list.

## Domain Model

### Staff

- `id`
- `academyId`
- `userId`
- `status`
- `auditTrail`
- `deletedAt`
- `deletedBy`

### TeamStaffAssignment

- `id`
- `academyId`
- `staffId`
- `teamId`
- `role`
- `status`
- `auditTrail`
- `deletedAt`
- `deletedBy`

### Staff Mapping

- Table: `staff`
- `id`
- `academy_id`
- `user_id`
- `status`
- `audit_trail.*`
- `deleted_at`
- `deleted_by`

### TeamStaffAssignment Mapping

- Table: `team_staff_assignments`
- `id`
- `academy_id`
- `staff_id`
- `team_id`
- `role`
- `status`
- `audit_trail.*`
- `deleted_at`
- `deleted_by`

## Functional Guides

- Backlog epic: [`docs/backlog/epics/EP-021.md`](/home/juan-rodas/projects/playertech/playertech-api/docs/backlog/epics/EP-021.md)
