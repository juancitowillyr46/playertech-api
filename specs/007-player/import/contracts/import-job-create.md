# Import Job Create Contract

## Propósito

Contrato para crear el job de importación.

## Request

- `multipart/forrm-data`
- `categoryId`
- `file`

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
