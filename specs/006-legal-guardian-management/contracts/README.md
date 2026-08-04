# Contratos HTTP: Legal Guardian Management

Este directorio contiene los contratos HTTP canónicos del módulo de acudientes legales.

## Endpoints cubiertos

- `POST /api/v1/academy/guardians`
- `GET /api/v1/academy/guardians`
- `GET /api/v1/academy/guardians/{guardianId}`
- `PUT /api/v1/academy/guardians/{guardianId}`
- `PATCH /api/v1/academy/guardians/{guardianId}/inactivate`
- `PATCH /api/v1/academy/guardians/{guardianId}/activate`
- `GET /api/v1/academy/guardians/{guardianId}/players`
- `GET /api/v1/academy/guardians/{guardianId}/players/options`
- `GET /api/v1/academy/guardians/options`

## Notas de contrato

- Los contratos deben usar el envelope JSON vigente del proyecto.
- Los endpoints deben respetar el aislamiento por academia autenticada.
- Las respuestas de listado deben ser paginadas según el estándar de la API.
- La actualización, inactivación y reactivación forman parte del alcance funcional documentado en la épica y las HUs.
- El selector `players/options` es un contrato liviano para autocomplete y debe excluir jugadores ya asociados al acudiente.
- El selector `guardians/options` es un contrato liviano para autocomplete de acudientes dentro de la academia autenticada.
- Los contratos deben reflejar sólo lo que la implementación expone o se ha definido formalmente en backlog.
