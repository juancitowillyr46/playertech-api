# Membership Feature

**Feature Branch**: `009-membership-management`

**Created**: 2026-07-27

**Status**: Draft

**Entrada**: Base feature para el ciclo administrativo de matrícula de un jugador dentro de una academia.

## Alcance Canónico

Esta feature cubre únicamente el lifecycle administrativo de `Membership`:

- crear matrícula activa;
- consultar matrícula activa;
- consultar historial de matrículas;
- suspender matrícula;
- retirar matrícula.

Las capacidades financieras relacionadas con cargos, pagos, saldo y evidencia no forman parte del núcleo de esta feature. Sin embargo, Membership debe mantener una referencia explícita hacia el bloque financiero para que el producto pueda conectar después los flujos de cobro, pagos y conciliación.

## Contrato Canónico

La creación de una matrícula debe recibir explícitamente:

- `playerId`
- `responsibleGuardianId`
- `categoryId`

Reglas del contrato:

- `playerId` identifica al jugador que se matricula.
- `responsibleGuardianId` identifica al acudiente elegido al momento de la inscripción.
- `categoryId` identifica la categoría de inscripción que debe conservarse como contexto histórico.
- La academia se resuelve desde el tenant autenticado.
- La categoría no se infiere desde el jugador.

## Evolución Del Contrato

La matrícula de `Membership` debe conservar la frontera administrativa, pero el contrato
de creación se amplía para registrar la inscripción con datos explícitos:

- `playerId`
- `responsibleGuardianId`
- `categoryId`

Estos campos permiten:

- identificar al jugador;
- identificar al acudiente elegido para la matrícula;
- conservar la categoría de inscripción como trazabilidad histórica.

La categoría no debe inferirse desde el jugador. Debe venir en el payload para
preservar la verdad del momento en que se realizó la matrícula.

## Escenarios de usuario y pruebas *(mandatory)*

### Historia de Usuario 1 - Create active membership (Priority: P1)

El sistema permite a academy administrators crear una membership para un jugador con acudiente responsable, academia y categoría de inscripción.

**Por qué esta prioridad**: Membership is the administrative gate for belonging to the academy.

**Prueba independiente**: A membership can be created and later queried as active.

**Escenarios de aceptación**:

1. **Given** a valid player, responsible guardian and category, **When** the admin creates a membership, **Then** the membership becomes active.
2. **Given** an existing active membership, **When** the admin queries it, **Then** the API returns the current membership data.

### Historia de Usuario 2 - Membership history and status transitions (Priority: P2)

El sistema permite a admins suspender o retirar memberships y revisar su historial.

**Por qué esta prioridad**: Operational control requires historical traceability.

**Prueba independiente**: Membership status changes can be executed and the history remains visible.

**Escenarios de aceptación**:

1. **Given** an active membership, **When** the admin suspends it, **Then** the status changes while history remains.
2. **Given** a prior membership, **When** the admin views the history, **Then** the historical record is available.

### Historia de Usuario 3 - Financial reference boundary (Priority: P3)

The system keeps membership lifecycle isolated from payment concerns, while preserving the reference to the financial block for later integration.

**Por qué esta prioridad**: The administrative membership flow should remain understandable and independent, but product traceability needs to preserve how it connects to charges and payments.

**Prueba independiente**: A membership lifecycle can be traced without requiring financial side effects inside the same feature, and the financial dependency remains documented.

**Escenarios de aceptación**:

1. **Given** a newly created active membership, **When** the flow completes, **Then** the membership remains available for future financial processing.
2. **Given** a suspended or withdrawn membership, **When** the admin checks it, **Then** the history remains intact.
3. **Given** the product review, **When** I inspect the feature boundary, **Then** the financial dependency is visible without being implemented inside the same feature.

### Historia de Usuario 4 - Membership enrollment contract update (Priority: P1)

El sistema permite crear una matrícula con `responsibleGuardianId` y `categoryId` como parte del contrato canónico.

