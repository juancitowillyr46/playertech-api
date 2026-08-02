# Documentation Map

Este documento define la jerarquia SDD del proyecto para que cualquier persona pueda ubicar rapidamente donde vive cada definicion.

## Capas De Verdad

### 1. Contrato Base Y Guías Canonicas

Vive en `docs/` y `specs/14-current-state.md`.

- Arquitectura general
- Seguridad
- API base
- Base de datos
- Testing
- Orden de ejecución
- Estado actual
- Referencia HTTP
- Environment guide

### 2. Decisión Técnica Persistente

Vive en `docs/architecture/`.

- ADRs
- memorias persistentes
- auditorías
- políticas SDD
- mapas de documentación
- specs de evolución arquitectónica

### 3. Documento Central De Flujo

Vive en `docs/flows/`.

- define un flujo funcional concreto
- explica contrato, UX, estados y reglas del flujo
- tiene satélites que amplían, no redefinen

### 4. Backlog

Vive en `docs/backlog/`.

- define intención funcional
- define épicas e historias
- no debe duplicar el contrato vigente si ya existe un documento central

## Mapa Maestro Por Dominio

### Base Del Proyecto

| Tema | Documento |
| --- | --- |
| Arquitectura | [`docs/architecture/architecture-overview.md`](../../docs/architecture/architecture-overview.md) |
| Seguridad | [`docs/security/security-overview.md`](../../docs/security/security-overview.md) |
| API global | [`docs/contracts/api-principles.md`](../../docs/contracts/api-principles.md) |
| Base de datos | [`docs/database/database-standards.md`](../../docs/database/database-standards.md) |
| Migraciones | [`docs/database/migration-standards.md`](../../docs/database/migration-standards.md) |
| Testing | [`docs/architecture/guides/testing-strategy.md`](../../docs/architecture/guides/testing-strategy.md) |
| Execution order | [`docs/architecture/guides/execution-order-guide.md`](../../docs/architecture/guides/execution-order-guide.md) |
| Current state | [`specs/14-current-state.md`](../../specs/14-current-state.md) |
| API reference | [`docs/contracts/api-reference.md`](../../docs/contracts/api-reference.md) |
| Memory | [`docs/architecture/memory/project-memory.md`](./memory/project-memory.md) |

### Academy

| Rol | Documento |
| --- | --- |
| Central | [`docs/domains/academy/academy-domain-spec.md`](../domains/academy/academy-domain-spec.md) |
| Feature spec | [`specs/001-academy/spec.md`](../../specs/001-academy/spec.md) |
| Contexto | [`docs/backlog/epics/EP-001.md`](../backlog/epics/EP-001.md) |
| HUs | [`docs/backlog/stories/EP-001/`](../backlog/stories/EP-001/) |

### Identity

| Rol | Documento |
| --- | --- |
| Central actual | [`docs/security/security-overview.md`](../../docs/security/security-overview.md) + [`docs/contracts/api-reference.md`](../../docs/contracts/api-reference.md) |
| Documento central de dominio | [`docs/domains/identity/identity-domain-spec.md`](../domains/identity/identity-domain-spec.md) |
| Feature spec | [`specs/003-identity/spec.md`](../../specs/003-identity/spec.md) |
| Historia funcional | [`docs/backlog/epics/EP-003.md`](../backlog/epics/EP-003.md) |
| HUs | [`docs/backlog/stories/EP-003/`](../backlog/stories/EP-003/) |

### Category

| Rol | Documento |
| --- | --- |
| Central vigente | [`docs/contracts/api-reference.md`](../../docs/contracts/api-reference.md) |
| Documento central de dominio | [`docs/domains/category/category-domain-spec.md`](../domains/category/category-domain-spec.md) |
| Contexto funcional | [`docs/backlog/epics/EP-004.md`](../backlog/epics/EP-004.md) |
| HUs | [`docs/backlog/stories/EP-004/`](../backlog/stories/EP-004/) |

### Venue

| Rol | Documento |
| --- | --- |
| Central vigente | [`docs/contracts/api-reference.md`](../../docs/contracts/api-reference.md) |
| Documento central de dominio | [`docs/domains/venue/venue-domain-spec.md`](../domains/venue/venue-domain-spec.md) |
| Contexto funcional | [`docs/backlog/epics/EP-002.md`](../backlog/epics/EP-002.md) |
| HUs | [`docs/backlog/stories/EP-002/`](../backlog/stories/EP-002/) |

### Player

| Rol | Documento |
| --- | --- |
| Contrato operativo general | [`docs/contracts/api-reference.md`](../../docs/contracts/api-reference.md) |
| Feature spec | [`specs/007-player/spec.md`](../../specs/007-player/spec.md) |
| Documento central de dominio | [`docs/domains/player/player-domain-spec.md`](../domains/player/player-domain-spec.md) |
| Contexto funcional | [`docs/backlog/epics/EP-007.md`](../backlog/epics/EP-007.md) |
| HUs | [`docs/backlog/stories/EP-007/`](../backlog/stories/EP-007/) |

### Player Import Flow

| Rol | Documento |
| --- | --- |
| Flujo central | [`docs/flows/player/player-import-flow-spec.md`](../flows/player/player-import-flow-spec.md) |
| Satélite UX | [`docs/flows/player/player-import-ux-spec.md`](../flows/player/player-import-ux-spec.md) |
| Feature spec | [`specs/007-player/spec.md`](../../specs/007-player/spec.md) |
| Documento central de dominio | [`docs/domains/player/player-domain-spec.md`](../domains/player/player-domain-spec.md) |
| Contrato HTTP | [`docs/contracts/api-reference.md`](../../docs/contracts/api-reference.md) |
| Backlog origen | [`docs/backlog/stories/EP-007/HU-007-import-players-bulk.md`](../backlog/stories/EP-007/HU-007-import-players-bulk.md) |

