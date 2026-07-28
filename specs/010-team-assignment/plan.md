# Implementation Plan: Team Assignment

**Branch**: `010-team-assignment` | **Date**: 2026-07-27 | **Spec**: ./spec.md

**Input**: Feature specification from `./spec.md`

## Summary

Consolidate player-to-team assignment and primary team handling as the canonical
competitive participation feature.

## Technical Context

**Language/Version**: PHP 8.4 / Symfony 7.4

**Primary Dependencies**: Symfony, Doctrine ORM, JWT

**Storage**: MySQL 8+

**Testing**: PHPUnit

**Target Platform**: Linux containerized backend

**Project Type**: Web service

**Performance Goals**: Keep assignment operations predictable.

**Constraints**: Must preserve tenant isolation, history and primary assignment integrity.

**Scale/Scope**: Player-team assignment lifecycle inside the academy.

## Constitution Check

- Must preserve history and avoid destructive assignment behavior.
- Must keep one active primary assignment per player.
- Must keep tenant scope explicit.

## Project Structure

### Documentation (this feature)

```text
specs/010-team-assignment/
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
app/src/Modules/TeamAssignment/
app/tests/Functional/Modules/TeamAssignment/
app/tests/Unit/Modules/TeamAssignment/
```

**Structure Decision**: TeamAssignment stays as a separate relational feature
because it encodes historical behavior and primary-state rules.

