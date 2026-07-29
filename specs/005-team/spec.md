# Team Feature

**Feature Branch**: `005-team`

**Created**: 2026-07-27

**Status**: Draft

**Entrada**: Base feature para team lifecycle, listing, detail, update y state management dentro de la estructura deportiva de la academia.

## Escenarios de usuario y pruebas *(mandatory)*

### Historia de Usuario 1 - Team registration and profile management (Priority: P1)

El sistema permite a los administradores de academia crear, ver y actualizar teams.

**Por qué esta prioridad**: Los teams son la estructura competitiva central alrededor de los players.

**Prueba independiente**: Un team puede crearse, listarse, verse y actualizarse dentro del scope de la academia.

**Escenarios de aceptación**:

1. **Given** datos válidos de team, **When** un admin crea el team, **Then** el team se almacena.
2. **Given** un team existente, **When** el admin actualiza el team, **Then** los nuevos datos quedan persistidos.

### Historia de Usuario 2 - Team status management (Priority: P2)

El sistema permite a los administradores desactivar y reactivar teams de forma segura.

**Por qué esta prioridad**: Los teams necesitan un lifecycle controlado sin perder historial.

**Prueba independiente**: Un team puede desactivarse y reactivarse de forma independiente.

**Escenarios de aceptación**:

1. **Given** un team activo, **When** el admin lo desactiva, **Then** el team pasa a inactivo.
2. **Given** un team inactivo, **When** el admin lo reactiva, **Then** el team vuelve a estar activo.

### Historia de Usuario 3 - Team listing and detail enrichment (Priority: P3)

El sistema expone datos de listing y detalle de teams para consumo del frontend.

**Por qué esta prioridad**: El frontend necesita datos estables para tablas y vistas de detalle.

**Prueba independiente**: Los teams pueden listarse y consultarse con la estructura de respuesta esperada.

**Escenarios de aceptación**:

1. **Given** teams en la academia, **When** el admin los lista, **Then** la respuesta devuelve datos paginados.
2. **Given** un identificador de team, **When** el admin consulta el detalle, **Then** la API devuelve el profile del team.

### Casos límite

- ¿Qué ocurre cuando se crea un team sin category?
- ¿Cómo maneja el sistema nombres duplicados dentro de la misma academia?
- ¿Qué ocurre cuando se actualiza un team desactivado?

## Requisitos *(mandatory)*

### Requisitos funcionales

- **FR-001**: El sistema MUST permitir el registro de teams dentro del scope de la academia.
- **FR-002**: El sistema MUST permitir la actualización del profile de team.
- **FR-003**: El sistema MUST permitir el listing y la consulta de detalle de teams.
- **FR-004**: El sistema MUST permitir la desactivación y reactivación de teams.
- **FR-005**: El sistema MUST preservar el historial del team mediante state transitions.

### Entidades clave *(include if feature involves data)*

- **Team**: grupo competitivo que pertenece a una academia y a una category.

## Criterios de éxito *(mandatory)*

### Resultados medibles

- **SC-001**: Las operaciones del lifecycle de Team son testeables de forma independiente.
- **SC-002**: Las respuestas de listing y detalle de Team son estables para el uso del frontend.
- **SC-003**: Los state transitions no destruyen el contexto histórico.

## Suposiciones

- El team debe seguir perteneciendo a una category.
- El aislamiento por tenant sigue siendo aplicado por el backend.
