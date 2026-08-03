# GET /api/v1/academy/guardians/{guardianId}

## Objetivo

Consultar el detalle de un acudiente legal.

## Request

```http
GET /api/v1/academy/guardians/{guardianId}
Authorization: Bearer <token>
```

## Response 200

```json
{
  "data": {
    "id": "019f0000-0000-7000-8000-000000000001",
    "firstName": "Juan",
    "lastName": "Rodas",
    "phone": "+573125953354",
    "email": "juan.rodas@example.com",
    "documentType": "CC",
    "documentNumber": "1088329031",
    "address": "Calle 1 # 2-3",
    "relationship": "FATHER",
    "status": "ACTIVE"
  },
  "meta": {}
}
```

## Reglas

- El acudiente debe pertenecer a la academia autenticada.
- Si no existe, debe responder el error de recurso no encontrado vigente.
