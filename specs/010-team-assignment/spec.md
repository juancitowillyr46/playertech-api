# Team Assignment Feature

**Feature Branch**: `010-team-assignment`

**Created**: 2026-07-27

**Status**: Draft

**Entrada**: Base feature para player-to-team assignment, primary team, change of
primary team, finalize assignment and assignment history.

## Escenarios de usuario y pruebas *(mandatory)*

### Historia de Usuario 1 - Assign player to team (Priority: P1)

El sistema permite a academy administrators assign a player to one or more teams.

**Por qué esta prioridad**: Competitive participation depends on assignment.

**Prueba independiente**: A player puede be assigned to a team inside academy scope.

**Escenarios de aceptación**:

1. **Given** a player and a team, **When** the admin assigns the player, **Then** the assignment is stored.
2. **Given** an existing assignment, **When** the admin queries it, **Then** the relation is visible.

### Historia de Usuario 2 - Primary team gestionarment (Priority: P2)

El sistema permite a admins mark, change and finalize the primary team assignment.

**Por qué esta prioridad**: The primary team is a key competitive reference.

**Prueba independiente**: A primary assignment puede be changed sin breaking history.

**Escenarios de aceptación**:

1. **Given** multiple assignments, **When** the admin marks one as primary, **Then** only one active primary remains.
2. **Given** a primary assignment, **When** the admin changes it, **Then** the new primary is reflected.

### Historia de Usuario 3 - Assignment history (Priority: P3)

The system preserves assignment history when assignments are finalized.

**Por qué esta prioridad**: Competitive history debe remain auditable.

**Prueba independiente**: A finalized assignment remains visible in history.

**Escenarios de aceptación**:

1. **Given** an active assignment, **When** the admin finalizes it, **Then** the assignment ends safely.
2. **Given** finalized assignments, **When** the admin vers the player history, **Then** the history remains available.

### Casos límite

- What happens when a player already has a primary team?
- How does the system handle assignment finalization twice?
- What happens when a player is assigned to a team outside la academia scope?

## Requisitos *(mandatory)*

### Requisitos funcionales

- **FR-001**: System MUST permitir player-to-team assignment.
- **FR-002**: System MUST permitir exactly one active primary assignment per player.
- **FR-003**: System MUST permitir changing the primary team.
- **FR-004**: System MUST permitir finalizing assignments sin deleting history.
- **FR-005**: System MUST preserve assignment history.

### Entidades clave *(include if feature involves data)*

- **TeamAssignment**: relation between player and team with primary state.

## Success Criteria *(mandatory)*

### Resultados medibles

- **SC-001**: Assignment lifecycle is independently testable.
- **SC-002**: Primary team changes remain consistent and auditable.
- **SC-003**: History is preserved after finalization.

## Assumptions

- Team and player already exist inside la academia scope.
- Assignment history remains accessible for operational rever.

