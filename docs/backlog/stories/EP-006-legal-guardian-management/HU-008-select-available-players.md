# HU-008 - Seleccionar Jugadores Disponibles

| Campo | Valor |
| --- | --- |
| Épica | EP-006 Legal Guardian Management |
| Tipo | Historia de Usuario |
| Prioridad | Media |

## Objetivo
Permitir al frontend mostrar un autocomplete con jugadores disponibles para asociar a un acudiente.

## Alcance
- Listar sólo jugadores de la academia actual.
- Excluir los jugadores ya asociados al acudiente.
- Soportar búsqueda parcial mediante `q`.
- Retornar un payload liviano para autocomplete.

## Criterios de aceptación
- El selector no debe mostrar jugadores ya vinculados al mismo acudiente.
- La búsqueda debe responder con coincidencias parciales.
- El contrato debe ser apto para consumo progresivo mientras el usuario escribe.

## Contrato relacionado
- `GET /api/v1/academy/guardians/{guardianId}/players/options?q={texto}`
