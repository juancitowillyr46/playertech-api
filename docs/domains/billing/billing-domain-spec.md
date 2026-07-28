# Billing Domain Spec

## Purpose

Documento central para el bloque financiero y de cobros.

## Canonical Sources

- Modelo financiero: [`specs/18-financial-domain-model.md`](/home/juan-rodas/projects/playertech/playertech-api/specs/18-financial-domain-model.md)
- API operativa: [`specs/16-api-reference.md`](/home/juan-rodas/projects/playertech/playertech-api/specs/16-api-reference.md)
- Memoria persistente: [`docs/architecture/memory/project-memory.md`](/home/juan-rodas/projects/playertech/playertech-api/docs/architecture/memory/project-memory.md)
- Evolución de dominio: [`docs/domains/billing/billing-evolution-notes.md`](/home/juan-rodas/projects/playertech/playertech-api/docs/domains/billing/billing-evolution-notes.md)

## Scope

- membership
- payment concepts
- charges
- payments
- allocations
- evidence attachments
- dashboard financial views
- fiscal profiles and receipts

## Contract Notes

- `Membership` cubre matrícula activa e historial y puede disparar cargos automáticos al crear la matrícula.
- `PaymentConcept` administra el catálogo reutilizable de conceptos de cobro.
- `Charge` modela deuda concreta.
- `Payment` registra el recaudo y sus allocations sobre cargos existentes.
- `Dashboard` consume agregados del bloque financiero para KPIs operativos.
- `Fiscal` cubre perfil fiscal operativo y recibos.

## Boundary Rules

- `Membership` pertenece al dominio administrativo de matrícula.
- `Membership` no registra pagos ni reconciliaciones.
- `Charge` representa la deuda concreta y puede nacer de matrícula o de carga manual.
- `Payment` no redefine reglas de matrícula; sólo aplica recaudo sobre cargos existentes.
- `PaymentAllocation` distribuye el pago entre cargos.
- `PaymentConcept` sigue siendo el catálogo reusable que motiva el cobro.

## Domain Model

### Membership

- `id`
- `academyId`
- `playerId`
- `primaryGuardianId`
- `status`
- `startedAt`
- `endedAt`
- `auditTrail`
- `deletedAt`
- `deletedBy`

### PaymentConcept

- `id`
- `academyId`
- `code`
- `name`
- `description`
- `status`
- `auditTrail`
- `deletedAt`
- `deletedBy`

### Charge

- `id`
- `academyId`
- `playerId`
- `membershipId`
- `paymentConceptId`
- `description`
- `amount`
- `allocatedAmount`
- `dueDate`
- `source`
- `status`
- `auditTrail`
- `deletedAt`
- `deletedBy`

### Payment

- `id`
- `academyId`
- `membershipId`
- `playerId`
- `guardianId`
- `paymentConceptId`
- `paymentDate`
- `amount`
- `method`
- `notes`
- `allocations`
- `status`
- `auditTrail`
- `deletedAt`
- `deletedBy`

### FiscalAttachment

- `id`
- `academyId`
- `paymentId`
- `fileName`
- `filePath`
- `mimeType`
- `size`
- `checksum`

### PaymentEvidence

- `id`
- `paymentId`
- `path`
- `url`
- `mimeType`
- `size`
- `checksum`

### Membership Mapping

- Table: `memberships`
- `id`
- `academy_id`
- `player_id`
- `primary_guardian_id`
- `status`
- `started_at`
- `ended_at`
- `audit_trail.*`
- `deleted_at`
- `deleted_by`

### PaymentConcept Mapping

- Table: `payment_concepts`
- `id`
- `academy_id`
- `code`
- `name`
- `description`
- `status`
- `audit_trail.*`
- `deleted_at`
- `deleted_by`

### Charge Mapping

- Table: `charges`
- `id`
- `academy_id`
- `player_id`
- `membership_id`
- `payment_concept_id`
- `description`
- `amount`
- `allocated_amount`
- `due_date`
- `source`
- `status`
- `audit_trail.*`
- `deleted_at`
- `deleted_by`

### Payment Mapping

- Table: `payments`
- `id`
- `academy_id`
- `membership_id`
- `player_id`
- `guardian_id`
- `payment_concept_id`
- `payment_date`
- `amount`
- `method`
- `notes`
- `status`
- `audit_trail.*`
- `deleted_at`
- `deleted_by`

### PaymentAllocation Mapping

- Table: `payment_allocations`
- `id`
- `academy_id`
- `payment_id`
- `charge_id`
- `amount`
- `audit_trail.*`
- `deleted_at`
- `deleted_by`

### PaymentEvidence Mapping

- Table: `payment_evidences`
- `id`
- `academy_id`
- `payment_id`
- `file_name`
- `file_path`
- `mime_type`
- `audit_trail.*`
- `deleted_at`
- `deleted_by`

### FiscalAttachment Mapping

- Table: `fiscal_attachments`
- `id`
- `academy_id`
- `payment_id`
- `provider_name`
- `document_number`
- `document_url`
- `status`
- `audit_trail.*`
- `deleted_at`
- `deleted_by`

## Functional Guides

- Backlog epic: [`docs/backlog/epics/EP-009.md`](/home/juan-rodas/projects/playertech/playertech-api/docs/backlog/epics/EP-009.md)
- Payment concepts: [`docs/backlog/epics/EP-011.md`](/home/juan-rodas/projects/playertech/playertech-api/docs/backlog/epics/EP-011.md)
- Charges and payments: [`docs/backlog/epics/EP-012.md`](/home/juan-rodas/projects/playertech/playertech-api/docs/backlog/epics/EP-012.md)
- Dashboard: [`docs/backlog/epics/EP-013.md`](/home/juan-rodas/projects/playertech/playertech-api/docs/backlog/epics/EP-013.md)
- Fiscal profiles and receipts: [`docs/backlog/epics/EP-023.md`](/home/juan-rodas/projects/playertech/playertech-api/docs/backlog/epics/EP-023.md)
