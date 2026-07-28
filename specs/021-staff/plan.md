# Implementation Plan: Staff

**Branch**: `021-staff` | **Date**: 2026-07-27 | **Spec**: ./spec.md

**Input**: Feature specification from `./spec.md`

## Summary

Consolidate staff onboarding, technical roles and team assignment as the canonical
staff feature for PlayerTech.

## Technical Context

**Language/Version**: PHP 8.4 / Symfony 7.4

**Primary Dependencies**: Symfony, Doctrine ORM, JWT, identity and team modules

**Storage**: MySQL 8+

**Testing**: PHPUnit

**Target Platform**: Linux containerized backend

**Project Type**: Web service

**Performance Goals**: Keep invitation and assignment flows predictable.

**Constraints**: Must preserve academy scope and technical role traceability.

**Scale/Scope**: Staff lifecycle and team assignment features.

## Constitution Check

- Must preserve academy scope.
- Must keep invitation and activation traceable.
- Must keep technical roles explicit.

## Project Structure

### Documentation (this feature)

```text
specs/021-staff/
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
app/src/Modules/Staff/
app/tests/Functional/Modules/Staff/
app/tests/Unit/Modules/Staff/
```

**Structure Decision**: Staff remains a separate feature because it is a
technical-operations layer that depends on identity and team modules.

