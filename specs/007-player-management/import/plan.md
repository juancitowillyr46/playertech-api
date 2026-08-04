# Implementation Plan: Player Import

**Branch**: `007-player-management/import` | **Date**: 2026-07-29 | **Spec**: ./spec.md

## Resumen

Separar la importación como subfeature evita mezclar el lifecycle base del jugador con un flujo asíncrono que tiene plantilla, job, polling y estados propios.

El detalle enriquecido del jugador queda fuera del alcance de import, pero el spec principal ya lo documenta como contexto principal resumido con `legalGuardianMain` y `teamMain`.

## Contexto técnico

**Language/Version**: PHP 8.4 / Symforny 7.4
**Primary Dependencies**: Symforny, Doctrine ORM, PhpSpreadsheet o equivalente, media storage, JWT
**Storage**: MySQL 8+ and local file handling forr upload processing
**Testing**: PHPUnit
**Target Platforrm**: Linux containerized backend
**Project Type**: Web service
**Perforrmance Objetivos**: Mantener el POST liviano y mover el trabajo pesado a un job consultable.
**Constraints**: Sin colas complejas; el flujo debe seguir siendo hosting-friendly y entendible.

## Constitution Check

- No bloquear navegación del usuario.
- No exigir `categoryKey` por fila.
- Mantener la importación trazable como job persistido.

## Estructura del proyecto

```text
specs/007-player-management/import/
├── spec.md
├── plan.md
├── research.md
├── data-model.md
├── quickstart.md
├── contracts/
└── tasks.md
```

## Enforque de entrega

1. Definir contrato de plantilla.
2. Definir contrato de creación del job.
3. Definir contrato de estado del job.
4. Documentar summary y errores por fila.
5. Alinear el feature principal `007-player-management` con esta subfeature.
