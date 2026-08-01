# Contrato: Opciones de tipos de documento

## Endpoint oficial

```http
GET /api/v1/academy/document-types/options
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
      "value": "CE",
      "label": "Cédula de extranjería"
    },
    {
      "value": "CC",
      "label": "Cédula de ciudadanía"
    },
    {
      "value": "TI",
      "label": "Tarjeta de identidad"
    },
    {
      "value": "PPT",
      "label": "Permiso por Protección Temporal"
    },
    {
      "value": "PASSPORT",
      "label": "Pasaporte"
    },
    {
      "value": "RC",
      "label": "Registro civil"
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

La ruta anterior queda fuera del contrato oficial:

```http
GET /api/v1/academy/players/document-types/options
```

Los consumidores internos deben migrar a la ruta neutral antes de retirar la ruta anterior. La referencia canónica de API y la colección Postman deben reflejar únicamente la ruta oficial una vez completada la migración.
