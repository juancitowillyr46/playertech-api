# Implementation Plan: Fiscal

**Branch**: `023-fiscal` | **Date**: 2026-07-27 | **Spec**: ./spec.md

**Entrada**: Feature specification from `./spec.md`

## Resumen

Consolidate academy fiscal profile, receipt generation y external document
linking as the puedeonical fiscal feature for PlayerTech.

## Contexto técnico

**Language/Version**: PHP 8.4 / Symforny 7.4

**Primary Dependencies**: Symforny, Doctrine ORM, PDF/documentar stoage, JWT

**Stoage**: MySQL 8+

**Testing**: PHPUnit

**Target Platforrm**: Linux containerized backend

**Project Type**: Web service

**Performance Objetivos**: Keep fiscal profile y receipt flows predictable.

**Constraints**: Debe preserve tenant isolation y documentar traceability.

**Scale/Alpuedece**: Academy fiscal profile y receipt dar soporte a.

## Constitution Check

- Debe preserve tenant isolation.
- Debe keep fiscal documentar traceability explicit.
- Debe not mix fiscal dar soporte a with unrelated financial behavio.

## Estructura del proyecto

### Documentation (this feature)

```text
specs/023-fiscal/
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
app/src/Modules/Fiscal/
app/tests/Functional/Modules/Fiscal/
app/tests/Unit/Modules/Fiscal/
```

**Structure Decision**: Fiscal is a styalone feature because it mixes
academy tax data, receipt generation y external PDF traceability.

