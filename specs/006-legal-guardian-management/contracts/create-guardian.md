# POST /api/v1/academy/guardians

## Objetivo

Crear un acudiente legal dentro de la academia autenticada.

## Request

```http
POST /api/v1/academy/guardians
Content-Type: application/json
Authorization: Bearer <token>
```

```json
{
  "firstName": "Juan",
  "lastName": "Rodas",
  "phone": "+573125953354",
  "email": "juan.rodas@example.com",
  "documentType": "CC",
  "documentNumber": "1088329031",
  "address": "Calle 1 # 2-3",
  "relationship": "FATHER"
}
```

## Response 201

```json
{
  "data": {
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
  },
  "meta": {}
}
```

## Reglas

- El acudiente pertenece a la academia autenticada.
- El correo debe ser único dentro de la academia si se envía.
- El endpoint no debe aceptar el `academyId` desde el cliente.
- Cuando el teléfono aplique a formato colombiano, el response debe incluir `phoneSingle` sin prefijo internacional.
