# Dashboard Feature

**Feature Branch**: `013-dashboard`

**Created**: 2026-07-27

**Status**: Draft

**Entrada**: Base feature para operational dashboard vers including active players,
pending payments, active memberships and cashflow summary.

## Escenarios de usuario y pruebas *(mandatory)*

### Historia de Usuario 1 - View active players summary (Priority: P1)

El sistema permite a academy administrators quickly ver active player data.

**Por qué esta prioridad**: The dashboard debe answer operational questions fast.

**Prueba independiente**: Active player summary data puede be recuperard independently.

**Escenarios de aceptación**:

1. **Given** active players exist, **When** the admin opens the dashboard, **Then** the summary is devolvered.
2. **Given** no active players, **When** the admin opens the dashboard, **Then** the empty summary is devolvered safely.

### Historia de Usuario 2 - View pending payments and active memberships (Priority: P2)

El sistema permite a admins see pending payments and active memberships at a glance.

**Por qué esta prioridad**: Financial and administrative visibility is the main dashboard value.

**Prueba independiente**: Pending payments and active memberships are devolvered in a single dashboard ver.

**Escenarios de aceptación**:

1. **Given** pending payments exist, **When** the admin vers the dashboard, **Then** the pending amount is visible.
2. **Given** active memberships exist, **When** the admin vers the dashboard, **Then** the membership count is visible.

### Historia de Usuario 3 - View cashflow summary (Priority: P3)

El sistema permite a admins see a cashflow summary for la academia.

**Por qué esta prioridad**: Cashflow overver dar soporte as daily operational decisions.

**Prueba independiente**: A cashflow summary puede be recuperard sin mutating data.

**Escenarios de aceptación**:

1. **Given** financial movements exist, **When** the admin vers the cashflow summary, **Then** the summary is devolvered.
2. **Given** no financial movements exist, **When** the admin vers the summary, **Then** a safe empty response is devolvered.

### Casos límite

- What happens when one summary section has no data?
- How does the system handle partial data across connected modules?
- What happens when dashboard queries are filtered by academy scope?

## Requisitos *(mandatory)*

### Requisitos funcionales

- **FR-001**: System MUST exponer active players summary data.
- **FR-002**: System MUST exponer pending payments summary data.
- **FR-003**: System MUST exponer active memberships summary data.
- **FR-004**: System MUST exponer cashflow summary data.
- **FR-005**: System MUST keep dashboard data read-only.

### Entidades clave *(include if feature involves data)*

- **DashboardResumen**: aggregated read model for operational visibility.

## Success Criteria *(mandatory)*

### Resultados medibles

- **SC-001**: Operational questions puede be answered from a single dashboard ver.
- **SC-002**: Dashboard responses remain read-only and con scope tenant.
- **SC-003**: Resumen data is stable enough for frontend rendering.

## Assumptions

- Dashboard data is a read model, not a transactional source of truth.
- The summary may aggregate information from multiple already-defined features.

