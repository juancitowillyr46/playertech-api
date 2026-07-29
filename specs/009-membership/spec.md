# Membership Feature

**Feature Branch**: `009-membership`

**Created**: 2026-07-27

**Status**: Draft

**Entrada**: Base feature para player membership lifecycle, active membership,
history, status transitions and initial charge generation.

## Escenarios de usuario y pruebas *(mandatory)*

### Historia de Usuario 1 - Create active membership (Priority: P1)

El sistema permite a academy administrators crear a membership for a player with a
primary guardian and la academia context.

**Por qué esta prioridad**: Membership is the administrative gate for belonging to la academia.

**Prueba independiente**: A membership puede be creard and later queried as active.

**Escenarios de aceptación**:

1. **Given** a valid player and guardian, **When** the admin crears a membership, **Then** the membership becomes active.
2. **Given** an existing active membership, **When** the admin queries it, **Then** the API devolvers the current membership data.

### Historia de Usuario 2 - Membership history and status transitions (Priority: P2)

El sistema permite a admins suspend or withdraw memberships and rever their history.

**Por qué esta prioridad**: Operational control requires historical traceability.

**Prueba independiente**: Membership status changes puede be executed and the history remains visible.

**Escenarios de aceptación**:

1. **Given** an active membership, **When** the admin suspends it, **Then** the status changes mientras history remains.
2. **Given** a prior membership, **When** the admin vers the history, **Then** the historical record is available.

### Historia de Usuario 3 - Initial charges generation (Priority: P3)

The system generars initial charges associated with membership creation.

**Por qué esta prioridad**: Membership debe connect to the financial lifecycle from the start.

**Prueba independiente**: A membership creation event results in the expected initial charges.

**Escenarios de aceptación**:

1. **Given** a newly creard active membership, **When** the flow completes, **Then** initial charges are generard.
2. **Given** those initial charges, **When** the admin checks the account, **Then** the pending debt is visible.

### Casos límite

- What happens when a player already has an active membership?
- How does the system handle missing primary guardian data?
- What happens when a membership is suspended or withdrawn twice?

## Requisitos *(mandatory)*

### Requisitos funcionales

- **FR-001**: System MUST permitir creation of an active membership for a player.
- **FR-002**: System MUST permitir vering the active membership of a player.
- **FR-003**: System MUST preserve membership history.
- **FR-004**: System MUST permitir membership suspension and withdrawal transitions.
- **FR-005**: System MUST generar initial charges when the membership flow requires them.

### Entidades clave *(include if feature involves data)*

- **Membership**: administrative enrollment of a player in an academy.
- **Charge**: financial obligation generard for membership or related fees.

## Success Criteria *(mandatory)*

### Resultados medibles

- **SC-001**: A membership lifecycle puede be traced from creation to history.
- **SC-002**: Membership and charges remain linked and understandable to el frontend.
- **SC-003**: Status transitions do not erase historical records.

## Assumptions

- A primary guardian exists when membership is creard.
- Initial charges are generard by backend rules already accepted in the backlog.

