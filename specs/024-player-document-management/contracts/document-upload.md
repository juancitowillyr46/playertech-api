# Contrato: Carga de documento del jugador

## Propósito

Cargar un nuevo documento del jugador después de validar tipo, tamaño y pertenencia.

## Endpoint

`POST /api/v1/academy/players/{playerId}/documents`

## Request

`multipart/form-data`

Fields:

- `documentType` required
- `file` required
- `observations` optional

## Reglas de archivos aceptados

- Maximum size: 3,145,728 bytes
- Supported extensions: `.pdf`, `.jpg`, `.jpeg`, `.png`
- Supported MIME types: `application/pdf`, `image/jpeg`, `image/png`

## Forma de respuesta

```json
{
  "data": {
    "id": "uuid",
    "documentType": "CE",
    "originalFileName": "cedula.pdf",
    "mimeType": "application/pdf",
    "fileSize": 123456,
    "observations": "some note",
    "createdAt": "2026-07-30T10:00:00Z"
  },
  "meta": {}
}
```

## Reglas

- El tenant debe provenir del contexto autenticado.
- El jugador debe pertenecer al tenant autenticado.
- El nombre original debe sanitizarse antes de almacenarse como metadata.
- El nombre de almacenamiento debe generarse internamente.
- Si la validación falla, no se crea ningún archivo permanente ni registro de metadata.
