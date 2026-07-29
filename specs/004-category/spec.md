# Category Feature

**Feature Branch**: `004-category`

**Created**: 2026-07-27

**Status**: Draft

**Entrada**: Base feature para category lifecycle, listing, detail, update, state management y business key support.

## Escenarios de usuario y pruebas *(mandatory)*

### Historia de Usuario 1 - Category registration and profile management (Priority: P1)

El sistema permite a los administradores de academia crear, ver y actualizar categories.

**Por qué esta prioridad**: Categories organize la estructura deportiva de la academia.

**Prueba independiente**: Una category puede crearse, listarse, verse y actualizarse dentro del scope de la academia.

**Escenarios de aceptación**:

1. **Given** datos válidos de category, **When** un admin crea la category, **Then** la category se almacena.
2. **Given** una category existente, **When** el admin la actualiza, **Then** los nuevos datos quedan persistidos.

### Historia de Usuario 2 - Category state management (Priority: P2)

El sistema permite a los administradores activar y desactivar categories de forma segura.

**Por qué esta prioridad**: Las categories necesitan un lifecycle controlado sin perder historial.

**Prueba independiente**: Una category puede activarse y desactivarse de forma independiente.

**Escenarios de aceptación**:

1. **Given** una category activa, **When** el admin la desactiva, **Then** la category pasa a inactiva.
2. **Given** una category inactiva, **When** el admin la activa, **Then** la category vuelve a estar activa.

### Historia de Usuario 3 - Category options and business key support (Priority: P3)

El sistema expone category options y una business key estable para uso del frontend.

**Por qué esta prioridad**: `Player`, la creación de teams y los import flows dependen de una referencia estable de category.

**Prueba independiente**: Las categories pueden listarse como opciones activas y la business key se mantiene estable.

**Escenarios de aceptación**:

1. **Given** categories activas, **When** el frontend solicita las options, **Then** la respuesta devuelve solo entries activas.
2. **Given** un registro de category, **When** se requiere la business key, **Then** el backend expone una key estable para uso contractual.

### Casos límite

- ¿Qué ocurre cuando dos categories entran en conflicto por nombre?
- ¿Cómo maneja el sistema la desactivación de una category mientras players ya pertenecen a ella?
- ¿Qué ocurre cuando una category se usa en import o team creation flows?

## Requisitos *(mandatory)*

### Requisitos funcionales

- **FR-001**: El sistema MUST permitir la creación de categories.
- **FR-002**: El sistema MUST permitir la actualización del profile de category.
- **FR-003**: El sistema MUST permitir el listing y la consulta de detalle de categories.
- **FR-004**: El sistema MUST permitir la activación y desactivación de categories.
- **FR-005**: El sistema MUST exponer category options activas para selección del frontend.
- **FR-006**: El sistema MUST exponer una business key estable para uso contractual.

### Entidades clave *(include if feature involves data)*

- **Category**: clasificación deportiva usada para organizar players y teams.

## Criterios de éxito *(mandatory)*

### Resultados medibles

- **SC-001**: Los flujos de lifecycle de Category son testeables de forma independiente.
- **SC-002**: Las options activas dan soporte a selectores e imports del frontend.
- **SC-003**: La business key permanece estable a través del backend.

## Suposiciones

- Category sigue perteneciendo a una sola academia.
- El backend ya aplica unicidad y scope tenant.
