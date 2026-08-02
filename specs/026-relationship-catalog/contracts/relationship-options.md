# Contrato: Opciones de parentescos

## Endpoint oficial

```http
GET /api/v1/academy/relationships/options
```

## Acceso

- Requiere autenticación.
- Requiere contexto válido de academia.
- Puede ser consultado por cualquier usuario tenant autenticado.
- `ROLE_ROOT` no debe utilizar este endpoint como usuario tenant porque opera sin `academy_id`.
- El cliente no envía `academy_id`; el contexto proviene de la autenticación.

## Respuesta exitosa

```http
HTTP/1.1 200 OK
Content-Type: application/json
```

```json
{
  "data": [
    {
      "value": "FATHER",
      "label": "Padre"
    },
    {
      "value": "MOTHER",
      "label": "Madre"
    },
    {
      "value": "GRANDFATHER",
      "label": "Abuelo"
    },
    {
      "value": "GRANDMA",
      "label": "Abuela"
    },
    {
      "value": "TUTOR",
      "label": "Tutor"
    },
    {
      "value": "BROTHER",
      "label": "Hermano"
    },
    {
      "value": "SISTER",
      "label": "Hermana"
    },
    {
      "value": "OTHER",
      "label": "Otro"
    }
  ],
  "meta": {}
}
```

## Errores relevantes

### Usuario no autenticado

El endpoint debe responder con el error estándar de autenticación del proyecto.

### Usuario sin contexto de academia

El endpoint debe responder con el error estándar de acceso denegado para un usuario que no puede operar en contexto tenant.

## Compatibilidad

La ruta oficial no debe duplicarse con otras rutas de consumidores. La referencia canónica de API y la colección Postman deben reflejar únicamente esta ruta una vez completada la migración.
