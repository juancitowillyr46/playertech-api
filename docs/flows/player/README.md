# Player Flows

Este directorio contiene flujos de negocio del módulo `Player` que no deben confundirse con el dominio puro.

## Documentos

- `player-import-flow-spec.md`: contrato funcional del import masivo asíncrono.
- `player-import-ux-spec.md`: experiencia visual y comportamiento del frontend para el import.

## Regla

- El dominio `Player` vive en `docs/domains/` solo cuando el documento sea de dominio.
- Los flujos de importación viven aquí porque mezclan contrato operativo, experiencia y trazabilidad de ejecución.
