# Academy Frontend Contract

Este documento resume el contrato vigente para el front sobre la academia y la imagen institucional.

---

## 1. Contexto del tenant

### Consulta

`GET /api/v1/academy/context`

Usar para obtener el contexto operativo del usuario autenticado dentro del tenant.

### Propósito

- Resolver `mode`
- Resolver `userId`
- Resolver `academyId`
- Resolver `role`
- Resolver `roles`

### Regla

Este endpoint no representa el perfil de la academia.

---

## 2. Perfil de academia

### Consulta

`GET /api/v1/academy/me`

Usar para mostrar la información completa de la academia del usuario owner/admin.

### Actualización textual

`PUT /api/v1/academy/me`

Body permitido:

```json
{
  "name": "Academia Demo",
  "contactEmail": "contacto@academiademo.com",
  "phone": "+51 987 654 321",
  "country": "Colombia",
  "department": "Cundinamarca",
  "address": "Jr. Secundario 789",
  "city": "Arequipa"
}
```

### Reglas

- No enviar `logo` ni `shield` en este endpoint.
- No enviar archivo binario aquí.
- Este endpoint es para datos textuales.
- `address` sigue siendo parte del perfil principal de la academia y se usa también como base para crear la sede principal en `venues`.

---

## 3. Imagen institucional

### Subida / reemplazo

`POST /api/v1/academy/me/shield`

Body:

- `multipart/form-data`
- campo: `shield`

### Reglas

- El usuario selecciona el archivo desde su escritorio.
- El front puede abrir un modal de recorte y zoom antes de guardar.
- El resultado del crop debe mantenerse solo en memoria/local state hasta que el usuario confirme `Guardar cambios`.
- Al confirmar, enviar el archivo final como `multipart/form-data` en el campo `shield`.
- El backend debe aceptar el resultado como `File` o `Blob` del recorte, no como base64.
- El backend devuelve la referencia almacenada.
- Esta es la vía correcta para actualizar la imagen institucional.

---

## 4. Perfil fiscal y comprobante de pagos

### Contrato fiscal vigente

El perfil fiscal actual de la academia vive dentro de `academies` y se consulta o actualiza con:

- `GET /api/v1/academy/me/tax-profile`
- `PUT /api/v1/academy/me/tax-profile`

Campos fiscales operativos vigentes:

- `taxIdType`
- `taxIdNumber`
- `taxCheckDigit`
- `taxRegime`
- `billingEmail`

### Uso actual

Estos datos se usan como base para el comprobante de pagos, no para facturación electrónica DIAN.

### Alcance del MVP

- No modelar todavía CUFE, firma digital DIAN ni resolución de facturación electrónica.
- No crear una tabla separada de facturación electrónica para este flujo.
- Mantener el perfil fiscal como fuente operativa para comprobantes internos.

### Datos legales

Si en el futuro se requiere `legalName` o razón social editable, ese dato debe tratarse como un bloque separado del `tax-profile`.
Por ahora no forma parte del contrato editable de este endpoint.

---

## 5. Actor autorizado

La gestión de la academia en el tenant debe quedar asociada al `owner/admin` principal.

- `GET /api/v1/academy/context`
- `GET /api/v1/academy/me`
- `PUT /api/v1/academy/me`
- `POST /api/v1/academy/me/shield`

No usar estos endpoints para usuarios operativos secundarios si no son owner/admin.

---

## 6. Fuentes de referencia

- Colección Postman: `postman/PlayerTech.postman_collection.json`
- Contrato HTTP: `specs/16-api-reference.md`
- Historia: [HU-011 Actualizar Academia](/C:/Data/Source/Repos/playertech/docs/backlog/stories/EP-001/HU-011-update-academy.md)
- Historia: [HU-013 Subir Escudo Institucional de Academia](/C:/Data/Source/Repos/playertech/docs/backlog/stories/EP-001/HU-013-upload-academy-shield.md)
