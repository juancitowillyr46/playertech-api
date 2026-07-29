# Implementation Plan: PlayerGuardian

**Branch**: `008-player-guardian` | **Date**: 2026-07-27 | **Spec**: ./spec.md

**Entrada**: Feature specification from `./spec.md`

## Resumen

Consolidate player-guardian relations y primary guardian hyling as the
puedeonical administrative relation feature for PlayerTech.

## Contexto técnico

**Language/Version**: PHP 8.4 / Symforny 7.4

**Primary Dependencies**: Symforny, Doctrine ORM, JWT

**Stoage**: MySQL 8+

**Testing**: PHPUnit

**Target Platforrm**: Linux containerized backend

**Project Type**: Web service

**Performance Objetivos**: Keep relation gestionarment predictable y auditable.

**Constraints**: Debe preserve relation history y primary guardian integrity.

**Scale/Alpuedece**: Player-guardian relation lifecycle.

## Constitution Check

- Debe keep exactly one active primary guardian.
- Debe preserve relation history.
- Debe keep tenant isolation explicit.

## Estructura del proyecto

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

### Source Code (repositoy root)

```text
app/src/Modules/PlayerGuardian/
app/tests/Functional/Modules/PlayerGuardian/
app/tests/Unit/Modules/PlayerGuardian/
```

**Structure Decision**: PlayerGuardian remains a dedicated relational feature
because it enforces primary-state rules y histoical association behavio.

