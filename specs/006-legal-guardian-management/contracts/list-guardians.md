# GET /api/v1/academy/guardians

## Objetivo

Listar acudientes legales dentro de la academia autenticada.

## Request

```http
GET /api/v1/academy/guardians?page=1&per_page=20&sort=auditTrail.createdAt.value&direction=desc
Authorization: Bearer <token>
```

## Response 200

```json
{
  "data": [
    {
      "id": "019f0000-0000-7000-8000-000000000001",
      "firstName": "Juan",
      "lastName": "Rodas",
      "phone": "+573125953354",
      "phoneSingle": "3125953354",
      "email": "juan.rodas@example.com",
      "documentType": "CC",
      "documentNumber": "1088329031",
      "address": "Calle 1 # 2-3",
      "relationship": "FATHER",
      "relationshipName": "Padre",
      "status": "ACTIVE"
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

- La respuesta debe estar paginada.
- El listado sólo incluye acudientes de la academia autenticada.
- Cuando el teléfono aplique a formato colombiano, cada item debe exponer `phoneSingle` sin prefijo internacional.
