# Venue Feature

**Feature Branch**: `002-venue`

**Created**: 2026-07-27

**Status**: Draft

**Input**: Base feature for academy venue management and venue contact data.

## User Scenarios & Testing *(mandatory)*

### User Story 1 - Create venues (Priority: P1)

The system lets academy administrators create venues.

**Why this priority**: Venues are required to organize the academy's physical locations.

**Independent Test**: A venue can be created and stored with tenant isolation.

**Acceptance Scenarios**:

1. **Given** valid venue data, **When** the admin creates the venue, **Then** the venue is stored.
2. **Given** missing name, **When** the admin creates the venue, **Then** the operation is rejected.

### User Story 2 - List and inspect venues (Priority: P2)

The system lets academy administrators list and view venue details.

**Why this priority**: Venues must be queryable for operational use.

**Independent Test**: A venue list and a venue detail can be queried independently.

**Acceptance Scenarios**:

1. **Given** existing venues, **When** the admin lists them, **Then** only academy venues are returned.
2. **Given** an existing venue, **When** the admin reads the detail, **Then** the venue data is returned.

### User Story 3 - Update and deactivate venues (Priority: P3)

The system lets academy administrators update, deactivate and reactivate venues.

**Why this priority**: Venue lifecycle must be manageable without deleting history.

**Independent Test**: A venue can be updated and its status can be toggled independently.

**Acceptance Scenarios**:

1. **Given** a venue, **When** the admin updates it, **Then** the changes are persisted.
2. **Given** an active venue, **When** the admin deactivates it, **Then** the venue becomes inactive.
3. **Given** an inactive venue, **When** the admin reactivates it, **Then** the venue becomes active.

### User Story 4 - Manage contact data (Priority: P4)

The system stores optional venue contact data.

**Why this priority**: Operational detail improves venue usability without forcing extra data.

**Independent Test**: Optional phone and address fields can be saved and read.

**Acceptance Scenarios**:

1. **Given** a venue with phone and address, **When** the admin views it, **Then** the contact data is visible.
2. **Given** a venue without contact data, **When** the admin views it, **Then** the response still succeeds.

### Edge Cases

- What happens when a venue name is duplicated within the same academy?
- What happens when the admin deactivates a venue still referenced by teams?
- What happens when optional contact fields are omitted?

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: System MUST allow venue creation.
- **FR-002**: System MUST allow venue listing.
- **FR-003**: System MUST allow venue detail retrieval.
- **FR-004**: System MUST allow venue update.
- **FR-005**: System MUST allow venue deactivation.
- **FR-006**: System MUST allow venue reactivation.
- **FR-007**: System MUST support optional contact data for venues.
- **FR-008**: System MUST keep venues tenant-scoped.

### Key Entities *(include if feature involves data)*

- **Venue**: physical location associated with an academy.

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: Venue creation and listing are independently testable.
- **SC-002**: Venue lifecycle changes are preserved without deleting history.
- **SC-003**: Optional contact data is available when provided.

## Assumptions

- Venues belong to one academy.
- Venue deletion is soft by design; lifecycle uses active/inactive states.