### Guardian

| Rol | Documento |
| --- | --- |
| Central vigente | [`docs/contracts/api-reference.md`](../../docs/contracts/api-reference.md) |
| Documento central de dominio | [`docs/domains/guardian/guardian-domain-spec.md`](../domains/guardian/guardian-domain-spec.md) |
| Feature spec | [`specs/006-legal-guardian-management/spec.md`](../../specs/006-legal-guardian-management/spec.md) |
| Contexto funcional | [`docs/backlog/epics/EP-006.md`](../backlog/epics/EP-006.md) |
| HUs | [`docs/backlog/stories/EP-006/`](../backlog/stories/EP-006/) |

### Team

| Rol | Documento |
| --- | --- |
| Central vigente | [`docs/contracts/api-reference.md`](../../docs/contracts/api-reference.md) |
| Documento central de dominio | [`docs/domains/team/team-domain-spec.md`](../domains/team/team-domain-spec.md) |
| Feature spec | [`specs/005-team/spec.md`](../../specs/005-team/spec.md) |
| Contexto funcional | [`docs/backlog/epics/EP-005.md`](../backlog/epics/EP-005.md) |
| HUs | [`docs/backlog/stories/EP-005/`](../backlog/stories/EP-005/) |

### Membership / Billing

| Rol | Documento |
| --- | --- |
| Evolución de billing | [`docs/domains/billing/billing-evolution-notes.md`](../domains/billing/billing-evolution-notes.md) |
| Conceptos de pago | [`specs/011-payment-concept/spec.md`](../../specs/011-payment-concept/spec.md) |
| Plan financiero / cargos | [`specs/012-charge-payment/spec.md`](../../specs/012-charge-payment/spec.md) |
| Documento central de dominio | [`docs/domains/billing/billing-domain-spec.md`](../domains/billing/billing-domain-spec.md) |
| Modelo financiero | [`docs/domains/billing/financial-domain-model.md`](../../docs/domains/billing/financial-domain-model.md) |
| HUs | [`docs/backlog/stories/EP-009/`](../backlog/stories/EP-009/) |

### Staff

| Rol | Documento |
| --- | --- |
| Central vigente | [`docs/contracts/api-reference.md`](../../docs/contracts/api-reference.md) |
| Documento central de dominio | [`docs/domains/staff/staff-domain-spec.md`](../domains/staff/staff-domain-spec.md) |
| Feature spec | [`specs/021-staff/spec.md`](../../specs/021-staff/spec.md) |
| Contexto funcional | [`docs/backlog/epics/EP-021.md`](../backlog/epics/EP-021.md) |
| HUs | [`docs/backlog/stories/EP-021/`](../backlog/stories/EP-021/) |

### Shared

| Rol | Documento |
| --- | --- |
| Capa transversal | [`docs/domains/shared/shared-domain-spec.md`](../domains/shared/shared-domain-spec.md) |
| Base de VOs y tipos | [`docs/domains/shared/shared-domain-spec.md`](../domains/shared/shared-domain-spec.md) |
| Catálogo compartido de tipos de documento | [`specs/025-document-type-catalog/spec.md`](../../specs/025-document-type-catalog/spec.md) |
| Catálogo compartido de parentescos | [`specs/026-relationship-catalog/spec.md`](../../specs/026-relationship-catalog/spec.md) |

### Fiscal / Receipts

| Rol | Documento |
| --- | --- |
| Fiscal feature | [`specs/023-fiscal/spec.md`](../../specs/023-fiscal/spec.md) |
| Central vigente | [`docs/domains/billing/financial-domain-model.md`](../../docs/domains/billing/financial-domain-model.md) |
| Contexto funcional | [`docs/backlog/epics/EP-023.md`](../backlog/epics/EP-023.md) |
| HUs | [`docs/backlog/stories/EP-023/`](../backlog/stories/EP-023/) |


## Reglas De Lectura

1. Si buscas contrato vigente, lee `specs/`.
2. Si buscas historia o backlog, lee `docs/backlog/`.
3. Si buscas decisión técnica o memoria, lee `docs/architecture/`.
4. Si buscas un flujo funcional completo, lee su documento central en `docs/`.
5. Si hay conflicto entre un satélite y su documento central, manda el central.

## Autoridad Transversal

- Las decisiones arquitectónicas aceptadas en `docs/architecture/ADR-*.md` son obligatorias para nuevas features y refactors relevantes.
- La referencia HTTP operativa canónica es `docs/contracts/api-reference.md`; Postman refleja ese contrato para validación manual y QA.
- `docs/contracts/api-principles.md` define el marco general y no puede contradecir la referencia HTTP operativa.
- Un ADR o contrato en estado `Propuesto` expresa una alternativa pendiente, no una regla vigente.

## Reglas De Mantenimiento

- Un dominio no debe tener dos documentos centrales que compitan entre sí.
- Una UX no puede redefinir contrato.
- Una HU no debe duplicar el contrato ya definido.
- Si un contrato cambia, actualizar documento central, `docs/contracts/api-reference.md` y `docs/architecture/memory/project-memory.md` si aplica.
- Si una decisión técnica cambia, documentarla en `docs/architecture/`.
