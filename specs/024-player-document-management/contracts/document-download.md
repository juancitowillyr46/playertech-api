# Contrato: Descarga de documento del jugador

## Propósito

Devolver un documento como adjunto descargable.

## Endpoint

`GET /api/v1/academy/players/{playerId}/documents/{documentId}/download`

## Comportamiento de respuesta

- Devolver el archivo almacenado como adjunto.
- Conservar el nombre original sanitizado.
- Devolver el MIME validado.

## Headers

- `Content-Disposition: attachment`
- `X-Content-Type-Options: nosniff`

## Reglas

- El acceso debe validarse mediante el jugador y el tenant autenticado.
- Los archivos faltantes deben devolver un error controlado.
- Solo pueden descargarse documentos activos.
