# Team Assignment Feature

**Feature Branch**: `010-team-assignment`

**Created**: 2026-07-27

**Status**: Draft

**Input**: Base feature for player-to-team assignment, primary team, change of
primary team, finalize assignment and assignment history.

## User Scenarios & Testing *(mandatory)*

### User Story 1 - Assign player to team (Priority: P1)

The system lets academy administrators assign a player to one or more teams.

**Why this priority**: Competitive participation depends on assignment.

**Independent Test**: A player can be assigned to a team inside academy scope.

**Acceptance Scenarios**:

1. **Given** a player and a team, **When** the admin assigns the player, **Then** the assignment is stored.
2. **Given** an existing assignment, **When** the admin queries it, **Then** the relation is visible.

### User Story 2 - Primary team management (Priority: P2)

The system lets admins mark, change and finalize the primary team assignment.

**Why this priority**: The primary team is a key competitive reference.

**Independent Test**: A primary assignment can be changed without breaking history.

**Acceptance Scenarios**:

1. **Given** multiple assignments, **When** the admin marks one as primary, **Then** only one active primary remains.
2. **Given** a primary assignment, **When** the admin changes it, **Then** the new primary is reflected.

### User Story 3 - Assignment history (Priority: P3)

The system preserves assignment history when assignments are finalized.

**Why this priority**: Competitive history must remain auditable.

**Independent Test**: A finalized assignment remains visible in history.

**Acceptance Scenarios**:

1. **Given** an active assignment, **When** the admin finalizes it, **Then** the assignment ends safely.
2. **Given** finalized assignments, **When** the admin views the player history, **Then** the history remains available.

### Edge Cases

- What happens when a player already has a primary team?
- How does the system handle assignment finalization twice?
- What happens when a player is assigned to a team outside the academy scope?

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: System MUST allow player-to-team assignment.
- **FR-002**: System MUST allow exactly one active primary assignment per player.
- **FR-003**: System MUST allow changing the primary team.
- **FR-004**: System MUST allow finalizing assignments without deleting history.
- **FR-005**: System MUST preserve assignment history.

### Key Entities *(include if feature involves data)*

- **TeamAssignment**: relation between player and team with primary state.

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: Assignment lifecycle is independently testable.
- **SC-002**: Primary team changes remain consistent and auditable.
- **SC-003**: History is preserved after finalization.

## Assumptions

- Team and player already exist inside the academy scope.
- Assignment history remains accessible for operational review.

