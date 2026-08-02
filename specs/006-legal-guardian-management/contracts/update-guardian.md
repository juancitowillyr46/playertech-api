# PUT /api/v1/academy/guardians/{guardianId}

## Objetivo

Actualizar un acudiente legal existente dentro de la academia autenticada.

## Request

```http
PUT /api/v1/academy/guardians/{guardianId}
Content-Type: application/json
Authorization: Bearer <token>
```

```json
{
  "firstName": "Juan",
  "lastName": "Rodas",
  "phone": "+573125953354",
  "email": "juan.rodas.updated@example.com",
  "documentType": "CC",
  "documentNumber": "1088329031",
  "address": "Calle 1 # 2-3",
  "relationship": "Padre"
}
```

## Response 200

```json
{
  "data": {
    "id": "019f0000-0000-7000-8000-000000000001",
    "firstName": "Juan",
    "lastName": "Rodas",
    "phone": "+573125953354",
    "email": "juan.rodas.updated@example.com",
    "documentType": "CC",
    "documentNumber": "1088329031",
    "address": "Calle 1 # 2-3",
    "relationship": "Padre",
    "status": "ACTIVE"
  },
  "meta": {}
}
```

## Reglas

- El acudiente debe pertenecer a la academia autenticada.
- El correo debe conservar unicidad por academia si se modifica.
- No se debe permitir actualizar acudientes de otra academia.
