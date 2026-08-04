# Player Import Subfeature

**Feature Branch**: `007-player-management/import`

**Creado**: 2026-07-29

**Estado**: Ready for implementation

**Alcance**: Importación asíncrona de jugadores desde Excel, con plantilla oficial generada por backend, categoría seleccionada previamente, polling de progreso y reporte de errores por fila.

## Escenarios de usuario y pruebas *(mandatory)*

### Historia de Usuario 1 - Import players in bulk

El sistema permite importar jugadores en lote sin bloquear la navegación del usuario.

**Prueba independiente**: Un usuario puede crear un import job y consultar su estado hasta llegar a un estado terminal.

**Escenarios de aceptación**:

1. **Given** un archivo Excel válido, **When** el admin crea el job, **Then** el backend responseonde con un identificador del proceso.
2. **Given** un job en progreso, **When** el frontend consulta su estado, **Then** el backend devuelve progreso, summary y errores si aplica.

### Historia de Usuario 2 - Official template and reference data

El sistema permite descargar una plantilla oficial con hojas `Datos` y `Referencias`.

**Prueba independiente**: La plantilla descargada corresponde al contrato oficial y refleja la verdad del tenant autenticado.

**Escenarios de aceptación**:

1. **Given** categorías activas en el tenant, **When** el admin descarga la plantilla, **Then** el backend entrega un `.xlsx` con referencias válidas y una hoja `Referencias` legible.
2. **Given** un job de importación, **When** existen errores parciales, **Then** los registros válidos se conservan y los errores quedan reportados.

### Casos límite

- ¿Qué ocurre si el archivo no es un `.xlsx` válido?
- ¿Qué ocurre si la categoría seleccionada no existe o está inactiva?
- ¿Qué ocurre si el job termina con errores parciales?

## Requisitos *(mandatory)*

### Requisitos funcionales

- **FR-001**: El sistema MUST permitir que el usuario seleccione la categoría antes de subir el archivo.
- **FR-002**: El sistema MUST generar una plantilla Excel oficial desde backend.
- **FR-003**: El sistema MUST crear un async import job desde `multipart/form-data`.
- **FR-004**: El sistema MUST exponer el progreso del job mediante polling.
- **FR-005**: El sistema MUST devolver summary y errores por fila cuando el import finaliza.
- **FR-006**: El sistema MUST dar soporte a terminal states: `COMPLETED`, `COMPLETED_WITH_ERRORS` y `FAILED`.

### Entidades clave *(include if feature involves data)*

- **PlayerImportJob**: async process that rastrears progress, summary, state and errors.
- **ImportTemplate**: official spreadsheet downloaded desde backend.
- **ImportErrorRow**: a nivel de fila validation or processing error.

## Criterios de éxito *(mandatory)*

### Resultados medibles

- **SC-001**: El frontend puede iniciar un import job y seguir navegando mientras el proceso corre.
- **SC-002**: El frontend puede consultar el estado hasta llegar a un estado terminal.
- **SC-003**: El backend puede devolver suficiente inforrmación para mostrar summary y row errors sin endpoints adicionales en el MVP.

## Suposiciones

- La categoría se selecciona antes de subir el archivo.
- La importación funciona únicamente con el contrato oficial de Excel.
- El backend sigue siendo responseonsable de generar la plantilla.

## Alignment Notes

- El feature principal `007-player-management` expone el detalle enriquecido del jugador.
- El detalle utiliza `legalGuardianMain` y `teamMain` como objetos resumidos o `null`.
