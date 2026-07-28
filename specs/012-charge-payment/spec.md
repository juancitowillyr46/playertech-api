# Charge & Payment Feature

**Feature Branch**: `012-charge-payment`

**Created**: 2026-07-27

**Status**: Draft

**Input**: Base feature for charges, payments, payment methods, evidence upload,
consultation and player debt visibility.

## User Scenarios & Testing *(mandatory)*

### User Story 1 - View and manage pending charges (Priority: P1)

The system lets academy administrators consult pending charges and the debt
status for a player or membership.

**Why this priority**: Financial visibility is required for daily operation.

**Independent Test**: Pending charges and debt can be retrieved for an academy member.

**Acceptance Scenarios**:

1. **Given** a player with pending charges, **When** the admin views the charges, **Then** the pending obligations are returned.
2. **Given** a player with debt, **When** the admin checks the debt view, **Then** the outstanding balance is visible.

### User Story 2 - Register payments and evidence (Priority: P2)

The system lets admins register payments, payment methods and payment evidence.

**Why this priority**: Payment registration is the core financial transaction flow.

**Independent Test**: A payment can be registered and evidence can be attached independently.

**Acceptance Scenarios**:

1. **Given** a pending charge, **When** the admin registers a payment, **Then** the charge becomes reconciled according to the rules.
2. **Given** a payment without evidence, **When** the admin uploads evidence, **Then** the evidence reference is stored.

### User Story 3 - Consult payments and financial history (Priority: P3)

The system lets admins consult payments and financial history.

**Why this priority**: Auditing and support depend on historical consultation.

**Independent Test**: Payment history can be retrieved and inspected without side effects.

**Acceptance Scenarios**:

1. **Given** recorded payments, **When** the admin consults them, **Then** the payment list is returned.
2. **Given** an account with financial history, **When** the admin consults it, **Then** the history is visible.

### Edge Cases

- What happens when a payment is cancelled?
- How does the system handle partial payment reconciliation?
- What happens when evidence is uploaded for a non-existent payment?

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: System MUST allow consultation of pending charges.
- **FR-002**: System MUST allow consultation of player debt.
- **FR-003**: System MUST allow payment registration.
- **FR-004**: System MUST allow payment method capture when applicable.
- **FR-005**: System MUST allow payment evidence upload.
- **FR-006**: System MUST allow consultation of payments and financial history.
- **FR-007**: System MUST allow payment cancellation when business rules permit it.

### Key Entities *(include if feature involves data)*

- **Charge**: financial obligation pending payment.
- **Payment**: recorded settlement against one or more charges.
- **PaymentEvidence**: proof attached to a payment.
- **PaymentMethod**: method used to register the payment.

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: Financial obligations can be viewed without ambiguity.
- **SC-002**: A payment can be registered and traced to its evidence.
- **SC-003**: History and cancellation actions remain auditable.

## Assumptions

- Membership may already exist and generate charges.
- Payment evidence storage is already available in the backend.
- Cancellation rules are handled by existing business policies.

