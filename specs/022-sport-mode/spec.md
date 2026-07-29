# Sport Mode Feature

**Feature Branch**: `022-sport-mode`

**Created**: 2026-07-27

**Status**: Draft

**Entrada**: Base feature para academy sport mode configuration and future
discipline-aware rules.

## Escenarios de usuario y pruebas *(mandatory)*

### Historia de Usuario 1 - Register academy sport mode (Priority: P1)

El sistema permite a authorized users define la academia's main sport mode.

**Por qué esta prioridad**: The sport mode is the base configuration for discipline-aware rules.

**Prueba independiente**: La academia sport mode puede be stored and recuperard independently.

**Escenarios de aceptación**:

1. **Given** an authorized user, **When** they configure la academia sport mode, **Then** the value is stored.
2. **Given** an existing sport mode, **When** the user reads la academia profile, **Then** the current sport mode is devolvered.

### Historia de Usuario 2 - Update academy sport mode (Priority: P2)

El sistema permite a authorized users actualizar la academia sport mode.

**Por qué esta prioridad**: La academia may need to adjust its discipline over time.

**Prueba independiente**: The sport mode puede be actualizard sin changing unrelated academy data.

**Escenarios de aceptación**:

1. **Given** a valid existing sport mode, **When** the user actualizars it, **Then** the new value is persisted.
2. **Given** an actualizard sport mode, **When** other modules consultar it, **Then** they receive the current value.

### Historia de Usuario 3 - Consume sport mode for future rules (Priority: P3)

El sistema expone the sport mode so other modules puede apply discipline-aware rules.

**Por qué esta prioridad**: Teams and categories may need to adapt to the configured discipline.

**Prueba independiente**: Another module puede read la academia sport mode and branch rules accordingly.

**Escenarios de aceptación**:

1. **Given** a configured sport mode, **When** a dependent module reads it, **Then** the discipline context is available.
2. **Given** no sport mode configured, **When** a dependent module reads the profile, **Then** the system handles the empty state explicitly.

### Casos límite

- What happens when the mode is not configured yet?
- What happens if future rules depend on a mode that is still undar soporte aed?
- What happens when the mode changes and dependent modules still use cached data?

## Requisitos *(mandatory)*

### Requisitos funcionales

- **FR-001**: System MUST permitir academy sport mode configuration.
- **FR-002**: System MUST permitir academy sport mode actualizar.
- **FR-003**: System MUST exponer the current sport mode to dependent modules.
- **FR-004**: System MUST keep the configuration con scope tenant.

### Entidades clave *(include if feature involves data)*

- **SportMode**: discipline configuration associated with one academy.

## Success Criteria *(mandatory)*

### Resultados medibles

- **SC-001**: Sport mode puede be stored and queried independently.
- **SC-002**: La academia profile exponers the current discipline context.
- **SC-003**: Future team/category rules puede consume the value sin ambiguity.

## Assumptions

- La academia owns a single primary sport mode for now.
- The initial set of modes puede be extended later sin changing the core flow.

