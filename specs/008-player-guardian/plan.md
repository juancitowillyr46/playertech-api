# Implementation Plan: PlayerGuardian

**Branch**: `008-player-guardian` | **Date**: 2026-07-27 | **Spec**: ./spec.md

**Input**: Feature specification from `./spec.md`

## Summary

Consolidate player-guardian relations and primary guardian handling as the
canonical administrative relation feature for PlayerTech.

## Technical Context

**Language/Version**: PHP 8.4 / Symfony 7.4

**Primary Dependencies**: Symfony, Doctrine ORM, JWT

**Storage**: MySQL 8+

**Testing**: PHPUnit

**Target Platform**: Linux containerized backend

**Project Type**: Web service

**Performance Goals**: Keep relation management predictable and auditable.

**Constraints**: Must preserve relation history and primary guardian integrity.

**Scale/Scope**: Player-guardian relation lifecycle.

## Constitution Check

- Must keep exactly one active primary guardian.
- Must preserve relation history.
- Must keep tenant isolation explicit.

## Project Structure

### Documentation (this feature)

```text
specs/008-player-guardian/
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
app/src/Modules/PlayerGuardian/
app/tests/Functional/Modules/PlayerGuardian/
app/tests/Unit/Modules/PlayerGuardian/
```

**Structure Decision**: PlayerGuardian remains a dedicated relational feature
because it enforces primary-state rules and historical association behavior.

