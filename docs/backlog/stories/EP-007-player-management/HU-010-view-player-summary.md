# HU-010 - Ver Resumen de Jugador

| Campo | Valor |
| --- | --- |
| Épica | EP-007 Player Management |
| Tipo | Historia de Usuario |
| Prioridad | Media |

## Objetivo
Permitir consultar un resumen compacto del jugador por `playerId`.

## Alcance
- Retornar `firstName`, `lastName`, `photo` y `gender`.
- Respetar el tenant autenticado.

## Criterios de aceptación
- El endpoint debe devolver sólo la información compacta requerida.
- La foto debe devolverse como objeto media cuando exista.
- El acceso debe estar restringido a la academia autenticada.

## Contrato relacionado
- `GET /api/v1/academy/players/{playerId}/summary`
