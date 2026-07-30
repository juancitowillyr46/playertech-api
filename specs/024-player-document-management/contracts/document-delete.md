# Contrato: Eliminación de documento del jugador

## Propósito

Aplicar soft delete a un documento y eliminar su archivo físico.

## Endpoint

`DELETE /api/v1/academy/players/{playerId}/documents/{documentId}`

## Reglas

- Eliminar únicamente a través del jugador propietario.
- El tenant autenticado debe coincidir con el tenant del jugador.
- La metadata debe eliminarse lógicamente.
- El archivo almacenado debe eliminarse del almacenamiento privado.
- Los documentos eliminados no deben aparecer en listados activos.
