# Plan de Implementación: Legal Guardian Management

**Branch**: `006-legal-guardian-management` | **Date**: 2026-07-27 | **Spec**: ./spec.md

**Entrada**: Feature specification from `./spec.md`

## Resumen

Consolidar el ciclo de vida de acudientes legales como la entidad canónica de contacto reutilizable para flujos de jugadores, membresías y cobros.

## Contexto técnico

**Language/Version**: PHP 8.4 / Symfony 7.4

**Primary Dependencies**: Symforny, Doctrine ORM, JWT

**Storage**: MySQL 8+

**Testing**: PHPUnit

**Target Platform**: Linux containerized backend

**Project Type**: Web service

**Performance Objectives**: Keep guardian CRUD and listing flows predictable.

**Constraints**: Preserve tenant isolation and support downstream relation use cases.

**Scale/Complexity**: Guardian lifecycle is reused across player, membership and payment flows.

## Constitution Check

- El alcance debe respetar tenant isolation.
- El agregado `LegalGuardian` debe conservar datos de contacto útiles para flujos downstream.
- La gestión del acudiente no debe mezclar reglas de relación jugador-acudiente.
- Los contratos HTTP deben alinearse con el envelope y la paginación vigentes.
- Toda HU verificable debe tener contrato y tarea trazable.

## Estructura del proyecto

### Documentation (this feature)

```text
specs/006-legal-guardian-management/
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

**Structure Decision**: `LegalGuardian` remains a standalone feature because it is a reusable responsible-person entity referenced by several backend flows.

## Estrategia de Implementación

### Fase 1: Cierre documental
- Verificar que `spec.md`, `contracts/`, `data-model.md`, `quickstart.md` y `research.md` describan el alcance completo de las 6 HUs.
- Confirmar que `specs/14-current-state.md` y `docs/backlog/epics/EP-006-legal-guardian-management.md` coincidan con el alcance documentado.

### Fase 2: Contratos y capa HTTP
- Implementar y validar el contrato de actualización del acudiente.
- Implementar y validar los endpoints de inactivación y reactivación.
- Mantener el create/list/show ya existentes como base estable.

### Fase 3: Aplicación y dominio
- Agregar commands, handlers y requests necesarios para update/inactivate/reactivate.
- Reutilizar el agregado `LegalGuardian` y extenderlo sólo si el dominio lo requiere.
- Mantener la validación en la capa HTTP y la lógica de negocio en aplicación/dominio.

### Fase 4: Persistencia y pruebas
- Ajustar repository o mapping sólo si alguna HU nueva lo requiere.
- Cubrir las nuevas operaciones con pruebas unitarias.
- Agregar pruebas funcionales si se introduce nueva superficie HTTP.

### Fase 5: Trazabilidad final
- Registrar el avance en `specs/14-current-state.md`.
- Marcar tareas completadas conforme cada endpoint y caso de uso quede verificado.
