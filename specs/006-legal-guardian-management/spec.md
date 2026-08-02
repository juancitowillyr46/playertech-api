# Legal Guardian Management

**Feature Branch**: `006-legal-guardian-management`

**Created**: 2026-07-27

**Status**: Implemented

**Entrada**: Gestión de acudientes legales dentro de la academia autenticada.

## Escenarios de usuario y pruebas *(mandatory)*

### Historia de Usuario 1 - Crear acudiente legal (Priority: P1)

El sistema permite a los administradores de academia crear un acudiente legal dentro del contexto de la academia autenticada.

**Por qué esta prioridad**: Los acudientes son necesarios para completar los flujos administrativos de jugadores y cobros.

**Prueba independiente**: Un acudiente puede crearse y luego consultarse por detalle.

**Escenarios de aceptación**:

1. **Given** datos válidos de acudiente, **When** el admin lo crea, **Then** el sistema almacena el acudiente en la academia actual.
2. **Given** un correo duplicado dentro de la misma academia, **When** el admin intenta crear otro acudiente, **Then** el sistema rechaza la operación.

### Historia de Usuario 2 - Listar y consultar detalle de acudientes (Priority: P2)

El sistema permite a los administradores listar y consultar acudientes dentro del scope de la academia.

**Por qué esta prioridad**: El trabajo operativo necesita un directorio de acudientes confiable.

**Prueba independiente**: Un acudiente puede listarse y consultarse por endpoint de detalle.

**Escenarios de aceptación**:

1. **Given** acudientes en la academia, **When** el admin los lista, **Then** la respuesta devuelve datos paginados.
2. **Given** un criterio de ordenamiento válido, **When** el admin consulta el listado, **Then** el backend resuelve el alias seguro correspondiente.
3. **Given** filtros por nombre, apellido o nombre completo, **When** el admin consulta el listado, **Then** la respuesta devuelve sólo los acudientes coincidentes.
4. **Given** un acudiente listado, **When** el frontend consulta la respuesta, **Then** el backend expone `relationshipName` como etiqueta visible del parentesco.
5. **Given** un identificador de acudiente, **When** el admin lo consulta, **Then** el detalle del acudiente se devuelve.

### Historia de Usuario 3 - Soporte de datos operativos del acudiente (Priority: P3)

El sistema almacena datos de acudiente útiles para operaciones de contacto y flujos financieros.

**Por qué esta prioridad**: Los datos del acudiente dan soporte a membership y payment flows downstream.

**Prueba independiente**: Los datos de contacto usados por otras features están disponibles en el profile del acudiente.

**Escenarios de aceptación**:

1. **Given** un profile de acudiente, **When** el admin registra datos de contacto, **Then** los valores quedan retenidos.
2. **Given** un acudiente activo, **When** membership o payment flows lo referencian, **Then** los datos del acudiente están disponibles.

### Casos límite

- ¿Qué ocurre cuando se crea un acudiente con información de contacto incompleta?
- ¿Cómo maneja el sistema acudientes duplicados dentro de la misma academia?
- ¿Qué ocurre cuando un acudiente se inactiva?

### Historia de Usuario 4 - Editar acudiente (Priority: P4)

El sistema permite a los administradores de academia actualizar los datos de un acudiente existente dentro de la academia autenticada.

**Por qué esta prioridad**: Los datos de contacto e identificación pueden cambiar con el tiempo y deben mantenerse al día.

**Prueba independiente**: Un acudiente existente puede editarse sin afectar otros registros de la academia.

**Escenarios de aceptación**:

1. **Given** un acudiente existente y datos válidos, **When** el admin lo actualiza, **Then** el sistema persiste los cambios.
2. **Given** un correo duplicado dentro de la misma academia, **When** el admin actualiza el acudiente, **Then** el sistema rechaza la operación.

### Historia de Usuario 5 - Inactivar acudiente (Priority: P5)

El sistema permite a los administradores de academia inactivar un acudiente sin eliminar su historial.

**Por qué esta prioridad**: La operación activa requiere retirar acudientes sin perder trazabilidad.

