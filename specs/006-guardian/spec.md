# Guardian Feature

**Feature Branch**: `006-guardian`

**Created**: 2026-07-27

**Status**: Draft

**Entrada**: Base feature para guardian lifecycle, listing, detail y creation.

## Escenarios de usuario y pruebas *(mandatory)*

### Historia de Usuario 1 - Guardian creation and profile management (Priority: P1)

El sistema permite a los administradores de academia crear y gestionar guardian profiles.

**Por qué esta prioridad**: Los guardians son necesarios para completar los flujos administrativos de player.

**Prueba independiente**: Un guardian puede crearse y luego consultarse o actualizarse.

**Escenarios de aceptación**:

1. **Given** datos válidos de guardian, **When** el admin crea un guardian, **Then** el guardian se almacena.
2. **Given** un guardian existente, **When** el admin lo actualiza, **Then** los nuevos datos quedan persistidos.

### Historia de Usuario 2 - Guardian listing and detail retrieval (Priority: P2)

El sistema permite a los administradores listar y consultar guardians dentro del scope de la academia.

**Por qué esta prioridad**: El trabajo operativo necesita un directorio de guardians confiable.

**Prueba independiente**: Un guardian puede listarse y consultarse por endpoint de detalle.

**Escenarios de aceptación**:

1. **Given** guardians en la academia, **When** el admin los lista, **Then** la respuesta devuelve datos paginados.
2. **Given** un identificador de guardian, **When** el admin lo consulta, **Then** el detalle del guardian se devuelve.

### Historia de Usuario 3 - Guardian operational data support (Priority: P3)

El sistema almacena datos de guardian útiles para operaciones de contacto y payment.

**Por qué esta prioridad**: Los guardian records dan soporte a membership y payment flows downstream.

**Prueba independiente**: Los datos de contacto usados por otras features están disponibles en el profile del guardian.

**Escenarios de aceptación**:

1. **Given** un profile de guardian, **When** el admin registra datos de contacto, **Then** los valores quedan retenidos.
2. **Given** un guardian activo, **When** membership o payment flows lo referencian, **Then** los datos del guardian están disponibles.

### Casos límite

- ¿Qué ocurre cuando se crea un guardian con información de contacto incompleta?
- ¿Cómo maneja el sistema guardians duplicados dentro de la misma academia?
- ¿Qué ocurre cuando un guardian se elimina o se inactiva?

## Requisitos *(mandatory)*

### Requisitos funcionales

- **FR-001**: El sistema MUST permitir la creación de guardians.
- **FR-002**: El sistema MUST permitir la actualización del profile de guardian.
- **FR-003**: El sistema MUST permitir el listing y la consulta de detalle de guardians.
- **FR-004**: El sistema MUST retener los datos de contacto de guardian para flujos downstream.
- **FR-005**: El sistema MUST preservar el aislamiento por tenant para las operaciones de guardian.

### Entidades clave *(include if feature involves data)*

- **Guardian**: persona legal o responsable vinculada a uno o más players.

## Criterios de éxito *(mandatory)*

### Resultados medibles

- **SC-001**: Los records de guardian son testeables de forma independiente.
- **SC-002**: Los datos de guardian pueden dar soporte a player, membership y payment flows.
- **SC-003**: Las respuestas de listing y detalle permanecen estables para el uso del frontend.

## Suposiciones

- Los guardian records se usan en membership y payment flows.
- El backend ya da soporte a aislamiento de datos con scope tenant.
