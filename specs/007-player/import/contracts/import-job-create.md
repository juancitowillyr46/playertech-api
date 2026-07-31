# Import Job Create Contract

## Propósito

Contrato para crear el job de importación.

## Request

- `multipart/form-data`
- `categoryId`
- `file`

`categoryId` define la categoría del job completo y no se repite por fila.

## Response

```json
{
  "data": {
    "jobId": "uuid",
    "status": "QUEUED"
  },
  "meta": {}
}
```
