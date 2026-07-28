# Documentation Index

`docs/` contiene la documentación persistente, operativa y de trazabilidad del proyecto.

## Structure

- `docs/backlog/` épicas, historias y priorización funcional.
- `docs/architecture/` ADR, auditorías, políticas y memorias técnicas.
- `docs/contracts/` índice de contratos HTTP y sincronización con frontend.
- `docs/domains/` documentos centrales por dominio puro y capa compartida.
- `docs/flows/` documentos centrales de flujos funcionales específicos.
- `docs/database/` modelo de datos, relaciones y diccionario de entidades.
- `docs/audit/` reportes de auditoría y migración documental.
- `docs/traceability/` matrices y soportes de trazabilidad SDD.
- `docs/operations/` guías operativas de entorno.
- `docs/_archive/` documentación histórica o reemplazada por la capa canónica.
- `docs/architecture/documentation-map.md` mapa maestro para ubicar contratos y documentos centrales.

## Usage Rule

- Si una decisión afecta al backlog o a la historia funcional, vive en `docs/backlog/`.
- Si una decisión afecta al contrato técnico o a la arquitectura, vive en `specs/` o `docs/architecture/` según su naturaleza.
- Si una decisión afecta un flujo funcional específico, debe existir un documento central en `docs/flows/<dominio>/` y el resto de documentos deben apuntar a él.
- Si una decisión redefine el dominio conceptual, la fuente canónica es `specs/02-domains.md`.
- Si un documento ya fue reemplazado por `specs/`, debe moverse a `docs/_archive/`.
