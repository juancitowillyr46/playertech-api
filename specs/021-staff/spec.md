# Staff Feature

**Feature Branch**: `021-staff`

**Created**: 2026-07-27

**Status**: Draft

**Input**: Base feature for staff lifecycle, invitations, activation, team
assignment and technical roles.

## User Scenarios & Testing *(mandatory)*

### User Story 1 - Invite and activate staff members (Priority: P1)

The system lets academy administrators invite staff members and activate their accounts.

**Why this priority**: Staff onboarding is required before team assignment.

**Independent Test**: A staff invitation can be created and accepted.

**Acceptance Scenarios**:

1. **Given** a valid staff candidate, **When** the admin sends an invitation, **Then** the invitation is stored and traceable.
2. **Given** an invited staff member, **When** the activation flow runs, **Then** the account becomes active.

### User Story 2 - Staff technical role management (Priority: P2)

The system lets admins assign, change and remove technical roles for staff.

**Why this priority**: The team structure depends on explicit technical roles.

**Independent Test**: A staff member can be assigned and re-assigned a technical role.

**Acceptance Scenarios**:

1. **Given** a staff member, **When** the admin assigns a technical role, **Then** the role is persisted.
2. **Given** an existing role, **When** the admin changes it, **Then** the new role replaces the previous one.

### User Story 3 - Team staff membership management (Priority: P3)

The system lets admins assign staff to teams and view the team staff list.

**Why this priority**: Team operations need visibility of the technical staff.

**Independent Test**: A staff member can be assigned to a team and later listed.

**Acceptance Scenarios**:

1. **Given** a team and staff member, **When** the admin assigns the staff member, **Then** the relation is stored.
2. **Given** a team with staff members, **When** the admin views the team staff, **Then** the relations are returned.

### Edge Cases

- What happens when a staff invitation is resent?
- How does the system handle duplicate role assignments?
- What happens when a staff member is removed from a team?

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: System MUST allow staff invitations.
- **FR-002**: System MUST allow staff account activation.
- **FR-003**: System MUST allow staff profile registration and update.
- **FR-004**: System MUST allow technical role assignment and changes.
- **FR-005**: System MUST allow staff-to-team assignment and removal.
- **FR-006**: System MUST allow team staff listing.

### Key Entities *(include if feature involves data)*

- **StaffMember**: user associated with the academy’s technical staff.
- **TechnicalRole**: role assigned to a staff member within a team context.
- **TeamStaffAssignment**: relation between staff member and team.

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: Staff onboarding can be completed independently.
- **SC-002**: Team staff relations remain auditable.
- **SC-003**: Technical role changes are visible and traceable.

## Assumptions

- Staff members are academy-scoped users.
- Existing identity and team modules provide the necessary foundations.

