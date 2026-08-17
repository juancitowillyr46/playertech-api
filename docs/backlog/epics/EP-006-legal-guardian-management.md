# EP-006 Legal Guardian Management

## Objetivo
Gestionar acudientes legales dentro de la academia autenticada.

## Problema que Resuelve
Centralizar el registro y mantenimiento de acudientes legales para usarlos en la operación diaria, la asociación con jugadores y los procesos relacionados del dominio.

## Valor de Negocio
Permite mantener un directorio confiable de acudientes por academia, con trazabilidad y control de estado.

## Actores
* Academic Administrator

## Dominios Involucrados
* LegalGuardian

## Reglas de Negocio Relacionadas
* Un acudiente pertenece a una única academia.
* Un acudiente puede estar asociado a múltiples jugadores.
* El tipo y número de documento son datos opcionales de apoyo.
* El correo puede registrarse como dato opcional, pero si existe debe mantenerse único dentro de la academia.
* El acudiente puede cambiar entre estado activo e inactivo.
* Las respuestas del acudiente exponen `relationship` como valor técnico y `relationshipName` como etiqueta visible.
* Las respuestas del acudiente exponen `phone` como valor completo y `phoneSingle` como número local sin prefijo internacional cuando aplique, para facilitar el consumo en UI.

## Flujo de Negocio
1. Registrar acudiente.
2. Consultar listado de acudientes.
3. Consultar detalle de acudiente.
4. Actualizar acudiente.
5. Inactivar acudiente.
6. Reactivar acudiente.
7. Filtrar el listado por `documentNumber` y `documentType` además de los filtros de nombre.
8. Listar los jugadores relacionados a un acudiente por `guardianId`.
9. Exponer un selector tipo autocomplete con jugadores disponibles para asociar al acudiente.
10. Exponer un selector tipo autocomplete con acudientes disponibles para asociar a un jugador específico.
11. Validar el bloqueo de desvinculación de acudientes principales cuando no exista un candidato de reemplazo o una reasignación previa.

## Historias de Usuario
* [HU-001 - Listar Acudientes](/home/juan-rodas/projects/playertech/playertech-api/docs/backlog/stories/EP-006-legal-guardian-management/HU-001-list-guardians.md)
* [HU-002 - Ver Detalle de Acudiente](/home/juan-rodas/projects/playertech/playertech-api/docs/backlog/stories/EP-006-legal-guardian-management/HU-002-view-guardian-details.md)
* [HU-003 - Crear Acudiente](/home/juan-rodas/projects/playertech/playertech-api/docs/backlog/stories/EP-006-legal-guardian-management/HU-003-create-guardian.md)
* [HU-004 - Editar Acudiente](/home/juan-rodas/projects/playertech/playertech-api/docs/backlog/stories/EP-006-legal-guardian-management/HU-004-update-guardian.md)
* [HU-005 - Inactivar Acudiente](/home/juan-rodas/projects/playertech/playertech-api/docs/backlog/stories/EP-006-legal-guardian-management/HU-005-inactivate-guardian.md)
* [HU-006 - Reactivar Acudiente](/home/juan-rodas/projects/playertech/playertech-api/docs/backlog/stories/EP-006-legal-guardian-management/HU-006-reactivate-guardian.md)
* [HU-007 - Listar Jugadores Relacionados](/home/juan-rodas/projects/playertech/playertech-api/docs/backlog/stories/EP-006-legal-guardian-management/HU-007-list-related-players.md)
* [HU-008 - Seleccionar Jugadores Disponibles](/home/juan-rodas/projects/playertech/playertech-api/docs/backlog/stories/EP-006-legal-guardian-management/HU-008-select-available-players.md)
* [HU-010 - Seleccionar Acudientes Disponibles](/home/juan-rodas/projects/playertech/playertech-api/docs/backlog/stories/EP-006-legal-guardian-management/HU-010-select-available-guardians.md)
* [HU-011 - Bloquear desvinculación del acudiente principal sin candidato de reemplazo](/home/juan-rodas/projects/playertech/playertech-api/docs/backlog/stories/EP-006-legal-guardian-management/HU-011-block-remove-primary-guardian-without-replacement.md)
* [HU-012 - Bloquear eliminación del acudiente principal con dependencias de negocio](/home/juan-rodas/projects/playertech/playertech-api/docs/backlog/stories/EP-006-legal-guardian-management/HU-012-block-remove-primary-guardian-with-business-dependencies.md)

## Métricas de Éxito
* Número de acudientes registrados por academia.
* Número de acudientes activos vs. inactivos.

## Fuera de Alcance
* Portal externo de acudientes.
* Asociación jugador-acudiente, que se documenta y gestiona en el módulo Player.

## Contratos HTTP Cubiertos
* `POST /api/v1/academy/guardians`
* `GET /api/v1/academy/guardians`
* `GET /api/v1/academy/guardians/{guardianId}`
* `PUT /api/v1/academy/guardians/{guardianId}`.
* `PATCH /api/v1/academy/guardians/{guardianId}/inactivate`
* `PATCH /api/v1/academy/guardians/{guardianId}/activate`
* `GET /api/v1/academy/guardians/{guardianId}/players`
* `GET /api/v1/academy/guardians/{guardianId}/players/options`
* `GET /api/v1/academy/players/{playerId}/guardians/options`
* `DELETE /api/v1/academy/players/{playerId}/guardians/{guardianId}`

## Preguntas Abiertas
* ¿La desvinculación de un acudiente principal debe bloquearse cuando no exista candidato de reemplazo?
* ¿La eliminación de un acudiente principal debe bloquearse cuando tenga dependencias de negocio activas o históricas?

## MVP
Sí.
