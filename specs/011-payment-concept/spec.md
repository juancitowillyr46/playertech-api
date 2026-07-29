# Payment Concept Feature

**Feature Branch**: `011-payment-concept`

**Created**: 2026-07-27

**Status**: Draft

**Entrada**: Base feature para academy payment concepts, naming and code generation.

## Escenarios de usuario y pruebas *(mandatory)*

### Historia de Usuario 1 - Create payment concepts (Priority: P1)

El sistema permite a academy administrators crear payment concepts.

**Por qué esta prioridad**: Payment concepts are the basis for charges and financial traceability.

**Prueba independiente**: A payment concept puede be creard, queried and validated for uniqueness.

**Escenarios de aceptación**:

1. **Given** a valid unique name, **When** the admin crears the concept, **Then** the concept is stored.
2. **Given** a duplicate name, **When** the admin crears the concept, **Then** the operation is rejected.

### Historia de Usuario 2 - Generate payment concept codes (Priority: P2)

The system generars the payment concept code automatically from the name.

**Por qué esta prioridad**: The code debe be consistent and not manually edited.

**Prueba independiente**: A code is generard from the concept name and remains immutable on actualizar.

**Escenarios de aceptación**:

1. **Given** a valid concept name, **When** the concept is creard, **Then** a normalized code is generard.
2. **Given** a repeated normalized code, **When** another concept is creard, **Then** the system resolves the collision deterministically.

### Historia de Usuario 3 - Manage concept lifecycle (Priority: P3)

El sistema permite a academy administrators listar, actualizar and deactivate concepts.

**Por qué esta prioridad**: The concept catalog debe remain usable sin deleting history.

**Prueba independiente**: Concepts puede be listared, actualizard and deactivated independently.

**Escenarios de aceptación**:

1. **Given** an existing concept, **When** the admin actualizars it, **Then** the change is persisted.
2. **Given** an active concept, **When** the admin deactivates it, **Then** it no longer appears as active.

### Casos límite

- What happens when two names normalize to the same code?
- What happens when the concept is deactivated but still referenced by charges?
- What happens when el frontend sends a code manually?

## Requisitos *(mandatory)*

### Requisitos funcionales

- **FR-001**: System MUST permitir payment concept creation.
- **FR-002**: System MUST generar payment concept codes in backend.
- **FR-003**: System MUST prevent manual code editing from frontend.
- **FR-004**: System MUST permitir payment concept listaring and detail retrieval.
- **FR-005**: System MUST permitir payment concept actualizar.
- **FR-006**: System MUST permitir payment concept deactivation mientras preserving history.

### Entidades clave *(include if feature involves data)*

- **PaymentConcept**: catalog item that defines the reason for a charge.

## Success Criteria *(mandatory)*

### Resultados medibles

- **SC-001**: Payment concept creation is independent and testable.
- **SC-002**: Code generation is deterministic and backend-owned.
- **SC-003**: The active/inactive lifecycle remains traceable.

## Assumptions

- Payment concepts are con scope tenant to one academy.
- Charges and payments reference valid concepts.

