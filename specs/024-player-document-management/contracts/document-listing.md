# Contrato: Listado de documentos del jugador

## Propósito

Devolver los documentos activos asociados a un jugador, dentro del tenant autenticado.

## Endpoint

`GET /api/v1/academy/players/{playerId}/documents`

## Parámetros de consulta

- `page`: opcional, por defecto `1`
- `per_page`: opcional, por defecto `20`

## Forma de respuesta

```json
{
  "data": [
    {
      "id": "uuid",
      "documentType": "CE",
      "originalFileName": "cedula.pdf",
      "mimeType": "application/pdf",
      "fileSize": 123456,
      "uploadedBy": "uuid",
      "observations": null,
      "createdAt": "2026-07-30T10:00:00Z"
    }
  ],
  "meta": {
    "page": 1,
    "per_page": 20,
    "total": 1,
    "total_pages": 1,
    "has_next": false,
    "has_prev": false
  }
}
```

## Reglas

- Devolver únicamente documentos activos.
- Denegar el acceso cuando el jugador pertenezca a otro tenant.
- Devolver una colección vacía cuando no existan documentos.
