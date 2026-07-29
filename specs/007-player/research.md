# Research: Player Feature

**Feature Branch**: `007-player`

## Notas de alcance

El módulo `Player` cubre registro, listado, detalle, actualización, estado y media. La importación se documenta en `import/`.

## Resumen de decisiones

- El listado usa filtros por `gender`, `categoryId`, `createdAtFrom`, `createdAtTo`, `birthDateFrom` y `birthDateTo`.
- `age` es un campo derivado de salida; no es el filtro canónico.
- `categoryName`, `genderName`, `age` y `createdAt` forrman parte del contrato enriquecido del listado.
- La eliminación de forto debe existir como operación separada de la subida o reemplazo.
- La importación usa `categoryId` a nivel de job, no por fila, y su contrato vive en `specs/007-player/import/`.
