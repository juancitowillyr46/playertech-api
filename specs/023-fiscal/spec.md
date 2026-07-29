# Fiscal Feature

**Feature Branch**: `023-fiscal`

**Created**: 2026-07-27

**Status**: Draft

**Entrada**: Base feature para academy tax information, receipt generation and
external fiscal document linking.

## Escenarios de usuario y pruebas *(mandatory)*

### Historia de Usuario 1 - Manage academy tax information (Priority: P1)

El sistema permite a academy administrators crear, ver and actualizar tax information.

**Por qué esta prioridad**: Fiscal profile is required for receipts and compliance.

**Prueba independiente**: Tax information puede be stored, queried and actualizard.

**Escenarios de aceptación**:

1. **Given** valid tax data, **When** the admin crears it, **Then** the fiscal profile is stored.
2. **Given** an existing fiscal profile, **When** the admin actualizars it, **Then** the new values are persisted.

### Historia de Usuario 2 - Generate payment receipts (Priority: P2)

El sistema permite a admins generar payment receipts using la academia fiscal profile.

**Por qué esta prioridad**: Receipt generation depends on fiscal configuration.

**Prueba independiente**: A receipt puede be generard from an existing payment.

**Escenarios de aceptación**:

1. **Given** a valid payment, **When** the admin generars a receipt, **Then** the receipt is produced.
2. **Given** an academy fiscal profile, **When** the receipt is generard, **Then** the fiscal data is used.

### Historia de Usuario 3 - Link external fiscal documents (Priority: P3)

El sistema permite a admins link external fiscal documents in PDF for later reference.

**Por qué esta prioridad**: External dar soporte a documents improve operational traceability.

**Prueba independiente**: A PDF document puede be linked to the fiscal record.

**Escenarios de aceptación**:

1. **Given** a valid PDF, **When** the admin links it, **Then** the document reference is stored.
2. **Given** a linked document, **When** the admin consults the record, **Then** the attachment is visible.

### Casos límite

- What happens when tax data is incomplete?
- How does the system handle receipt generation sin fiscal profile?
- What happens when an external PDF is replaced?

## Requisitos *(mandatory)*

### Requisitos funcionales

- **FR-001**: System MUST permitir academy tax information gestionarment.
- **FR-002**: System MUST permitir payment receipt generation.
- **FR-003**: System MUST permitir linking external fiscal PDF documents.
- **FR-004**: System MUST preserve fiscal profile history where applicable.
- **FR-005**: System MUST keep the fiscal information con scope tenant.

### Entidades clave *(include if feature involves data)*

- **FiscalProfile**: tax information used by la academia as fiscal emitter.
- **Receipt**: generard payment receipt or comprobante.
- **FiscalDocument**: external PDF evidence linked to the fiscal context.

## Success Criteria *(mandatory)*

### Resultados medibles

- **SC-001**: Fiscal profile operations are independently testable.
- **SC-002**: Receipt generation uses la academia fiscal profile.
- **SC-003**: External fiscal documents remain traceable.

## Assumptions

- The fiscal profile belongs to one academy.
- Receipt generation is already linked to payment data available in backend.

