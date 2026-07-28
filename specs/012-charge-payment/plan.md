# Implementation Plan: Charge & Payment

**Branch**: `012-charge-payment` | **Date**: 2026-07-27 | **Spec**: ./spec.md

**Input**: Feature specification from `./spec.md`

## Summary

Consolidate the financial flow around charges, payments, evidence and history as
the canonical payment feature for PlayerTech.

## Technical Context

**Language/Version**: PHP 8.4 / Symfony 7.4

**Primary Dependencies**: Symfony, Doctrine ORM, media storage, financial models

**Storage**: MySQL 8+

**Testing**: PHPUnit

**Target Platform**: Linux containerized backend

**Project Type**: Web service

**Performance Goals**: Keep financial consultation and registration predictable.

**Constraints**: Must preserve auditable history and financial linkage.

**Scale/Scope**: Charges, payments and evidence flows for the academy tenant.

## Constitution Check

- Must preserve auditability and non-destructive history.
- Must keep financial records tenant-scoped.
- Must keep evidence attachments explicit and traceable.

## Project Structure

### Documentation (this feature)

```text
specs/012-charge-payment/
├── spec.md
├── plan.md
├── research.md
├── data-model.md
├── quickstart.md
├── contracts/
└── tasks.md
```

### Source Code (repository root)

```text
app/src/Modules/Payment/
app/src/Modules/Charge/
app/tests/Functional/Modules/Payment/
app/tests/Unit/Modules/Payment/
```

**Structure Decision**: Charges and payments are treated as one financial
feature because the business rules and contract references are tightly coupled.

