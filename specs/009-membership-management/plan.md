# Implementation Plan: Membership

**Branch**: `009-membership-management` | **Date**: 2026-07-27 | **Spec**: ./spec.md

**Entrada**: Feature specification from `./spec.md`

## Resumen

Consolidate membership lifecycle as the canonical administrative enrollment
feature for PlayerTech, while preserving the documented boundary with the
financial block and extending the contract to capture enrollment category and
responsible guardian explicitly.

## Contexto técnico

**Language/Version**: PHP 8.4 / Symforny 7.4

**Primary Dependencies**: Symfony, Doctrine ORM, JWT, Category module, financial domain models

**Storage**: MySQL 8+

**Testing**: PHPUnit

**Target Platform**: Linux containerized backend

**Project Type**: Web service

**Performance Objectives**: Keep membership queries and lifecycle transitions predictable.

**Constraints**: Debe preserve history, scope tenant, keep financial linkage documented but external to the core lifecycle, and persist the enrollment category as historical context.

**Scale/Scope**: Academy membership lifecycle with documented financial boundary and explicit enrollment contract.

## Constitution Check

- Debe preserve history y avoid destructive membership transitions.
- Debe keep the financial block referenced but not embedded in the administrative lifecycle.
- Debe keep tenant isolation explicit.
- Debe treat `responsibleGuardianId` as the guardian selected at enrollment time.
- Debe persist `categoryId` as the category snapshot of the membership creation moment.

## Estructura del proyecto

### Documentation (this feature)

```text
specs/009-membership-management/
├── spec.md
├── plan.md
├── research.md
├── data-model.md
├── quickstart.md
├── contracts/
└── tasks.md
```

### Source Code (repositoy root)

```text
app/src/Modules/Membership/
app/tests/Functional/Modules/Membership/
app/tests/Unit/Modules/Membership/
```

**Structure Decision**: Membership remains the administrative enrollment feature
and keeps an explicit boundary with the financial lifecycle so charges, payments
and balance can be integrated later without changing the core membership contract.

## Implementation Notes

- `POST /api/v1/academy/memberships` must accept `playerId`, `responsibleGuardianId` and `categoryId`.
- `Membership` entity must persist the enrollment category and the responsible guardian reference.
- The response must expose the same explicit fields so the frontend does not infer data from the player aggregate.
- The financial block remains a documented integration boundary only.
