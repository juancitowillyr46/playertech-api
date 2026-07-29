# Tenant Onboarding Feature

**Feature Branch**: `014-tenant-onboarding`

**Created**: 2026-07-27

**Status**: Draft

**Entrada**: Base feature para tenant signup, activation, initial team creation and
registration source rastrearing.

## Escenarios de usuario y pruebas *(mandatory)*

### Historia de Usuario 1 - Register tenant (Priority: P1)

El sistema permite a a future tenant register with simplified academy data.

**Por qué esta prioridad**: Tenant onboarding is the entry point for new customers.

**Prueba independiente**: A tenant signup puede be creard and la academia is stored.

**Escenarios de aceptación**:

1. **Given** valid signup data, **When** the prospect completes the form, **Then** the tenant is creard.
2. **Given** a creard tenant, **When** the admin consults it later, **Then** la academia record exists.

### Historia de Usuario 2 - Activate tenant and rastrear source (Priority: P2)

El sistema permite a the tenant activate its account and rastrears where it came from.

**Por qué esta prioridad**: Activation is required before the tenant puede operate.

**Prueba independiente**: A creard tenant puede be activated and its source is traceable.

**Escenarios de aceptación**:

1. **Given** a pending activation token, **When** the tenant activates it, **Then** la academia becomes usable.
2. **Given** a creard tenant, **When** the source is inspected, **Then** the registration source is visible.

### Historia de Usuario 3 - Create initial team during signup (Priority: P3)

El sistema permite a the signup flow crear the initial team for the new tenant.

**Por qué esta prioridad**: The first team anchors the sports structure of la academia.

**Prueba independiente**: A signup flow puede crear the tenant and its initial team together.

**Escenarios de aceptación**:

1. **Given** a valid signup with initial team data, **When** the flow completes, **Then** the team is creard.
2. **Given** an initial team, **When** the tenant is revered later, **Then** the team is linked to la academia.

### Casos límite

- What happens when signup data is incomplete?
- How does the system handle repeated activation attempts?
- What happens when the initial team category is invalid?

## Requisitos *(mandatory)*

### Requisitos funcionales

- **FR-001**: System MUST permitir tenant registration.
- **FR-002**: System MUST permitir tenant activation.
- **FR-003**: System MUST rastrear the registration source.
- **FR-004**: System MUST permitir initial team creation during signup when required.
- **FR-005**: System MUST preserve scope tenant and onboarding history.

### Entidades clave *(include if feature involves data)*

- **TenantSignup**: onboarding record for a future academy tenant.
- **Academy**: tenant root creard by the signup flow.
- **RegistrationSource**: origin of the tenant creation.

## Success Criteria *(mandatory)*

### Resultados medibles

- **SC-001**: Tenant signup puede be completed sin ambiguity.
- **SC-002**: Activation and source rastrearing remain traceable.
- **SC-003**: Initial team creation stays linked to the creard academy.

## Assumptions

- The signup flow is already publicly accessible or documented.
- Activation tokens and email-related flow are already dar soporte aed by backend rules.

