# Player Import UX Spec

## Purpose

Documentar la experiencia visual y el contrato funcional deseado para la importación asíncrona de jugadores, con una primera iteración implementada con mocks en frontend y una segunda iteración respaldada por backend.

> Documento central de referencia: [`docs/flows/player/player-import-flow-spec.md`](/home/juan-rodas/projects/playertech/playertech-api/docs/flows/player/player-import-flow-spec.md)
>
> Este archivo queda como apoyo visual/UX del frontend. Si hay diferencia de contrato, prevalece el documento central.

Este documento es la referencia operativa para diseñar y maquetar el flujo de importación sin depender todavía de la implementación asíncrona completa en backend.

## Scope

- Module: `Player`
- Surface: `Academy / Players`
- Audience: Frontend
- Mode: Tenant-scoped
- Strategy: Mock-first for UI, backend iteration later

## Problem Statement

La importación actual de jugadores es síncrona y no ofrece una experiencia de progreso continua ni una forma amable de revisar categorías durante la preparación del Excel. El usuario necesita:

- ver las categorías disponibles y sus `categoryKey`
- preparar archivos con jugadores de una o varias categorías
- mantener la vista del listado activa mientras se importa
- recibir progreso visible
- ver errores por fila al finalizar
- entender con facilidad cómo llenar formatos como fechas, correos y teléfonos

## UX Principles

- No bloquear el listado principal durante la importación.
- Mostrar contexto de categorías de forma persistente.
- Ofrecer progreso visible y entendible.
- Reducir fricción para usuarios que importan jugadores de múltiples categorías.
- Evitar que el usuario tenga que adivinar `categoryKey`.
- Mantener instrucciones cortas y legibles.
- Dar ejemplos claros para fechas, correos y teléfonos.

## Recommended Interaction Model

### Primary Pattern

Usar un `dialog` amplio y persistente, con scroll interno y comportamiento de asistente, sobre la vista de jugadores.

### Why Not a Blocking Modal

- Obliga a detener el trabajo del usuario.
- Impide revisar la tabla de jugadores mientras se prepara o ejecuta el import.
- Hace más difícil referenciar categorías y keys simultáneamente.

### Why a Persistent Dialog

- Permite seguir viendo el listado detrás.
- Facilita la consulta de categorías mientras se llena el Excel.
- Permite cerrar/reabrir sin perder contexto.
- Es más adecuado para procesos largos o de varias etapas.

## Visual Structure

### 1. Entry Point

Desde el listado de jugadores debe existir una acción:

- `Importar Excel`

Al activar la acción, abrir un dialog amplio con el flujo completo.

### 2. Dialog Layout

El dialog debe dividirse en tres bloques:

#### Block A: Template and Guidance

- Título: `Importar jugadores`
- Subtítulo: `Carga masiva desde archivo Excel`
- Descripción breve:
  - `Descarga la plantilla oficial antes de cargar el archivo`
  - `La importación se ejecuta sobre la academia actual`
- CTA:
  - `Descargar plantilla`

La plantilla debe verse simple y clara:

- hoja `Referencias` con copy corto en la parte superior
- hoja `Datos` sin filas de prueba
- tablas de referencia con valores válidos y concisos
- ejemplos visibles de formato para:
  - fechas
  - correo
  - celular con prefijo `+57`

#### Block B: Category Reference

Mostrar una referencia visible de categorías para ayudar al usuario a llenar el archivo.

Cada categoría debe mostrar:

- `name`
- `categoryKey`

Opcionalmente:

- rango de edad
- número de jugadores

Acciones útiles:

- buscador por nombre
- filtro por estado
- botón `Copiar key`
- botón `Copiar todas`
- botón opcional `Exportar categorías`

#### Block C: File Upload and Progress

- upload para archivo `.xlsx`
- nombre del archivo cargado
- botón para reemplazar/remover
- barra de progreso
- estado textual
- resumen final

## Category Reference UX

El usuario puede tener dos escenarios:

1. Un archivo con jugadores de varias categorías.
2. Un archivo con jugadores de una sola categoría.

En ambos casos debe poder consultar rápidamente las keys disponibles.

### Recommended Presentation

- lista compacta o tabla simple
- `name` como dato principal
- `categoryKey` visible como referencia secundaria
- `categoryKey` en estilo monospace o badge

### User Assistance

La UI debe dejar claro que:

- el usuario no debe inventar la key
- la key es una referencia de negocio definida por backend
- el frontend puede ayudar copiando o exportando la referencia
- el usuario debe copiar códigos válidos, no escribir valores libres
- `birthDate` usa formato `YYYY-MM-DD`
- `email` debe ser válido
- `phone` puede ingresarse como número colombiano local; el backend agregará automáticamente el prefijo `+57`

## Import Flow States

### State 1: No File Selected

- instrucción inicial
- descarga de plantilla
- categorías visibles
- CTA para seleccionar archivo

### State 2: File Selected

- nombre del archivo
- tamaño
- opción de remover o reemplazar

### State 3: Validating

- barra de progreso
- mensajes como:
  - `Validando plantilla`
  - `Leyendo archivo`
  - `Validando categorías`

### State 4: Ready To Import

