# Fiscal Feature

**Feature Branch**: `023-fiscal`

**Created**: 2026-07-27

**Status**: Draft

**Input**: Base feature for academy tax information, receipt generation and
external fiscal document linking.

## User Scenarios & Testing *(mandatory)*

### User Story 1 - Manage academy tax information (Priority: P1)

The system lets academy administrators create, view and update tax information.

**Why this priority**: Fiscal profile is required for receipts and compliance.

**Independent Test**: Tax information can be stored, queried and updated.

**Acceptance Scenarios**:

1. **Given** valid tax data, **When** the admin creates it, **Then** the fiscal profile is stored.
2. **Given** an existing fiscal profile, **When** the admin updates it, **Then** the new values are persisted.

### User Story 2 - Generate payment receipts (Priority: P2)

The system lets admins generate payment receipts using the academy fiscal profile.

**Why this priority**: Receipt generation depends on fiscal configuration.

**Independent Test**: A receipt can be generated from an existing payment.

**Acceptance Scenarios**:

1. **Given** a valid payment, **When** the admin generates a receipt, **Then** the receipt is produced.
2. **Given** an academy fiscal profile, **When** the receipt is generated, **Then** the fiscal data is used.

### User Story 3 - Link external fiscal documents (Priority: P3)

The system lets admins link external fiscal documents in PDF for later reference.

**Why this priority**: External support documents improve operational traceability.

**Independent Test**: A PDF document can be linked to the fiscal record.

**Acceptance Scenarios**:

1. **Given** a valid PDF, **When** the admin links it, **Then** the document reference is stored.
2. **Given** a linked document, **When** the admin consults the record, **Then** the attachment is visible.

### Edge Cases

- What happens when tax data is incomplete?
- How does the system handle receipt generation without fiscal profile?
- What happens when an external PDF is replaced?

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: System MUST allow academy tax information management.
- **FR-002**: System MUST allow payment receipt generation.
- **FR-003**: System MUST allow linking external fiscal PDF documents.
- **FR-004**: System MUST preserve fiscal profile history where applicable.
- **FR-005**: System MUST keep the fiscal information tenant-scoped.

### Key Entities *(include if feature involves data)*

- **FiscalProfile**: tax information used by the academy as fiscal emitter.
- **Receipt**: generated payment receipt or comprobante.
- **FiscalDocument**: external PDF evidence linked to the fiscal context.

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: Fiscal profile operations are independently testable.
- **SC-002**: Receipt generation uses the academy fiscal profile.
- **SC-003**: External fiscal documents remain traceable.

## Assumptions

- The fiscal profile belongs to one academy.
- Receipt generation is already linked to payment data available in backend.

