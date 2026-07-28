# Player Feature

**Feature Branch**: `007-player`

**Created**: 2026-07-27

**Status**: Draft

**Input**: Base feature for player lifecycle, listing, detail, update, state,
photo and import flows.

## User Scenarios & Testing *(mandatory)*

### User Story 1 - Player registration and profile management (Priority: P1)

The system lets academy administrators register, view and update a player profile.

**Why this priority**: The player is the central entity of the sports domain.

**Independent Test**: A player can be created, viewed and updated inside the academy scope.

**Acceptance Scenarios**:

1. **Given** valid player data, **When** an admin registers a player, **Then** the player is created.
2. **Given** an existing player, **When** an admin updates the profile, **Then** the profile changes are persisted.

### User Story 2 - Player state and media management (Priority: P2)

The system lets admins manage player active/inactive state and photo upload.

**Why this priority**: Operational maintenance depends on controlled lifecycle and media support.

**Independent Test**: A player can be activated, deactivated and have its photo updated independently.

**Acceptance Scenarios**:

1. **Given** an active player, **When** the admin deactivates the player, **Then** the state changes safely.
2. **Given** a player without photo, **When** the admin uploads one, **Then** the media reference is stored.

### User Story 3 - Player import and contract enrichment (Priority: P3)

The system lets admins import players in bulk and retrieve enriched listing data.

**Why this priority**: Bulk operations and enriched list data reduce manual work and improve UX.

**Independent Test**: A player import job can be created and polled, and lists expose enriched output fields.

**Acceptance Scenarios**:

1. **Given** a valid import file, **When** the admin creates an import job, **Then** the backend returns a job identifier.
2. **Given** player list data, **When** the admin consults the list, **Then** the response includes enriched display fields.

### Edge Cases

- What happens when a duplicated document is registered?
- How does the system handle invalid import rows?
- What happens when a player photo is replaced?

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: System MUST allow player registration within academy scope.
- **FR-002**: System MUST allow player profile update.
- **FR-003**: System MUST allow player listing and detail retrieval.
- **FR-004**: System MUST allow player activation and deactivation.
- **FR-005**: System MUST allow player photo upload and replacement.
- **FR-006**: System MUST support bulk import jobs for players.
- **FR-007**: System MUST expose enriched list data for frontend consumption.

### Key Entities *(include if feature involves data)*

- **Player**: central sports entity representing an athlete in the academy.
- **PlayerImportJob**: async import process with progress, summary and errors.
- **PlayerPhoto**: media reference associated with a player.

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: Player lifecycle flows are independently testable.
- **SC-002**: Bulk import jobs can be tracked without blocking navigation.
- **SC-003**: Player lists expose enough information for the frontend to render the table directly.

## Assumptions

- Category selection is handled before import.
- Player photo storage is already available in the backend.
- Current player module code remains the implementation target.

