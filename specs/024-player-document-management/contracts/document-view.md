# Contrato: Vista en línea del documento del jugador

## Propósito

Devolver un documento para mostrarlo en línea en el navegador cuando el formato lo permita.

## Endpoint

`GET /api/v1/academy/players/{playerId}/documents/{documentId}`

## Comportamiento de respuesta

- Devolver el archivo almacenado en línea.
- Devolver el MIME validado.
- Mantener oculta la ruta física de almacenamiento.

## Headers

- `Content-Disposition: inline`
- `X-Content-Type-Options: nosniff`

## Reglas

- El acceso debe validarse mediante el jugador y el tenant autenticado.
- Los archivos faltantes deben devolver un error controlado.
- Solo pueden visualizarse documentos activos.
