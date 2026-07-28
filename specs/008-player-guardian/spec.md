# PlayerGuardian Feature

**Feature Branch**: `008-player-guardian`

**Created**: 2026-07-27

**Status**: Draft

**Input**: Base feature for player-guardian association, primary guardian
changes, association removal, create-and-associate and listing.

## User Scenarios & Testing *(mandatory)*

### User Story 1 - Associate guardian to player (Priority: P1)

The system lets academy administrators associate a guardian to a player.

**Why this priority**: Every active player must have a primary guardian.

**Independent Test**: A guardian can be associated and retrieved for a player.

**Acceptance Scenarios**:

1. **Given** a player and guardian, **When** the admin associates them, **Then** the relation is stored.
2. **Given** the relation exists, **When** the admin lists it, **Then** the association is visible.

### User Story 2 - Primary guardian management (Priority: P2)

The system lets admins change the primary guardian of a player.

**Why this priority**: The primary guardian is the operational responsible party.

**Independent Test**: The primary guardian can be changed without losing the relation history.

**Acceptance Scenarios**:

1. **Given** multiple guardians, **When** the admin marks one as primary, **Then** exactly one primary remains.
2. **Given** a primary guardian, **When** the admin changes it, **Then** the new primary is persisted.

### User Story 3 - Create and remove associations (Priority: P3)

The system lets admins create a guardian and associate it to a player, or remove
an association safely.

**Why this priority**: Operational onboarding needs concise relation management.

**Independent Test**: An association can be created and removed safely.

**Acceptance Scenarios**:

1. **Given** a new guardian, **When** the admin creates and associates it, **Then** the relation is created in one step.
2. **Given** an existing association, **When** the admin removes it, **Then** the relation is no longer active.

### Edge Cases

- What happens when a player already has a primary guardian?
- How does the system handle removing the last guardian association?
- What happens when a guardian is associated twice?

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: System MUST allow association of a guardian to a player.
- **FR-002**: System MUST allow changing the primary guardian.
- **FR-003**: System MUST allow safe removal of associations.
- **FR-004**: System MUST allow create-and-associate flows.
- **FR-005**: System MUST allow listing guardians related to a player.

### Key Entities *(include if feature involves data)*

- **PlayerGuardian**: association between player and guardian with primary state.

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: Guardian relations are independently testable.
- **SC-002**: Primary guardian transitions remain consistent.
- **SC-003**: Relation history remains traceable after changes.

## Assumptions

- Guardian and player already exist or can be created in the same scope.
- The primary guardian is required for membership and financial flows.

