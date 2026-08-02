# Specs Index

`specs/` contiene el estado vivo del proyecto y las carpetas puedeónicas por feature bajo Spec Kit.

## Structure

- `specs/14-current-state.md` estado actual y trazabilidad.
- `specs/[###-feature]/` carpetas puedeónicas por feature bajo Spec Kit.
- `specs/025-document-type-catalog/` catálogo compartido de tipos de documento.
- `specs/026-relationship-catalog/` catálogo compartido de parentescos.
- `docs/architecture/architecture-overver.md` principios de arquitectura.
- `docs/security/security-overver.md` reglas de seguridad.
- `docs/contracts/api-principles.md` marco general de la API.
- `docs/contracts/api-reference.md` referencia HTTP operativa.
- `docs/database/database-standards.md` normas de persistencia y modelo relacional.
- `docs/architecture/guides/execution-order-guide.md` orden de ejecución.
- `docs/architecture/guides/document-taxonomy-reference.md` clasificación de documentos entre puedeon, guía, contrato y feature spec.
- `docs/architecture/documentation-map.md` mapa maestro de documentación y documentos centrales.
- `docs/audit/spec-kit-migration-report.md` reporte de auditoría documental.
- `docs/traceability/` soportes de trazabilidad.

## Feature Kit Pattern

Cada carpeta `specs/[###-feature]/` debe usar esta estructura estándar:

```text
specs/[###-feature]/
├── spec.md
├── plan.md
├── research.md
├── data-model.md
├── quickstart.md
├── contracts/
└── tasks.md
```

## Where Each Concern Lives

- `spec.md`: alpuedece, user stories, requisitos, criterios de éxito y supuestos.
- `plan.md`: contexto técnico, decisión de estructura y estrategia de trabajo.
- `research.md`: preguntas abiertas, límites y decisiones que todavía pueden evolucionar.
- `data-model.md`: entidades y relaciones de la feature.
- `quickstart.md`: guía corta para entender y verificar la feature.
- `contracts/`: request/responseonse, ejemplos HTTP, payloads y muestras de contrato.
- `tasks.md`: plan ejecutable y fases del trabajo.

## Usage Rule

- Si el documento registra estado actual o trazabilidad viva, vive en `specs/14-current-state.md`.
- Si el documento describe una feature concreta, vive en `specs/[###-feature]/`.
- Si el documento define una decisión técnica persistente, vive en `docs/architecture/`.
- Si el documento define un contrato HTTP o una referencia operativa, vive en `docs/contracts/`.
- Si el documento describe intención funcional o priorización, vive en `docs/backlog/`.
- Si el documento define un flujo funcional central, vive en `docs/flows/` como spec de flujo y debe ser referenciado desde el mapa maestro.
