# Category Domain Spec

## Purpose

Documento central del dominio `Category`.

## Canonical Sources

- API operativa: [`specs/16-api-reference.md`](/home/juan-rodas/projects/playertech/playertech-api/specs/16-api-reference.md)
- Estado actual: [`specs/14-current-state.md`](/home/juan-rodas/projects/playertech/playertech-api/specs/14-current-state.md)
- Memoria persistente: [`docs/architecture/memory/project-memory.md`](/home/juan-rodas/projects/playertech/playertech-api/docs/architecture/memory/project-memory.md)

## Scope

- create
- list
- detail
- update
- activate/inactivate
- options selector
- business key `categoryKey`
- onboarding categories

## Contract Notes

- `categoryKey` es la clave funcional estable del catálogo y debe mantenerse fuera del payload editable.
- La respuesta de `options` es el contrato liviano para selects de frontend.
- El list y el detail pueden mostrar `categoryKey`, `name`, `status` y metadatos de auditoría.
- El onboarding depende de categorías activas del tenant y del catálogo público generado por backend.

## Domain Model

### Category

- `id`
- `academyId`
- `categoryKey`
- `name`
- `minAge`
- `maxAge`
- `description`
- `status`
- `auditTrail`
- `deletedAt`
- `deletedBy`

### OnboardingCategory

- `id`
- `name`
- `minAge`
- `maxAge`
- `status`

### Category Mapping

- Table: `categories`
- `id`
- `academy_id`
- `category_key`
- `name`
- `min_age`
- `max_age`
- `description`
- `status`
- `audit_trail.*`
- `deleted_at`
- `deleted_by`

### OnboardingCategory Mapping

- Table: `onboarding_categories`
- `id`
- `code`
- `name`
- `min_age`
- `max_age`
- `description`
- `status`
- `created_at`
- `updated_at`

## Functional Guides

- Backlog epic: [`docs/backlog/epics/EP-004.md`](/home/juan-rodas/projects/playertech/playertech-api/docs/backlog/epics/EP-004.md)
- Player relation and business key story: [`docs/backlog/stories/EP-007/HU-008-category-business-key.md`](/home/juan-rodas/projects/playertech/playertech-api/docs/backlog/stories/EP-007/HU-008-category-business-key.md)
