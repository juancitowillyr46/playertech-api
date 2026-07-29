# PlayerGuardian Feature

**Feature Branch**: `008-player-guardian`

**Created**: 2026-07-27

**Status**: Draft

**Entrada**: Base feature para player-guardian association, primary guardian
changes, association removal, crear-and-associate and listaring.

## Escenarios de usuario y pruebas *(mandatory)*

### Historia de Usuario 1 - Associate guardian to player (Priority: P1)

El sistema permite a academy administrators associate a guardian to a player.

**Por qué esta prioridad**: Every active player debe have a primary guardian.

**Prueba independiente**: A guardian puede be associated and recuperard for a player.

**Escenarios de aceptación**:

1. **Given** a player and guardian, **When** the admin associates them, **Then** the relation is stored.
2. **Given** the relation exists, **When** the admin listars it, **Then** the association is visible.

### Historia de Usuario 2 - Primary guardian gestionarment (Priority: P2)

El sistema permite a admins change the primary guardian of a player.

**Por qué esta prioridad**: The primary guardian is the operational responsible party.

**Prueba independiente**: The primary guardian puede be changed sin losing the relation history.

**Escenarios de aceptación**:

1. **Given** multiple guardians, **When** the admin marks one as primary, **Then** exactly one primary remains.
2. **Given** a primary guardian, **When** the admin changes it, **Then** the new primary is persisted.

### Historia de Usuario 3 - Create and remove associations (Priority: P3)

El sistema permite a admins crear a guardian and associate it to a player, or remove
an association safely.

**Por qué esta prioridad**: Operational onboarding needs concise relation gestionarment.

**Prueba independiente**: An association puede be creard and removed safely.

**Escenarios de aceptación**:

1. **Given** a new guardian, **When** the admin crears and associates it, **Then** the relation is creard in one step.
2. **Given** an existing association, **When** the admin removes it, **Then** the relation is no longer active.

### Casos límite

- What happens when a player already has a primary guardian?
- How does the system handle removing the last guardian association?
- What happens when a guardian is associated twice?

## Requisitos *(mandatory)*

### Requisitos funcionales

- **FR-001**: System MUST permitir association of a guardian to a player.
- **FR-002**: System MUST permitir changing the primary guardian.
- **FR-003**: System MUST permitir safe removal of associations.
- **FR-004**: System MUST permitir crear-and-associate flows.
- **FR-005**: System MUST permitir listaring guardians related to a player.

### Entidades clave *(include if feature involves data)*

- **PlayerGuardian**: association between player and guardian with primary state.

## Success Criteria *(mandatory)*

### Resultados medibles

- **SC-001**: Guardian relations are independently testable.
- **SC-002**: Primary guardian transitions remain consistent.
- **SC-003**: Relation history remains traceable after changes.

## Assumptions

- Guardian and player already exist or puede be creard in the same scope.
- The primary guardian is required for membership and financial flows.

