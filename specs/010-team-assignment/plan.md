# Implementation Plan: Player Team Assignment

**Branch**: `010-team-assignment` | **Date**: 2026-07-27 | **Spec**: ./spec.md

**Entrada**: Feature specification from `./spec.md`

## Resumen

Consolidate player-to-team assignment and primary team handling as the canonical competitive participation feature, including the team autocomplete selector.

## Contexto técnico

**Language/Version**: PHP 8.4 / Symfony 7.4

**Primary Dependencies**: Symfony, Doctrine ORM, JWT

**Storage**: MySQL 8+

**Testing**: PHPUnit

**Target Platform**: Linux containerized backend

**Project Type**: Web service

**Performance Objectives**: Keep assignment operations predictable.

**Constraints**: Debe preservar tenant isolation, history y primary assignment integrity.

**Scale/Scope**: Player-team assignment lifecycle inside the academy.

## Constitution Check

- Debe preservar history y avoid destructive assignment behavior.
- Debe keep one active primary assignment per player.
- Debe keep scope tenant explicit.
- Debe proveer un selector liviano de equipos activos para autocomplete.

## Estructura del proyecto

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

**Structure Decision**: TeamAssignment stays as a separate relational feature because it encodes historical behavior, primary-state rules y catálogo de selección liviana.
