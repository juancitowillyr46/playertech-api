# Implementation Plan: Fiscal

**Branch**: `023-fiscal` | **Date**: 2026-07-27 | **Spec**: ./spec.md

**Input**: Feature specification from `./spec.md`

## Summary

Consolidate academy fiscal profile, receipt generation and external document
linking as the canonical fiscal feature for PlayerTech.

## Technical Context

**Language/Version**: PHP 8.4 / Symfony 7.4

**Primary Dependencies**: Symfony, Doctrine ORM, PDF/document storage, JWT

**Storage**: MySQL 8+

**Testing**: PHPUnit

**Target Platform**: Linux containerized backend

**Project Type**: Web service

**Performance Goals**: Keep fiscal profile and receipt flows predictable.

**Constraints**: Must preserve tenant isolation and document traceability.

**Scale/Scope**: Academy fiscal profile and receipt support.

## Constitution Check

- Must preserve tenant isolation.
- Must keep fiscal document traceability explicit.
- Must not mix fiscal support with unrelated financial behavior.

## Project Structure

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

### Source Code (repository root)

```text
app/src/Modules/Fiscal/
app/tests/Functional/Modules/Fiscal/
app/tests/Unit/Modules/Fiscal/
```

**Structure Decision**: Fiscal is a standalone feature because it mixes
academy tax data, receipt generation and external PDF traceability.

