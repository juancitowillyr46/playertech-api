# Guardian Feature

**Feature Branch**: `006-guardian`

**Created**: 2026-07-27

**Status**: Draft

**Input**: Base feature for guardian lifecycle, listing, detail and creation.

## User Scenarios & Testing *(mandatory)*

### User Story 1 - Guardian creation and profile management (Priority: P1)

The system lets academy administrators create and manage guardian profiles.

**Why this priority**: Guardians are required to keep player administrative flows complete.

**Independent Test**: A guardian can be created and later viewed or updated.

**Acceptance Scenarios**:

1. **Given** valid guardian data, **When** the admin creates a guardian, **Then** the guardian is stored.
2. **Given** an existing guardian, **When** the admin updates it, **Then** the new data is persisted.

### User Story 2 - Guardian listing and detail retrieval (Priority: P2)

The system lets admins list and inspect guardians within the academy scope.

**Why this priority**: Operational work needs a reliable guardian directory.

**Independent Test**: A guardian can be listed and queried by detail endpoint.

**Acceptance Scenarios**:

1. **Given** guardians in the academy, **When** the admin lists them, **Then** the response is returned with pagination.
2. **Given** a guardian identifier, **When** the admin views it, **Then** the guardian detail is returned.

### User Story 3 - Guardian operational data support (Priority: P3)

The system stores guardian data useful for contact and payment operations.

**Why this priority**: Guardian records support downstream membership and payment flows.

**Independent Test**: Contact data used by other features is available in the guardian profile.

**Acceptance Scenarios**:

1. **Given** a guardian profile, **When** the admin records contact data, **Then** the values are retained.
2. **Given** an active guardian, **When** membership or payment flows reference it, **Then** the guardian data is available.

### Edge Cases

- What happens when a guardian is created with incomplete contact information?
- How does the system handle duplicate guardians within the same academy?
- What happens when a guardian is deleted or inactivated?

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: System MUST allow guardian creation.
- **FR-002**: System MUST allow guardian profile updates.
- **FR-003**: System MUST allow guardian listing and detail retrieval.
- **FR-004**: System MUST retain guardian contact data for downstream flows.
- **FR-005**: System MUST preserve tenant isolation for guardian operations.

### Key Entities *(include if feature involves data)*

- **Guardian**: legal or responsible person linked to one or more players.

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: Guardian records are independently testable.
- **SC-002**: Guardian data can support player, membership and payment flows.
- **SC-003**: List and detail responses remain stable for frontend usage.

## Assumptions

- Guardian records are used by membership and payment flows.
- The backend already supports tenant-scoped data isolation.

