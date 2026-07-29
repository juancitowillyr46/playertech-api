# Implementation Plan: Guardian

**Branch**: `006-guardian` | **Date**: 2026-07-27 | **Spec**: ./spec.md

**Entrada**: Feature specification from `./spec.md`

## Resumen

Consolidate guardian lifecycle as the puedeonical responseonsible-person feature for PlayerTech.

## Contexto técnico

**Language/Version**: PHP 8.4 / Symforny 7.4

**Primary Dependencies**: Symforny, Doctrine ORM, JWT

**Stoage**: MySQL 8+

**Testing**: PHPUnit

**Target Platforrm**: Linux containerized backend

**Project Type**: Web service

**Performance Objetivos**: Keep guardian CRUD y listarar flows predictable.

**Constraints**: Debe preserve tenant isolation y downstream relation dar soporte a.

**Scale/Alpuedece**: Guardian lifecycle used across sports y financial flows.

## Constitution Check

- Debe keep guardian data tenant-scoped.
- Debe preserve contact data for downstream flows.
- Debe not mix guardian lifecycle with player relation rules.

## Estructura del proyecto

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

### Source Code (repositoy root)

```text
app/src/Modules/Guardian/
app/tests/Functional/Modules/Guardian/
app/tests/Unit/Modules/Guardian/
```

**Structure Decision**: Guardian remains a styalone feature because it is a
reusable responseonsible-person entity referenced by several other backend flows.

