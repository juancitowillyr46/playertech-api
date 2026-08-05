# HU-010 - Seleccionar acudientes disponibles

| Campo | Valor |
| --- | --- |
| Épica | EP-006 Legal Guardian Management |
| Tipo | Historia de Usuario |
| Prioridad | Media |

## Objetivo
Permitir al frontend mostrar un autocomplete con acudientes disponibles para asociar a un jugador específico.

## Alcance
- Listar acudientes por `playerId`.
- Excluir los acudientes ya asociados al jugador.
- Retornar un payload liviano para autocomplete.
- Buscar por `firstName`, `lastName` y nombre completo.

## Criterios de aceptación
- Sólo deben devolverse acudientes de la academia actual.
- Los acudientes ya vinculados al jugador no deben aparecer.
- El selector debe responder de forma liviana para autocomplete.

## Contrato relacionado
- `GET /api/v1/academy/players/{playerId}/guardians/options?q={texto}`
