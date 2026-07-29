# Team Domain Spec

## Purpose

Documento central del dominio `Team`.

## Canonical Sources

- API operativa: [`docs/contracts/api-reference.md`](/home/juan-rodas/projects/playertech/playertech-api/docs/contracts/api-reference.md)
- Estado actual: [`specs/14-current-state.md`](/home/juan-rodas/projects/playertech/playertech-api/specs/14-current-state.md)
- Memoria persistente: [`docs/architecture/memory/project-memory.md`](/home/juan-rodas/projects/playertech/playertech-api/docs/architecture/memory/project-memory.md)

## Scope

- create
- list
- detail
- update
- activate/inactivate
- categoryName in responses
- onboarding initial team

## Contract Notes

- El listado y el detalle exponen `categoryName` como campo derivado de salida para simplificar la UI.
- El sort operativo del listado se normaliza antes de llegar a Doctrine.
- El contrato de `Team` debe mantenerse plano para el frontend, sin anidar un objeto `category`.
- El flujo de signup del tenant crea el primer equipo y reutiliza el mismo enriquecimiento de salida.

## Domain Model

### Team

- `id`
- `academyId`
- `categoryId`
- `name`
- `status`
- `auditTrail`
- `deletedAt`
- `deletedBy`

### Team Mapping

- Table: `teams`
- `id`
- `academy_id`
- `category_id`
- `name`
- `status`
- `audit_trail.*`
- `deleted_at`
- `deleted_by`

## Functional Guides

- Backlog epic: [`docs/backlog/epics/EP-005.md`](/home/juan-rodas/projects/playertech/playertech-api/docs/backlog/epics/EP-005.md)
- Team assignment flows: [`docs/backlog/epics/EP-010.md`](/home/juan-rodas/projects/playertech/playertech-api/docs/backlog/epics/EP-010.md)
