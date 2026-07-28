# Implementation Plan: Tenant Onboarding

**Branch**: `014-tenant-onboarding` | **Date**: 2026-07-27 | **Spec**: ./spec.md

**Input**: Feature specification from `./spec.md`

## Summary

Consolidate tenant signup, activation, source tracking and initial team creation
as the canonical onboarding feature for PlayerTech.

## Technical Context

**Language/Version**: PHP 8.4 / Symfony 7.4

**Primary Dependencies**: Symfony, Doctrine ORM, JWT, email delivery

**Storage**: MySQL 8+

**Testing**: PHPUnit

**Target Platform**: Linux containerized backend

**Project Type**: Web service

**Performance Goals**: Keep onboarding predictable and traceable.

**Constraints**: Must preserve tenant isolation and activation traceability.

**Scale/Scope**: Public tenant onboarding lifecycle.

## Constitution Check

- Must preserve activation traceability.
- Must keep signup and academy creation consistent.
- Must keep initial team creation tied to the tenant.

## Project Structure

### Documentation (this feature)

```text
specs/014-tenant-onboarding/
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
app/src/Modules/Academy/
app/src/Modules/Identity/
app/tests/Functional/Modules/Academy/
app/tests/Functional/Modules/Identity/
```

**Structure Decision**: Tenant onboarding is a cross-module feature but stays as a
single Spec Kit unit because it coordinates academy creation, activation and source tracking.

