# Payment Concept Feature

**Feature Branch**: `011-payment-concept`

**Created**: 2026-07-27

**Status**: Draft

**Input**: Base feature for academy payment concepts, naming and code generation.

## User Scenarios & Testing *(mandatory)*

### User Story 1 - Create payment concepts (Priority: P1)

The system lets academy administrators create payment concepts.

**Why this priority**: Payment concepts are the basis for charges and financial traceability.

**Independent Test**: A payment concept can be created, queried and validated for uniqueness.

**Acceptance Scenarios**:

1. **Given** a valid unique name, **When** the admin creates the concept, **Then** the concept is stored.
2. **Given** a duplicate name, **When** the admin creates the concept, **Then** the operation is rejected.

### User Story 2 - Generate payment concept codes (Priority: P2)

The system generates the payment concept code automatically from the name.

**Why this priority**: The code must be consistent and not manually edited.

**Independent Test**: A code is generated from the concept name and remains immutable on update.

**Acceptance Scenarios**:

1. **Given** a valid concept name, **When** the concept is created, **Then** a normalized code is generated.
2. **Given** a repeated normalized code, **When** another concept is created, **Then** the system resolves the collision deterministically.

### User Story 3 - Manage concept lifecycle (Priority: P3)

The system lets academy administrators list, update and deactivate concepts.

**Why this priority**: The concept catalog must remain usable without deleting history.

**Independent Test**: Concepts can be listed, updated and deactivated independently.

**Acceptance Scenarios**:

1. **Given** an existing concept, **When** the admin updates it, **Then** the change is persisted.
2. **Given** an active concept, **When** the admin deactivates it, **Then** it no longer appears as active.

### Edge Cases

- What happens when two names normalize to the same code?
- What happens when the concept is deactivated but still referenced by charges?
- What happens when the frontend sends a code manually?

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: System MUST allow payment concept creation.
- **FR-002**: System MUST generate payment concept codes in backend.
- **FR-003**: System MUST prevent manual code editing from frontend.
- **FR-004**: System MUST allow payment concept listing and detail retrieval.
- **FR-005**: System MUST allow payment concept update.
- **FR-006**: System MUST allow payment concept deactivation while preserving history.

### Key Entities *(include if feature involves data)*

- **PaymentConcept**: catalog item that defines the reason for a charge.

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: Payment concept creation is independent and testable.
- **SC-002**: Code generation is deterministic and backend-owned.
- **SC-003**: The active/inactive lifecycle remains traceable.

## Assumptions

- Payment concepts are tenant-scoped to one academy.
- Charges and payments reference valid concepts.