**Por qué esta prioridad**: The enrollment flow now needs to capture the exact
responsible guardian and the enrollment category at the time of creation.

**Prueba independiente**: The membership can be created using the new explicit contract and later queried with the same historical data.

**Escenarios de aceptación**:

1. **Given** a valid player, guardian and category, **When** the admin creates a membership, **Then** the membership stores the enrollment category and responsible guardian.
2. **Given** a request without `categoryId`, **When** the admin tries to create the membership, **Then** the API rejects the operation.
3. **Given** a request without `responsibleGuardianId`, **When** the admin tries to create the membership, **Then** the API rejects the operation.
4. **Given** an existing membership, **When** I inspect the response, **Then** the contract exposes the enrollment category and responsible guardian fields.

## Cobertura De HUs Del Backlog

Esta feature mantiene trazabilidad completa con las HUs del backlog, pero
separa el núcleo administrativo del contexto financiero relacionado.

| HU | Cobertura en el spec | Lectura |
| --- | --- | --- |
| HU-001 | Núcleo | Crear matrícula administrativa con acudiente responsable y categoría de inscripción. |
| HU-002 | Referencia financiera | Los cargos iniciales se mantienen documentados como boundary del bloque financiero. |
| HU-003 | Núcleo | Consultar matrícula activa. |
| HU-004 | Referencia financiera | Registrar pago pertenece al bloque financiero, no al core de Membership. |
| HU-005 | Núcleo | Consultar historial de matrícula. |
| HU-006 | Núcleo | Suspender matrícula. |
| HU-007 | Referencia financiera | Consultar saldo/deuda pertenece al bloque financiero. |
| HU-008 | Núcleo | Retirar matrícula. |
| HU-009 | Núcleo / Contrato | Ajustar el contrato de matrícula para `responsibleGuardianId` y `categoryId`. |
| HU-010 | Núcleo | Persistir categoría de inscripción. |
| HU-011 | Núcleo | Renombrar la referencia del acudiente a `responsibleGuardianId`. |
| HU-012 | Núcleo | Actualizar el response de matrícula con el contrato nuevo. |
| HU-013 | Núcleo | Validar que la categoría de inscripción exista y pertenezca al tenant. |
| HU-014 | Documentación | Homologar backlog, spec, contracts y tests con el contrato nuevo. |

### Casos límite

- What happens when a player already has an active membership?
- How does the system handle missing responsible guardian data?
- What happens when a membership is suspended or withdrawn twice?

## Requisitos *(mandatory)*

### Requisitos funcionales

- **FR-001**: System MUST allow creation of an active membership for a player.
- **FR-002**: System MUST allow viewing the active membership of a player.
- **FR-003**: System MUST preserve membership history.
- **FR-004**: System MUST allow membership suspension and withdrawal transitions.
- **FR-005**: System MUST preserve an explicit reference to the financial block for future charges, payments, balance, and evidence workflows.
- **FR-006**: System MUST allow membership creation with `responsibleGuardianId` and `categoryId` as explicit contract fields.
- **FR-007**: System MUST preserve the enrollment category as historical membership context.

### Entidades clave *(include if feature involves data)*

- **Membership**: administrative enrollment of a player in an academy.

## Success Criteria *(mandatory)*

### Resultados medibles

- **SC-001**: A membership lifecycle can be traced from creation to history.
- **SC-002**: Status transitions do not erase historical records.
- **SC-003**: The feature remains isolated from financial side effects.
- **SC-004**: The feature keeps a documented integration boundary with the financial block.
- **SC-005**: The membership creation contract captures the selected guardian and enrollment category explicitly.
- **SC-006**: The enrollment category remains available as historical traceability even if the player's category changes later.

## Assumptions

- A responsible guardian exists when membership is created.
- Financial obligations are handled in the payment/charge block, not inside this feature.
- The financial block will consume the membership relationship later without changing the administrative lifecycle contract.
- `responsibleGuardianId` represents the guardian chosen during enrollment, not necessarily a permanently fixed "primary" guardian in every context.
