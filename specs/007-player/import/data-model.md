# Data Model: Player Import

## Entities

### PlayerImportJob

- Rastrea el estado, el progreso, el summary y los errores de la importación.
- Pertenece al contexto tenant actual.
- Referencia el `categoryId` seleccionado para todo el job.

### ImportErrorRow

- Representa un problema de validación o procesamiento a nivel de fila.
- Guarda número de fila, nombre del campo y mensaje legible.

### ImportResumen

- Guarda `totalRows`, `processedRows`, `successRows` y `errorRows`.
