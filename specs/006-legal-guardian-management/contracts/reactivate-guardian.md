# PATCH /api/v1/academy/guardians/{guardianId}/activate

## Objetivo

Reactivar un acudiente legal previamente inactivado.

## Request

```http
PATCH /api/v1/academy/guardians/{guardianId}/activate
Authorization: Bearer <token>
```

## Response 204

Sin contenido.

## Reglas

- La operación sólo aplica sobre acudientes inactivos.
- El acudiente debe pertenecer a la academia autenticada.
