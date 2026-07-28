# Venue Domain Spec

## Purpose

Documento central del dominio `Venue`.

## Canonical Sources

- API operativa: [`specs/16-api-reference.md`](/home/juan-rodas/projects/playertech/playertech-api/specs/16-api-reference.md)
- Estado actual: [`specs/14-current-state.md`](/home/juan-rodas/projects/playertech/playertech-api/specs/14-current-state.md)
- Memoria persistente: [`docs/architecture/memory/project-memory.md`](/home/juan-rodas/projects/playertech/playertech-api/docs/architecture/memory/project-memory.md)

## Scope

- create
- list
- detail
- update
- activate/inactivate
- primary venue

## Contract Notes

- El listado y el detalle siguen el contrato plano del frontend, con campos como `name`, `address`, `city`, `country`, `department`, `phone`, `notes`, `isPrimary` y `status`.
- El sort permitido se normaliza por backend antes de construir la consulta.
- La sede principal es una regla de negocio del dominio y no un campo de UI.
- La gestión activa/inactiva y el delete deben mantenerse como operaciones separadas y explícitas.

## Functional Guides

- Backlog epic: [`docs/backlog/epics/EP-002.md`](/home/juan-rodas/projects/playertech/playertech-api/docs/backlog/epics/EP-002.md)
