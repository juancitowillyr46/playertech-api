# Dashboard Feature

**Feature Branch**: `013-dashboard`

**Created**: 2026-07-27

**Status**: Draft

**Input**: Base feature for operational dashboard views including active players,
pending payments, active memberships and cashflow summary.

## User Scenarios & Testing *(mandatory)*

### User Story 1 - View active players summary (Priority: P1)

The system lets academy administrators quickly view active player data.

**Why this priority**: The dashboard must answer operational questions fast.

**Independent Test**: Active player summary data can be retrieved independently.

**Acceptance Scenarios**:

1. **Given** active players exist, **When** the admin opens the dashboard, **Then** the summary is returned.
2. **Given** no active players, **When** the admin opens the dashboard, **Then** the empty summary is returned safely.

### User Story 2 - View pending payments and active memberships (Priority: P2)

The system lets admins see pending payments and active memberships at a glance.

**Why this priority**: Financial and administrative visibility is the main dashboard value.

**Independent Test**: Pending payments and active memberships are returned in a single dashboard view.

**Acceptance Scenarios**:

1. **Given** pending payments exist, **When** the admin views the dashboard, **Then** the pending amount is visible.
2. **Given** active memberships exist, **When** the admin views the dashboard, **Then** the membership count is visible.

### User Story 3 - View cashflow summary (Priority: P3)

The system lets admins see a cashflow summary for the academy.

**Why this priority**: Cashflow overview supports daily operational decisions.

**Independent Test**: A cashflow summary can be retrieved without mutating data.

**Acceptance Scenarios**:

1. **Given** financial movements exist, **When** the admin views the cashflow summary, **Then** the summary is returned.
2. **Given** no financial movements exist, **When** the admin views the summary, **Then** a safe empty response is returned.

### Edge Cases

- What happens when one summary section has no data?
- How does the system handle partial data across connected modules?
- What happens when dashboard queries are filtered by academy scope?

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: System MUST expose active players summary data.
- **FR-002**: System MUST expose pending payments summary data.
- **FR-003**: System MUST expose active memberships summary data.
- **FR-004**: System MUST expose cashflow summary data.
- **FR-005**: System MUST keep dashboard data read-only.

### Key Entities *(include if feature involves data)*

- **DashboardSummary**: aggregated read model for operational visibility.

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: Operational questions can be answered from a single dashboard view.
- **SC-002**: Dashboard responses remain read-only and tenant-scoped.
- **SC-003**: Summary data is stable enough for frontend rendering.

## Assumptions

- Dashboard data is a read model, not a transactional source of truth.
- The summary may aggregate information from multiple already-defined features.

