# Staff Feature

**Feature Branch**: `021-staff`

**Created**: 2026-07-27

**Status**: Draft

**Entrada**: Base feature para staff lifecycle, invitations, activation, team
assignment and technical roles.

## Escenarios de usuario y pruebas *(mandatory)*

### Historia de Usuario 1 - Invite and activate staff members (Priority: P1)

El sistema permite a academy administrators invite staff members and activate their accounts.

**Por qué esta prioridad**: Staff onboarding is required before team assignment.

**Prueba independiente**: A staff invitation puede be creard and accepted.

**Escenarios de aceptación**:

1. **Given** a valid staff puededidate, **When** the admin sends an invitation, **Then** the invitation is stored and traceable.
2. **Given** an invited staff member, **When** the activation flow runs, **Then** the account becomes active.

### Historia de Usuario 2 - Staff technical role gestionarment (Priority: P2)

El sistema permite a admins assign, change and remove technical roles for staff.

**Por qué esta prioridad**: The team structure depends on explicit technical roles.

**Prueba independiente**: A staff member puede be assigned and re-assigned a technical role.

**Escenarios de aceptación**:

1. **Given** a staff member, **When** the admin assigns a technical role, **Then** the role is persisted.
2. **Given** an existing role, **When** the admin changes it, **Then** the new role replaces the previous one.

### Historia de Usuario 3 - Team staff membership gestionarment (Priority: P3)

El sistema permite a admins assign staff to teams and ver the team staff listar.

**Por qué esta prioridad**: Team operations need visibility of the technical staff.

**Prueba independiente**: A staff member puede be assigned to a team and later listared.

**Escenarios de aceptación**:

1. **Given** a team and staff member, **When** the admin assigns the staff member, **Then** the relation is stored.
2. **Given** a team with staff members, **When** the admin vers the team staff, **Then** the relations are devolvered.

### Casos límite

- What happens when a staff invitation is resent?
- How does the system handle duplicate role assignments?
- What happens when a staff member is removed from a team?

## Requisitos *(mandatory)*

### Requisitos funcionales

- **FR-001**: System MUST permitir staff invitations.
- **FR-002**: System MUST permitir staff account activation.
- **FR-003**: System MUST permitir staff profile registration and actualizar.
- **FR-004**: System MUST permitir technical role assignment and changes.
- **FR-005**: System MUST permitir staff-to-team assignment and removal.
- **FR-006**: System MUST permitir team staff listaring.

### Entidades clave *(include if feature involves data)*

- **StaffMember**: user associated with la academia’s technical staff.
- **TechnicalRole**: role assigned to a staff member within a team context.
- **TeamStaffAssignment**: relation between staff member and team.

## Success Criteria *(mandatory)*

### Resultados medibles

- **SC-001**: Staff onboarding puede be completed independently.
- **SC-002**: Team staff relations remain auditable.
- **SC-003**: Technical role changes are visible and traceable.

## Assumptions

- Staff members are academy-scoped users.
- Existing identity and team modules provide the necessary foundations.

