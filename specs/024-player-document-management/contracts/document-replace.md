# Contrato: Reemplazo de documento del jugador

## Propósito

Reemplazar el archivo y metadata seleccionada de un documento existente conservando su identificador.

## Endpoint

`POST /api/v1/academy/players/{playerId}/documents/{documentId}/replace`

## Request

`multipart/form-data`

Fields:

- `documentType` optional
- `file` required
- `observations` optional

## Reglas

- El identificador del documento debe permanecer sin cambios.
- La asociación con el jugador debe permanecer sin cambios.
- El reemplazo puede actualizar `documentType`, `observations` y la metadata del archivo en conjunto.
- El archivo físico anterior debe eliminarse después de un reemplazo exitoso.
- Si el reemplazo falla, el documento original debe permanecer activo.
