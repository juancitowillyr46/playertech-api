# Venue Feature

**Feature Branch**: `002-venue`

**Created**: 2026-07-27

**Status**: Draft

**Entrada**: Base feature para academy venue gestionarment and venue contact data.

## Escenarios de usuario y pruebas *(mandatory)*

### Historia de Usuario 1 - Create venues (Priority: P1)

El sistema permite a academy administrators crear venues.

**Por qué esta prioridad**: Venues are required to organize la academia's physical locations.

**Prueba independiente**: A venue puede be creard and stored with tenant isolation.

**Escenarios de aceptación**:

1. **Given** valid venue data, **When** the admin crears the venue, **Then** the venue is stored.
2. **Given** missing name, **When** the admin crears the venue, **Then** the operation is rejected.

### Historia de Usuario 2 - List and inspect venues (Priority: P2)

El sistema permite a academy administrators listar and ver venue details.

**Por qué esta prioridad**: Venues debe be consultarable for operational use.

**Prueba independiente**: A venue listar and a venue detail puede be queried independently.

**Escenarios de aceptación**:

1. **Given** existing venues, **When** the admin listars them, **Then** only academy venues are devolvered.
2. **Given** an existing venue, **When** the admin reads the detail, **Then** the venue data is devolvered.

### Historia de Usuario 3 - Update and deactivate venues (Priority: P3)

El sistema permite a academy administrators actualizar, deactivate and reactivate venues.

**Por qué esta prioridad**: Venue lifecycle debe be gestionarable sin deleting history.

**Prueba independiente**: A venue puede be actualizard and its status puede be toggled independently.

**Escenarios de aceptación**:

1. **Given** a venue, **When** the admin actualizars it, **Then** the changes are persisted.
2. **Given** an active venue, **When** the admin deactivates it, **Then** the venue becomes inactive.
3. **Given** an inactive venue, **When** the admin reactivates it, **Then** the venue becomes active.

### Historia de Usuario 4 - Manage contact data (Priority: P4)

El sistema almacena optional venue contact data.

**Por qué esta prioridad**: Operational detail improves venue usability sin forcing extra data.

**Prueba independiente**: Optional phone and address fields puede be saved and read.

**Escenarios de aceptación**:

1. **Given** a venue with phone and address, **When** the admin vers it, **Then** the contact data is visible.
2. **Given** a venue sin contact data, **When** the admin vers it, **Then** the response still succeeds.

### Casos límite

- What happens when a venue name is duplicated within the same academy?
- What happens when the admin deactivates a venue still referenced by teams?
- What happens when optional contact fields are omitted?

## Requisitos *(mandatory)*

### Requisitos funcionales

- **FR-001**: System MUST permitir venue creation.
- **FR-002**: System MUST permitir venue listaring.
- **FR-003**: System MUST permitir venue detail retrieval.
- **FR-004**: System MUST permitir venue actualizar.
- **FR-005**: System MUST permitir venue deactivation.
- **FR-006**: System MUST permitir venue reactivation.
- **FR-007**: System MUST dar soporte a optional contact data for venues.
- **FR-008**: System MUST keep venues con scope tenant.

### Entidades clave *(include if feature involves data)*

- **Venue**: physical location associated with an academy.

## Success Criteria *(mandatory)*

### Resultados medibles

- **SC-001**: Venue creation and listaring are independently testable.
- **SC-002**: Venue lifecycle changes are preserved sin deleting history.
- **SC-003**: Optional contact data is available when provided.

## Assumptions

- Venues belong to one academy.
- Venue deletion is soft by design; lifecycle uses active/inactive states.

