# Tenant Onboarding Feature

**Feature Branch**: `014-tenant-onboarding`

**Created**: 2026-07-27

**Status**: Draft

**Input**: Base feature for tenant signup, activation, initial team creation and
registration source tracking.

## User Scenarios & Testing *(mandatory)*

### User Story 1 - Register tenant (Priority: P1)

The system lets a future tenant register with simplified academy data.

**Why this priority**: Tenant onboarding is the entry point for new customers.

**Independent Test**: A tenant signup can be created and the academy is stored.

**Acceptance Scenarios**:

1. **Given** valid signup data, **When** the prospect completes the form, **Then** the tenant is created.
2. **Given** a created tenant, **When** the admin consults it later, **Then** the academy record exists.

### User Story 2 - Activate tenant and track source (Priority: P2)

The system lets the tenant activate its account and tracks where it came from.

**Why this priority**: Activation is required before the tenant can operate.

**Independent Test**: A created tenant can be activated and its source is traceable.

**Acceptance Scenarios**:

1. **Given** a pending activation token, **When** the tenant activates it, **Then** the academy becomes usable.
2. **Given** a created tenant, **When** the source is inspected, **Then** the registration source is visible.

### User Story 3 - Create initial team during signup (Priority: P3)

The system lets the signup flow create the initial team for the new tenant.

**Why this priority**: The first team anchors the sports structure of the academy.

**Independent Test**: A signup flow can create the tenant and its initial team together.

**Acceptance Scenarios**:

1. **Given** a valid signup with initial team data, **When** the flow completes, **Then** the team is created.
2. **Given** an initial team, **When** the tenant is reviewed later, **Then** the team is linked to the academy.

### Edge Cases

- What happens when signup data is incomplete?
- How does the system handle repeated activation attempts?
- What happens when the initial team category is invalid?

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: System MUST allow tenant registration.
- **FR-002**: System MUST allow tenant activation.
- **FR-003**: System MUST track the registration source.
- **FR-004**: System MUST allow initial team creation during signup when required.
- **FR-005**: System MUST preserve tenant scope and onboarding history.

### Key Entities *(include if feature involves data)*

- **TenantSignup**: onboarding record for a future academy tenant.
- **Academy**: tenant root created by the signup flow.
- **RegistrationSource**: origin of the tenant creation.

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: Tenant signup can be completed without ambiguity.
- **SC-002**: Activation and source tracking remain traceable.
- **SC-003**: Initial team creation stays linked to the created academy.

## Assumptions

- The signup flow is already publicly accessible or documented.
- Activation tokens and email-related flow are already supported by backend rules.

