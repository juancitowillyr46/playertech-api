# Specs Index

`specs/` contiene las especificaciones canónicas de arquitectura, seguridad, API, base técnica y criterios de ejecución.

## Structure

- `specs/01-arquitecture.md` principios de arquitectura.
- `specs/03-security.md` reglas de seguridad.
- `specs/04-api.md` marco general de la API.
- `specs/12-execution-order.md` orden de ejecución.
- `specs/14-current-state.md` estado actual y trazabilidad.
- `specs/16-api-reference.md` referencia HTTP operativa.
- `specs/[###-feature]/` carpetas canónicas por feature bajo Spec Kit.
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

- `spec.md`: alcance, user stories, requisitos, criterios de éxito y supuestos.
- `plan.md`: contexto técnico, decisión de estructura y estrategia de trabajo.
- `research.md`: preguntas abiertas, límites y decisiones que todavía pueden evolucionar.
- `data-model.md`: entidades y relaciones de la feature.
- `quickstart.md`: guía corta para entender y verificar la feature.
- `contracts/`: request/response, ejemplos HTTP, payloads y muestras de contrato.
- `tasks.md`: plan ejecutable y fases del trabajo.

## Usage Rule

- Si el documento define una regla vigente o un contrato canónico, vive en `specs/`.
- Si el documento describe intención funcional o priorización, vive en `docs/backlog/`.
- Si el documento define un flujo funcional central, vive en `docs/flows/` como spec de flujo y debe ser referenciado desde el mapa maestro.
