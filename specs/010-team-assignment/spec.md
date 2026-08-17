# Player Team Assignment Feature

**Feature Branch**: `010-team-assignment`

**Created**: 2026-07-27

**Status**: Draft

**Entrada**: Base feature para player-to-team assignment, primary team, change of primary team, finalize assignment, assignment history y catálogo de equipos para autocomplete.

## Escenarios de usuario y pruebas *(mandatory)*

### Historia de Usuario 1 - Asignar jugador a un equipo (Priority: P1)

El sistema permite a administradores de academia asignar un jugador a uno o más equipos.

**Por qué esta prioridad**: La participación deportiva depende de la asignación.

**Prueba independiente**: Un jugador puede ser asignado a un equipo dentro del alcance de la academia.

**Escenarios de aceptación**:

1. **Given** un jugador y un equipo, **When** el admin asigna el jugador, **Then** la asignación queda registrada.
2. **Given** una asignación existente, **When** el admin la consulta, **Then** la relación es visible.

### Historia de Usuario 2 - Gestionar equipo principal (Priority: P2)

El sistema permite a administradores marcar, cambiar y finalizar la asignación principal del equipo.

**Por qué esta prioridad**: El equipo principal es una referencia competitiva clave.

**Prueba independiente**: Una asignación principal puede cambiarse sin romper el historial.

**Escenarios de aceptación**:

1. **Given** múltiples asignaciones, **When** el admin marca una como principal, **Then** sólo una principal activa permanece.
2. **Given** una asignación principal, **When** el admin la cambia, **Then** el nuevo principal se refleja.

### Historia de Usuario 3 - Historial de asignaciones (Priority: P3)

El sistema preserva el historial cuando las asignaciones se finalizan.

**Por qué esta prioridad**: El historial competitivo debe permanecer auditable.

**Prueba independiente**: Una asignación finalizada permanece visible en el historial.

**Escenarios de aceptación**:

1. **Given** una asignación activa, **When** el admin la finaliza, **Then** la asignación termina de forma segura.
2. **Given** asignaciones finalizadas, **When** el admin consulta el historial del jugador, **Then** el historial permanece disponible.

### Historia de Usuario 4 - Seleccionar equipos disponibles (Priority: P4)

El sistema permite al frontend consultar un selector liviano de equipos activos para asociar a un jugador dentro de la academia autenticada.

**Por qué esta prioridad**: El formulario de asignación deportiva necesita sugerir equipos válidos sin cargar el listado completo.

**Prueba independiente**: Un admin puede buscar equipos activos por texto parcial y recibir resultados livianos para autocomplete.

**Escenarios de aceptación**:

1. **Given** equipos activos en la academia, **When** el frontend consulta el selector, **Then** el sistema devuelve coincidencias livianas para autocomplete.
2. **Given** un texto parcial, **When** el frontend consulta el selector con `q`, **Then** el sistema responde con coincidencias parciales.
3. **Given** un equipo inactivo o fuera del tenant, **When** se consulta el selector, **Then** el sistema no lo expone en la respuesta.

## Respuesta esperada

```json
{
  "data": [
    {
      "id": "uuid",
      "name": "Team A",
      "categoryName": "Sub 15",
      "status": "ACTIVE"
    }
  ],
  "meta": {}
}
```

### Casos límite

- What happens when a player already has a primary team?
- How does the system handle assignment finalization twice?
- What happens when a player is assigned to a team outside the academia scope?
- What happens when the autocomplete is queried with an empty `q`?

## Requisitos *(mandatory)*

### Requisitos funcionales

- **FR-001**: System MUST permitir player-to-team assignment.
- **FR-002**: System MUST permitir exactly one active primary assignment per player.
- **FR-003**: System MUST permitir changing the primary team.
- **FR-004**: System MUST permitir finalizing assignments sin deleting history.
- **FR-005**: System MUST preserve assignment history.
- **FR-006**: System MUST permitir un selector liviano de equipos activos para autocomplete mediante `GET /api/v1/academy/teams/options`.

### Entidades clave *(include if feature involves data)*

- **TeamAssignment**: relation between player and team with primary state.
- **Team**: catálogo de equipos usados por la asignación y el selector liviano.

## Success Criteria *(mandatory)*

### Resultados medibles

- **SC-001**: Assignment lifecycle is independently testable.
- **SC-002**: Primary team changes remain consistent and auditable.
- **SC-003**: History is preserved after finalization.
- **SC-004**: The team autocomplete response is liviano y apto para consumo incremental.

## Assumptions

- Team and player already exist inside the academia scope.
- Assignment history remains accessible for operational review.
