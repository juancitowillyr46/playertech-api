# Implementation Plan: Payment Concept

**Branch**: `011-payment-concept` | **Date**: 2026-07-27 | **Spec**: ./spec.md

**Input**: Feature specification from `./spec.md`

## Summary

Consolidate payment concept administration, code generation and lifecycle
control as the canonical catalog feature for financial operations.

## Technical Context

**Language/Version**: PHP 8.4 / Symfony 7.4

**Primary Dependencies**: Symfony, Doctrine ORM, validation, JWT

**Storage**: MySQL 8+

**Testing**: PHPUnit

**Target Platform**: Linux containerized backend

**Project Type**: Web service

**Performance Goals**: Keep catalog operations fast and predictable.

**Constraints**: Must preserve tenant isolation and code immutability.

**Scale/Scope**: Academy payment concept catalog.

## Constitution Check

- Must preserve tenant isolation.
- Must generate concept codes in backend only.
- Must keep deactivation history-safe.

## Project Structure

### Documentation (this feature)

```text
specs/011-payment-concept/
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
app/src/Modules/PaymentConcept/
app/tests/Functional/Modules/PaymentConcept/
app/tests/Unit/Modules/PaymentConcept/
```

**Structure Decision**: Payment concept is an independent catalog feature
because its business rules and lifecycle are distinct from charges and payments.

