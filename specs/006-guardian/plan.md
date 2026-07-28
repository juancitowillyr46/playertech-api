# Implementation Plan: Guardian

**Branch**: `006-guardian` | **Date**: 2026-07-27 | **Spec**: ./spec.md

**Input**: Feature specification from `./spec.md`

## Summary

Consolidate guardian lifecycle as the canonical responsible-person feature for PlayerTech.

## Technical Context

**Language/Version**: PHP 8.4 / Symfony 7.4

**Primary Dependencies**: Symfony, Doctrine ORM, JWT

**Storage**: MySQL 8+

**Testing**: PHPUnit

**Target Platform**: Linux containerized backend

**Project Type**: Web service

**Performance Goals**: Keep guardian CRUD and list flows predictable.

**Constraints**: Must preserve tenant isolation and downstream relation support.

**Scale/Scope**: Guardian lifecycle used across sports and financial flows.

## Constitution Check

- Must keep guardian data tenant-scoped.
- Must preserve contact data for downstream flows.
- Must not mix guardian lifecycle with player relation rules.

## Project Structure

### Documentation (this feature)

```text
specs/006-guardian/
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
app/src/Modules/Guardian/
app/tests/Functional/Modules/Guardian/
app/tests/Unit/Modules/Guardian/
```

**Structure Decision**: Guardian remains a standalone feature because it is a
reusable responsible-person entity referenced by several other backend flows.

