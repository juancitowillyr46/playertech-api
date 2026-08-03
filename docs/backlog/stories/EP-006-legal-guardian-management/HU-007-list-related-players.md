# HU-007 - Listar Jugadores Relacionados

| Campo | Valor |
| --- | --- |
| Épica | EP-006 Legal Guardian Management |
| Tipo | Historia de Usuario |
| Prioridad | Media |

## Objetivo
Permitir consultar los jugadores asociados a un acudiente específico.

## Alcance
- Listar jugadores por `guardianId`.
- Retornar `playerId`, `firstName`, `lastName`, `categoryName`, `relationshipName` y `principal`.
- `relationshipName` debe exponer el label humano del parentesco, por ejemplo `Madre` para `MOTHER`.
- Respetar el tenant autenticado.

## Criterios de aceptación
- Sólo deben devolverse jugadores de la academia actual.
- Debe incluirse la categoría del jugador si existe.
- Debe marcarse cuál relación es principal.

## Contrato relacionado
- `GET /api/v1/academy/guardians/{guardianId}/players`
