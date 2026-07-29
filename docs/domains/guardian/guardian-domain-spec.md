# Guardian Domain Spec

## Purpose

Documento central del dominio `Guardian`.

## Canonical Sources

- API operativa: [`docs/contracts/api-reference.md`](/home/juan-rodas/projects/playertech/playertech-api/docs/contracts/api-reference.md)
- Estado actual: [`specs/14-current-state.md`](/home/juan-rodas/projects/playertech/playertech-api/specs/14-current-state.md)
- Memoria persistente: [`docs/architecture/memory/project-memory.md`](/home/juan-rodas/projects/playertech/playertech-api/docs/architecture/memory/project-memory.md)

## Scope

- create
- list
- detail
- associate to player
- change primary
- remove association

## Contract Notes

- `GET /api/v1/academy/guardians` lista acudientes del tenant actual con paginación estándar.
- `GET /api/v1/academy/guardians/{guardianId}` devuelve el detalle del acudiente dentro del tenant.
- `POST /api/v1/academy/guardians` crea un acudiente legal como contacto reutilizable para `Player` y finanzas.
- El dominio `Guardian` es el contacto base; la relación jugador-acudiente vive en `PlayerGuardian`.

## Domain Model

### LegalGuardian

- `id`
- `academyId`
- `firstName`
- `lastName`
- `phone`
- `email`
- `documentType`
- `documentNumber`
- `address`
- `relationship`
- `status`
- `auditTrail`
- `deletedAt`
- `deletedBy`

### LegalGuardian Mapping

- Table: `legal_guardians`
- `id`
- `academy_id`
- `first_name`
- `last_name`
- `phone`
- `email`
- `document_type`
- `document_number`
- `address`
- `relationship`
- `status`
- `audit_trail.*`
- `deleted_at`
- `deleted_by`

## Functional Guides

- Backlog epic: [`docs/backlog/epics/EP-006.md`](/home/juan-rodas/projects/playertech/playertech-api/docs/backlog/epics/EP-006.md)
- Player-guardian flows: [`docs/backlog/epics/EP-008.md`](/home/juan-rodas/projects/playertech/playertech-api/docs/backlog/epics/EP-008.md)
