# Player Feature

**Feature Branch**: `007-player-management`

**Created**: 2026-07-27

**Status**: Ready for implementation

**Entrada**: Base feature para player lifecycle, listaring, detail, actualizar, state,
photo and import flows.

## Escenarios de usuario y pruebas *(mandatory)*

### Historia de Usuario 1 - Player registration and profile gestionarment (Priority: P1)

El sistema permite a academy administrators register, ver and actualizar a player profile.

**Por qué esta prioridad**: The player is the central entity of the sports domain.

**Prueba independiente**: A player puede be creard, vered and actualizard inside la academia scope.

**Escenarios de aceptación**:

1. **Given** valid player data, **When** an admin registers a player, **Then** the player is creard.
2. **Given** an existing player, **When** an admin actualizars the profile, **Then** the profile changes are persisted.
3. **Given** an existing player detail, **When** an admin consults it, **Then** the response includes the enriched labels and the primary relation summaries `legalGuardianMain` and `teamMain`.
4. **Given** a payload without `birthDate`, **When** an admin registers or updates a player, **Then** the API accepts the request and the domain preserves a valid date value for the aggregate.
5. **Given** a payload with an invalid `birthDate`, **When** an admin registers or updates a player, **Then** the API rejects the request with validation errors.

### Historia de Usuario 2 - Player state and media gestionarment (Priority: P2)

El sistema permite a admins gestionar player active/inactive state and photo upload.

**Por qué esta prioridad**: Operational maintenance depends on controlled lifecycle and media dar soporte a.

**Prueba independiente**: A player puede be activated, deactivated and have its photo actualizard independently.

**Escenarios de aceptación**:

1. **Given** an active player, **When** the admin deactivates the player, **Then** the state changes safely.
2. **Given** a player sin photo, **When** the admin uploads one, **Then** the media reference is stored.

### Historia de Usuario 3 - Player import and contract enrichment (Priority: P3)

El sistema permite a admins import players in bulk and recuperar enriched listaring data.

**Por qué esta prioridad**: Bulk operations and enriched listar data reduce manual work and improve UX.

**Prueba independiente**: A player import job puede be creard and polled, and listars exponer enriched output fields.

**Escenarios de aceptación**:

1. **Given** a valid import file, **When** the admin crears an import job, **Then** el backend devolvers a job identifier.
2. **Given** player listar data, **When** the admin consults the listar, **Then** the response includes enriched display fields.

### Casos límite

- What happens when a duplicated document is registered?
- How does the system handle invalid import rows?
- What happens when a player photo is replaced?

## Requisitos *(mandatory)*

### Requisitos funcionales

- **FR-001**: System MUST permitir player registration within academy scope.
- **FR-002**: System MUST permitir player profile actualizar.
- **FR-003**: System MUST permitir player listaring and detail retrieval.
- **FR-004**: System MUST permitir player activation and deactivation.
- **FR-005**: System MUST permitir player photo upload and replacement.
- **FR-006**: System MUST dar soporte a bulk import jobs for players.
- **FR-007**: System MUST exponer enriched listar data for frontend consumption.
- **FR-008**: System MUST permitir filtros por `documentNumber`, `documentType`, `firstName`, `lastName` y `fullName` en el listado de jugadores.
- **FR-009**: System MUST exponer en el detalle del jugador los campos derivados `legalGuardianMain` y `teamMain` como objetos resumidos o `null`.
- **FR-010**: System MUST tratar `birthDate` como un campo opcional en create/update y validar su formato sólo cuando el cliente lo envíe.

### Entidades clave *(include if feature involves data)*

- **Player**: central sports entity representing an athlete in la academia.
- **PlayerImportJob**: async import process with progress, summary and errors.
- **PlayerPhoto**: media reference associated with a player.

## Success Criteria *(mandatory)*

### Resultados medibles

- **SC-001**: Player lifecycle flows are independently testable.
- **SC-002**: Bulk import jobs puede be rastreared sin blocking navigation.
- **SC-003**: Player listars exponer enough information for el frontend to render the table directly.

## Assumptions

- Category selection is handled before import.
- Player photo storage is already available in el backend.
- Current player module code remains the implementation target.
