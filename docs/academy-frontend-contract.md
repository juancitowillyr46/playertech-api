# Academy Frontend Contract

Este documento resume el contrato vigente para el front sobre la academia y la imagen institucional.

---

## 1. Datos de academia

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

---

## 2. Imagen institucional

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

## 3. Actor autorizado

La gestión de la academia en el tenant debe quedar asociada al `owner/admin` principal.

- `GET /api/v1/academy/me`
- `PUT /api/v1/academy/me`
- `POST /api/v1/academy/me/shield`

No usar estos endpoints para usuarios operativos secundarios si no son owner/admin.

---

## 4. Fuentes de referencia

- Colección Postman: `postman/PlayerTech.postman_collection.json`
- Historia: [HU-011 Actualizar Academia](/C:/Data/Source/Repos/playertech/docs/backlog/stories/EP-001/HU-011-update-academy.md)
- Historia: [HU-013 Subir Escudo Institucional de Academia](/C:/Data/Source/Repos/playertech/docs/backlog/stories/EP-001/HU-013-upload-academy-shield.md)
