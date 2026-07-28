# Sport Mode Feature

**Feature Branch**: `022-sport-mode`

**Created**: 2026-07-27

**Status**: Draft

**Input**: Base feature for academy sport mode configuration and future
discipline-aware rules.

## User Scenarios & Testing *(mandatory)*

### User Story 1 - Register academy sport mode (Priority: P1)

The system lets authorized users define the academy's main sport mode.

**Why this priority**: The sport mode is the base configuration for discipline-aware rules.

**Independent Test**: The academy sport mode can be stored and retrieved independently.

**Acceptance Scenarios**:

1. **Given** an authorized user, **When** they configure the academy sport mode, **Then** the value is stored.
2. **Given** an existing sport mode, **When** the user reads the academy profile, **Then** the current sport mode is returned.

### User Story 2 - Update academy sport mode (Priority: P2)

The system lets authorized users update the academy sport mode.

**Why this priority**: The academy may need to adjust its discipline over time.

**Independent Test**: The sport mode can be updated without changing unrelated academy data.

**Acceptance Scenarios**:

1. **Given** a valid existing sport mode, **When** the user updates it, **Then** the new value is persisted.
2. **Given** an updated sport mode, **When** other modules query it, **Then** they receive the current value.

### User Story 3 - Consume sport mode for future rules (Priority: P3)

The system exposes the sport mode so other modules can apply discipline-aware rules.

**Why this priority**: Teams and categories may need to adapt to the configured discipline.

**Independent Test**: Another module can read the academy sport mode and branch rules accordingly.

**Acceptance Scenarios**:

1. **Given** a configured sport mode, **When** a dependent module reads it, **Then** the discipline context is available.
2. **Given** no sport mode configured, **When** a dependent module reads the profile, **Then** the system handles the empty state explicitly.

### Edge Cases

- What happens when the mode is not configured yet?
- What happens if future rules depend on a mode that is still unsupported?
- What happens when the mode changes and dependent modules still use cached data?

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: System MUST allow academy sport mode configuration.
- **FR-002**: System MUST allow academy sport mode update.
- **FR-003**: System MUST expose the current sport mode to dependent modules.
- **FR-004**: System MUST keep the configuration tenant-scoped.

### Key Entities *(include if feature involves data)*

- **SportMode**: discipline configuration associated with one academy.

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: Sport mode can be stored and queried independently.
- **SC-002**: The academy profile exposes the current discipline context.
- **SC-003**: Future team/category rules can consume the value without ambiguity.

## Assumptions

- The academy owns a single primary sport mode for now.
- The initial set of modes can be extended later without changing the core flow.