**Prueba independiente**: Un acudiente activo puede pasar a inactivo sin borrar su registro.

**Escenarios de aceptación**:

1. **Given** un acudiente activo, **When** el admin lo inactiva, **Then** el estado cambia a inactivo.
2. **Given** un acudiente ya inactivo, **When** se repite la operación, **Then** el sistema conserva el estado consistente.

### Historia de Usuario 6 - Reactivar acudiente (Priority: P6)

El sistema permite a los administradores de academia reactivar un acudiente previamente inactivado.

**Por qué esta prioridad**: Un acudiente puede volver a ser operativo sin crear un registro nuevo.

**Prueba independiente**: Un acudiente inactivo puede volver a activo manteniendo su identidad.

**Escenarios de aceptación**:

1. **Given** un acudiente inactivo, **When** el admin lo reactiva, **Then** el estado cambia a activo.
2. **Given** un acudiente activo, **When** se intenta reactivarlo, **Then** el sistema informa que no aplica o mantiene el estado.

### Historia de Usuario 7 - Seleccionar jugadores disponibles para asociar (Priority: P7)

El sistema permite al frontend consultar un selector liviano de jugadores disponibles para asociar a un acudiente, usando autocomplete.

**Por qué esta prioridad**: La asociación jugador-acudiente necesita una UX rápida que no incluya jugadores ya vinculados al mismo acudiente.

**Prueba independiente**: Un acudiente puede consultar los jugadores aún no asociados a él y filtrar por texto parcial.

**Escenarios de aceptación**:

1. **Given** un acudiente con algunos jugadores ya asociados, **When** el frontend consulta el selector, **Then** la respuesta excluye los jugadores ya vinculados.
2. **Given** un texto parcial, **When** el frontend consulta el selector con `q`, **Then** el sistema devuelve coincidencias livianas para autocomplete.
3. **Given** un `guardianId` inválido o fuera del tenant, **When** se consulta el selector, **Then** el sistema responde con el error de dominio correspondiente.

## Requisitos *(mandatory)*

### Requisitos funcionales

- **FR-001**: El sistema MUST permitir la creación de acudientes.
- **FR-002**: El sistema MUST permitir el listado de acudientes por academia.
- **FR-003**: El sistema MUST permitir la consulta de detalle de un acudiente por identificador.
- **FR-004**: El sistema MUST retener los datos de contacto de acudiente para flujos downstream.
- **FR-005**: El sistema MUST preservar el aislamiento por tenant para las operaciones de acudiente.
- **FR-006**: El sistema MUST permitir la actualización de acudientes dentro de la academia autenticada.
- **FR-007**: El sistema MUST permitir inactivar acudientes sin borrar su historial.
- **FR-008**: El sistema MUST permitir reactivar acudientes previamente inactivados.
- **FR-009**: El sistema MUST permitir filtros por `documentNumber`, `documentType`, `firstName`, `lastName` y `fullName` en el listado de acudientes.
- **FR-010**: El sistema MUST normalizar el ordenamiento del listado de acudientes con aliases seguros.
- **FR-011**: El sistema MUST tratar la búsqueda textual como case-insensitive y accent-insensitive.
- **FR-012**: El sistema MUST exponer `relationshipName` como label visible del parentesco en los responses del módulo.
- **FR-013**: El sistema MUST permitir listar los jugadores asociados a un acudiente autenticado.
- **FR-014**: El sistema MUST permitir listar jugadores disponibles para asociar a un acudiente usando un contrato tipo autocomplete que excluya los ya asociados.

### Entidades clave *(include if feature involves data)*

- **LegalGuardian**: persona legal o responsable vinculada a uno o más players.

## Criterios de éxito *(mandatory)*

### Resultados medibles

- **SC-001**: Los records de acudiente son testeables de forma independiente.
- **SC-002**: Los datos de acudiente pueden dar soporte a player, membership y payment flows.
- **SC-003**: Las respuestas de listing y detalle permanecen estables para el uso del frontend.

## Suposiciones

- Los acudientes se usan en membership y payment flows.
- El backend ya da soporte a aislamiento de datos con scope tenant.
