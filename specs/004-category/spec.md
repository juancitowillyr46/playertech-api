# Category Feature

**Feature Branch**: `004-category`

**Created**: 2026-07-27

**Status**: Draft

**Input**: Base feature for category lifecycle, listing, detail, update, state
management and business key support.

## User Scenarios & Testing *(mandatory)*

### User Story 1 - Category registration and profile management (Priority: P1)

The system lets academy administrators create, view and update categories.

**Why this priority**: Categories organize the academy’s sports structure.

**Independent Test**: A category can be created, listed, viewed and updated in academy scope.

**Acceptance Scenarios**:

1. **Given** valid category data, **When** an admin creates a category, **Then** the category is stored.
2. **Given** an existing category, **When** the admin updates it, **Then** the new data is persisted.

### User Story 2 - Category state management (Priority: P2)

The system lets admins activate and deactivate categories safely.

**Why this priority**: Categories need controlled lifecycle without losing history.

**Independent Test**: A category can be activated and deactivated independently.

**Acceptance Scenarios**:

1. **Given** an active category, **When** the admin deactivates it, **Then** the category becomes inactive.
2. **Given** an inactive category, **When** the admin activates it, **Then** the category becomes active again.

### User Story 3 - Category options and business key support (Priority: P3)

The system exposes category options and a stable business key for frontend use.

**Why this priority**: Player, team creation and import flows depend on a stable category reference.

**Independent Test**: Categories can be listed as active options and the business key is stable.

**Acceptance Scenarios**:

1. **Given** active categories, **When** the frontend requests options, **Then** the response returns only active entries.
2. **Given** a category record, **When** the business key is required, **Then** the backend exposes a stable key for contract use.

### Edge Cases

- What happens when two categories conflict by name?
- How does the system handle category inactivation while players already belong to it?
- What happens when a category is used in import or team creation flows?

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: System MUST allow category creation.
- **FR-002**: System MUST allow category profile updates.
- **FR-003**: System MUST allow category listing and detail retrieval.
- **FR-004**: System MUST allow category activation and deactivation.
- **FR-005**: System MUST expose active category options for frontend selection.
- **FR-006**: System MUST expose a stable business key for contract use.

### Key Entities *(include if feature involves data)*

- **Category**: sports classification used to organize players and teams.

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: Category lifecycle flows are independently testable.
- **SC-002**: Active options support frontend selectors and imports.
- **SC-003**: Business key support remains stable across the backend.

## Assumptions

- Category continues to belong to a single academy.
- The backend already enforces uniqueness and tenant scope.

