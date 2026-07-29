# Player Domain Spec

## Purpose

Documento central del dominio `Player`.

## Canonical Sources

- API operativa: [`docs/contracts/api-reference.md`](/home/juan-rodas/projects/playertech/playertech-api/docs/contracts/api-reference.md)
- Feature spec: [`specs/007-player/spec.md`](/home/juan-rodas/projects/playertech/playertech-api/specs/007-player/spec.md)
- Estado actual: [`specs/14-current-state.md`](/home/juan-rodas/projects/playertech/playertech-api/specs/14-current-state.md)
- Memoria persistente: [`docs/architecture/memory/project-memory.md`](/home/juan-rodas/projects/playertech/playertech-api/docs/architecture/memory/project-memory.md)

## Scope

- create
- list
- detail
- update
- activate/inactivate
- photo upload and delete
- bulk import
- listing enrichment

## Domain Model

### Player

- `id`
- `academyId`
- `documentType`
- `firstName`
- `lastName`
- `birthDate`
- `documentNumber`
- `email`
- `phone`
- `nationality`
- `gender`
- `federationId`
- `dominantFoot`
- `categoryId`
- `photo`
- `status`
- `auditTrail`
- `deletedAt`
- `deletedBy`

### PlayerImportJob

- `id`
- `academyId`
- `createdBy`
- `categoryId`
- `originalFileName`
- `filePath`
- `status`
- `progress`
- `totalRows`
- `processedRows`
- `successRows`
- `errorRows`
- `errors`
- `startedAt`
- `finishedAt`
- `createdAt`
- `updatedAt`
- `deletedAt`
- `deletedBy`

### PlayerGuardian

- `id`
- `academyId`
- `playerId`
- `guardianId`
- `relationship`
- `isPrimary`
- `status`
- `auditTrail`

### Player Mapping

- Table: `players`
- `id`
- `academy_id`
- `document_type`
- `first_name`
- `last_name`
- `birth_date`
- `document_number`
- `email`
- `phone`
- `nationality`
- `gender`
- `federation_id`
- `dominant_foot`
- `category_id`
- `photo`
- `status`
- `audit_trail.*`
- `deleted_at`
- `deleted_by`

### PlayerGuardian Mapping

- Table: `player_guardians`
- `id`
- `academy_id`
- `player_id`
- `guardian_id`
- `is_primary`
- `audit_trail.*`
- `deleted_at`
- `deleted_by`

### PlayerGuardian Mapping

- Table: `player_guardians`
- `id`
- `academy_id`
- `player_id`
- `guardian_id`
- `is_primary`
- `audit_trail.*`
- `deleted_at`
- `deleted_by`

### PlayerImportJob Mapping

- Table: `player_import_jobs`
- `id`
- `academy_id`
- `created_by`
- `category_id`
- `original_file_name`
- `file_path`
- `status`
- `progress`
- `total_rows`
- `processed_rows`
- `success_rows`
- `error_rows`
- `errors`
- `started_at`
- `finished_at`
- `created_at`
- `updated_at`
- `deleted_at`
- `deleted_by`

## Contract Notes

- El listado expone `categoryName`, `genderName`, `age`, `photo` y `createdAt`.
- El detalle expone el perfil completo del jugador dentro del tenant.
- El filtro canónico para rangos de edad es `birthDateFrom` / `birthDateTo`.
- `categoryId` es el identificador funcional para edición y filtro.
- `categoryName` es un campo derivado de salida para consumo frontend.
- La foto usa el contrato de media compartido con `Academy`.

## Functional Guides

- Backlog epic: [`docs/backlog/epics/EP-007.md`](/home/juan-rodas/projects/playertech/playertech-api/docs/backlog/epics/EP-007.md)
- Player import flow: [`docs/flows/player/player-import-flow-spec.md`](/home/juan-rodas/projects/playertech/playertech-api/docs/flows/player/player-import-flow-spec.md)
- Player import UX: [`docs/flows/player/player-import-ux-spec.md`](/home/juan-rodas/projects/playertech/playertech-api/docs/flows/player/player-import-ux-spec.md)
