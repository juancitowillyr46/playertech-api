# HU-009 - Autocomplete de acudientes

## Objetivo
Permitir al frontend consultar un selector liviano de acudientes dentro de la academia autenticada para los flujos de asociación jugador-acudiente.

## Endpoint
`GET /api/v1/academy/guardians/options?q={texto}`

## Reglas
- La búsqueda debe ser parcial.
- Debe respetar el tenant autenticado.
- La respuesta debe ser liviana para autocomplete.
- No debe incluir paginación visible en el contrato.

## Respuesta esperada
```json
{
  "data": [
    {
      "id": "uuid",
      "firstName": "Juan",
      "lastName": "Pérez",
      "documentNumber": "1088329031",
      "documentTypeName": "Cédula de ciudadanía",
      "relationshipName": "Madre"
    }
  ],
  "meta": {}
}
```
