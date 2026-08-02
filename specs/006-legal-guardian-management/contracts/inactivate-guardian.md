# PATCH /api/v1/academy/guardians/{guardianId}/inactivate

## Objetivo

Inactivar un acudiente legal sin eliminar su historial.

## Request

```http
PATCH /api/v1/academy/guardians/{guardianId}/inactivate
Authorization: Bearer <token>
```

## Response 204

Sin contenido.

## Reglas

- La operación no borra el acudiente.
- El acudiente debe pertenecer a la academia autenticada.