- validación correcta
- CTA principal:
  - `Importar jugadores`

### State 5: Importing

- barra de progreso activa
- texto dinámico:
  - `Procesando filas`
  - `Creando jugadores`
  - `Asociando categorías`

### State 6: Completed Successfully

- resumen:
  - total importados
  - total procesados
  - total fallidos, si aplica
- CTA:
  - `Ir al listado`
  - `Importar otro archivo`

### State 7: Completed With Errors

- mensaje claro de error parcial
- tabla de errores por fila y campo
- CTA:
  - `Descargar reporte`
  - `Reintentar`

### State 8: Failed

- fallo de validación o proceso
- mensaje general
- detalle técnico si aplica

## Progress UX

### Recommendation

La barra de progreso debe ser visible durante todo el job.

### Suggested Stages

- `Archivo cargado`
- `Validando estructura`
- `Validando filas`
- `Procesando registros`
- `Importando jugadores`
- `Finalizando`

### Behavior

- Si el backend expone progreso real, la barra debe reflejarlo.
- Si el backend aún no lo expone, la UI puede simular progreso por etapas usando mocks.

## Polling Strategy

### Responsibility

El polling lo ejecuta el frontend.

### Flow

1. `POST /api/v1/academy/players/import`
2. El backend devuelve un `jobId`
3. El frontend consulta periódicamente:
   - `GET /api/v1/academy/players/import/{jobId}`
4. La UI actualiza progreso y estado
5. El polling se detiene al llegar a un estado terminal

### Terminal States

- `COMPLETED`
- `COMPLETED_WITH_ERRORS`
- `FAILED`

### Polling Stop Rules

El polling debe detenerse cuando:

- el job entra en un estado terminal
- el usuario cierra el dialog
- el usuario navega fuera del módulo
- ocurre un error no recuperable en la consulta

## Backend Contract Target

### Create Import Job

`POST /api/v1/academy/players/import`

Expected response:

```json
{
  "data": {
    "jobId": "uuid",
    "status": "QUEUED",
    "progress": 0
  },
  "meta": {}
}
```

### Get Import Job Status

`GET /api/v1/academy/players/import/{jobId}`

Expected response:

```json
{
  "data": {
    "jobId": "uuid",
    "status": "PROCESSING",
    "progress": 45,
    "summary": {
      "totalRows": 80,
      "processedRows": 36,
      "successRows": 34,
      "errorRows": 2
    },
    "errors": [
      {
        "row": 12,
        "field": "documentNumber",
        "message": "Documento duplicado"
      }
    ]
  },
  "meta": {}
}
```

## Recommended Backend Data Model

### PlayerImportJob

Fields:

- `id`
- `academyId`
- `createdBy`
- `filePath`
- `originalFileName`
- `status`
- `progress`
- `totalRows`
- `processedRows`
- `successRows`
- `errorRows`
- `startedAt`
- `finishedAt`
- `createdAt`
- `updatedAt`

### PlayerImportJobError

Fields:

- `id`
- `importJobId`
- `row`
- `field`
- `message`

## Import Validation Rules

The import must continue to validate:

- file extension `.xlsx`
- template header
- tenant scope
- unique document per academy
- valid birth date
- valid categories

## Error Presentation

Errors should be shown by row and field:

- `Fila 3 - category_key: La categoría no existe en la academia.`
- `Fila 7 - birth_date: Debe tener formato Y-m-d.`
- `Fila 9 - document_number: El documento ya existe para esta academia.`

## Mock-First Frontend Approach

This spec should be implemented first using mock data.

### Mocked Data

- category list
- import job statuses
- progress values
- success summary
- row-level errors

## Trazabilidad SDD

- Flujo central: `docs/flows/player/player-import-flow-spec.md`
- Backlog origen: `docs/backlog/stories/EP-007-player-management/HU-007-import-players-bulk.md`
- Contrato HTTP: `docs/contracts/api-reference.md`

### Why Mock First

- Helps design the dialog and its states before backend is ready.
- Lets us iterate on visual clarity.
- Makes it easier to define the final API shape later.

## UI Components Suggested

- `ImportPlayersDialog`
- `CategoryReferencePanel`
- `FileUploadCard`
- `ImportProgressBar`
- `ImportStatusBadge`
- `ImportErrorsTable`
- `ImportSummaryCard`
- `CopyCategoryKeyButton`

## Acceptance Criteria for Frontend Mock Phase

- The dialog can be opened from the players list.
- The user can browse categories and copy `categoryKey`.
- The user can upload a `.xlsx` file.
- The UI shows import stages and progress states with mock data.
- The UI shows success, partial success, and failure states.
- The user can keep working in the list while the dialog is open.

## Recommended Next Iteration

Once the mock UI is approved:

1. implement `POST /api/v1/academy/players/import`
2. implement job persistence
3. implement polling endpoint
4. wire real progress into the dialog

## Traceability

- Epic: `EP-007 Gestión de Jugadores`
- Story: `HU-007 Importar Jugadores en Lote`
- Related Story: `HU-008 Clave de Negocio de Categoria`
- Reference: `docs/contracts/api-reference.md`
- Current state: `specs/14-current-state.md`
- Persistent memory: `docs/architecture/memory/project-memory.md`
