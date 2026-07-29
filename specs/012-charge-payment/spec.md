# Charge & Payment Feature

**Feature Branch**: `012-charge-payment`

**Created**: 2026-07-27

**Status**: Draft

**Entrada**: Base feature para charges, payments, payment methods, evidence upload,
consultation and player debt visibility.

## Escenarios de usuario y pruebas *(mandatory)*

### Historia de Usuario 1 - View and gestionar pending charges (Priority: P1)

El sistema permite a academy administrators consult pending charges and the debt
status for a player or membership.

**Por qué esta prioridad**: Financial visibility is required for daily operation.

**Prueba independiente**: Pending charges and debt puede be recuperard for an academy member.

**Escenarios de aceptación**:

1. **Given** a player with pending charges, **When** the admin vers the charges, **Then** the pending obligations are devolvered.
2. **Given** a player with debt, **When** the admin checks the debt ver, **Then** the outstanding balance is visible.

### Historia de Usuario 2 - Register payments and evidence (Priority: P2)

El sistema permite a admins register payments, payment methods and payment evidence.

**Por qué esta prioridad**: Payment registration is the core financial transaction flow.

**Prueba independiente**: A payment puede be registered and evidence puede be attached independently.

**Escenarios de aceptación**:

1. **Given** a pending charge, **When** the admin registers a payment, **Then** the charge becomes reconciled according to the rules.
2. **Given** a payment sin evidence, **When** the admin uploads evidence, **Then** the evidence reference is stored.

### Historia de Usuario 3 - Consult payments and financial history (Priority: P3)

El sistema permite a admins consult payments and financial history.

**Por qué esta prioridad**: Auditing and dar soporte a depend on historical consultation.

**Prueba independiente**: Payment history puede be recuperard and inspected sin side effects.

**Escenarios de aceptación**:

1. **Given** recorded payments, **When** the admin consults them, **Then** the payment listar is devolvered.
2. **Given** an account with financial history, **When** the admin consults it, **Then** the history is visible.

### Casos límite

- What happens when a payment is puedecelled?
- How does the system handle partial payment reconciliation?
- What happens when evidence is uploaded for a non-existent payment?

## Requisitos *(mandatory)*

### Requisitos funcionales

- **FR-001**: System MUST permitir consultation of pending charges.
- **FR-002**: System MUST permitir consultation of player debt.
- **FR-003**: System MUST permitir payment registration.
- **FR-004**: System MUST permitir payment method capture when applicable.
- **FR-005**: System MUST permitir payment evidence upload.
- **FR-006**: System MUST permitir consultation of payments and financial history.
- **FR-007**: System MUST permitir payment puedecellation when business rules permit it.

### Entidades clave *(include if feature involves data)*

- **Charge**: financial obligation pending payment.
- **Payment**: recorded settlement against one or more charges.
- **PaymentEvidence**: proof attached to a payment.
- **PaymentMethod**: method used to register the payment.

## Success Criteria *(mandatory)*

### Resultados medibles

- **SC-001**: Financial obligations puede be vered sin ambiguity.
- **SC-002**: A payment puede be registered and traced to its evidence.
- **SC-003**: History and puedecellation actions remain auditable.

## Assumptions

- Membership may already exist and generar charges.
- Payment evidence storage is already available in el backend.
- Cancellation rules are handled by existing business policies.

