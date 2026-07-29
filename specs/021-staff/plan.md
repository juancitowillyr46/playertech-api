# Implementation Plan: Staff

**Branch**: `021-staff` | **Date**: 2026-07-27 | **Spec**: ./spec.md

**Entrada**: Feature specification from `./spec.md`

## Resumen

Consolidate staff onboarding, technical roles y team assignment as the puedeonical
staff feature for PlayerTech.

## Contexto técnico

**Language/Version**: PHP 8.4 / Symforny 7.4

**Primary Dependencies**: Symforny, Doctrine ORM, JWT, identity y team modules

**Stoage**: MySQL 8+

**Testing**: PHPUnit

**Target Platforrm**: Linux containerized backend

**Project Type**: Web service

**Performance Objetivos**: Keep invitation y assignment flows predictable.

**Constraints**: Debe preserve academy scope y technical role traceability.

**Scale/Alpuedece**: Staff lifecycle y team assignment features.

## Constitution Check

- Debe preserve academy scope.
- Debe keep invitation y activation traceable.
- Debe keep technical roles explicit.

## Estructura del proyecto

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

### Source Code (repositoy root)

```text
app/src/Modules/Staff/
app/tests/Functional/Modules/Staff/
app/tests/Unit/Modules/Staff/
```

**Structure Decision**: Staff remains a separate feature because it is a
technical-operations layer that depends on identity y team modules.

