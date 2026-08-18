# Spec Kit Structure For PlayerTech

Este documento fija la estructura objetivo del proyecto backend bajo GitHub Spec Kit.

## Canonical Layers

### 1. Project Constitution

Ruta:

- `.specify/memory/constitution.md`

Propósito:

- principios SDD;
- reglas de gobernanza;
- criterios de cambio;
- restricciones de trabajo.

### 2. Feature Specifications

Ruta:

- `specs/[###-feature-name]/`

Contenido esperado:

- `spec.md`
- `plan.md`
- `research.md`
- `data-model.md`
- `quickstart.md`
- `contracts/`
- `tasks.md`

Regla:

- cada feature importante debe vivir en su propia carpeta de spec kit;
- la carpeta usa un identificador estable y legible;
- no debe mezclarse con documentación general del proyecto.

### 3. Canonical Global Specs

Ruta:

- `specs/`

Propósito:

- arquitectura general;
- seguridad;
- API base;
- base de datos;
- testing;
- execution order;
- current state;
- reference HTTP.

Regla:

- estos archivos son el canon transversal del backend;
- no deben duplicarse en `docs/` salvo como índice o legado explícito.

### 4. Product And Functional Context

Ruta:

- `docs/product/`
- `docs/backlog/`

Propósito:

- visión;
- alcance;
- roadmap;
- épicas;
- historias de usuario;
- priorización.

Regla:

- backlog y producto describen intención;
- `specs/` describe contrato y reglas vigentes;
- no se reescribe una HU como si fuera contrato.

### 5. Architecture Memory And Audit

Ruta:

- `docs/architecture/`
- `docs/audit/`
- `docs/traceability/`

Propósito:

- ADRs;
- memoria persistente;
- reportes de migración;
- matriz de trazabilidad;
- políticas SDD;
- cambios estructurales.

### 6. Domain And Flow Docs

Ruta:

- `docs/domains/`
- `docs/flows/`

Propósito:

- `docs/domains/`: dominio puro;
- `docs/flows/`: flujos funcionales específicos.

Regla:

- un flujo funcional no debe competir con el dominio puro;
- la UX de un flujo referencia el flujo central;
- si el flujo madura, su contrato real debe vivir en `specs/[###-feature]/`.

### 7. Feature Coverage Examples

Algunos features ya normalizados bajo esta estructura son:

- `specs/001-academy/`
- `specs/003-identity/`
- `specs/004-category/`
- `specs/005-team/`
- `specs/006-legal-guardian-management/`
- `specs/007-player-management/`
- `specs/008-player-guardian/`
- `specs/009-membership-management/`
- `specs/010-team-assignment/`
- `specs/011-payment-concept/`
- `specs/012-charge-payment/`
- `specs/013-dashboard/`
- `specs/014-tenant-onboarding/`
- `specs/021-staff/`
- `specs/023-fiscal/`

### 8. Operational Support

Ruta:

- `docs/contracts/`
- `docs/database/`
- `docs/operations/`

Propósito:

- índice operativo de contratos;
- modelo y relaciones de base de datos;
- setup y operación local.

## Feature Folder Standard

Una carpeta de feature en Spec Kit debe seguir esta forma:

```text
specs/[###-feature-name]/
├── spec.md
├── plan.md
├── research.md
├── data-model.md
├── quickstart.md
├── contracts/
└── tasks.md
```

## How PlayerTech Should Use It

### Backend feature workflow

1. Backlog define la intención.
2. `specs/[###-feature]/spec.md` define la necesidad verificable.
3. `plan.md` define el enfoque técnico.
4. `tasks.md` descompone el trabajo ejecutable.
5. La implementación vive en `app/`.
6. `specs/14-current-state.md` registra el cierre.
7. `docs/architecture/memory/project-memory.md` conserva las decisiones relevantes.

### Existing documentation rule

- lo histórico no se borra;
- lo duplicado se marca;
- lo canónico se deja explícito;
- lo desalineado se archiva o se reconcilia.

## Recommended Repository View

```text
.specify/
├── memory/
├── templates/
├── scripts/
└── workflows/

specs/
├── 00-product.md
├── 01-arquitecture.md
├── 02-domains.md
├── 03-security.md
├── 04-api.md
├── 06-database.md
├── 08-dev-standards.md
├── 09-roadmap.md
├── 10-project-setup.md
├── 11-testing-strategy.md
├── 12-execution-order.md
├── 13-user-story-rebuild-guide.md
├── 14-current-state.md
├── 15-module-creation-guide.md
├── 16-api-reference.md
├── 17-environment-guide.md
├── 18-financial-domain-model.md
├── 19-observability-local.md
└── [###-feature-name]/

docs/
├── product/
├── backlog/
├── architecture/
├── contracts/
├── database/
├── operations/
├── domains/
├── flows/
├── audit/
└── traceability/
```

## Governance

- `specs/` manda sobre `docs/` cuando existe conflicto de contrato.
- `docs/architecture/` manda sobre `docs/` en decisiones técnicas persistentes.
- `docs/backlog/` manda sobre intención, no sobre contrato.
- `docs/audit/` y `docs/traceability/` registran la evolución y la cobertura.
- cualquier feature nueva debe nacer en Spec Kit antes de implementarse.
