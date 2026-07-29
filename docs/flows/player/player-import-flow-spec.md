# Player Import Flow Spec

## Objetivo

Implementar la importación masiva de jugadores desde Excel para el módulo `Player`, con flujo guiado por categoría previa, plantilla oficial generada por backend, persistencia de job y consulta de estado por polling.

## Principios

- La categoría se selecciona antes de subir el archivo.
- La plantilla oficial debe venir desde backend.
- La importación debe quedar trazada por `jobId`.
- El frontend no debe bloquear navegación mientras el proceso corre.
- El backend debe exponer progreso, resumen y errores entendibles.
- Los registros válidos deben persistirse aun si existen filas inválidas.

## Endpoints

### 1. Listar categorías activas

`GET /api/v1/academy/categories/options`

Uso:

- selector previo al import
- muestra solo categorías activas del tenant actual

Respuesta:

```json
{
  "data": [
    {
      "id": "uuid",
      "categoryKey": "SUB-12",
      "name": "Sub 12",
      "status": "ACTIVE"
    }
  ],
  "meta": {}
}
```

### 2. Descargar plantilla oficial

`GET /api/v1/academy/players/import/template?categoryId={uuid}`

Requisitos:

- devuelve archivo `.xlsx`
- generado por backend
- debe incluir hojas `Datos` y `Referencias`
- la plantilla debe respetar el tenant autenticado

### 3. Crear job de importación

`POST /api/v1/academy/players/import`

`multipart/form-data`

Campos esperados:

- `categoryId`
- `file`

Respuesta:

```json
{
  "data": {
    "jobId": "uuid",
    "status": "QUEUED"
  },
  "meta": {}
}
```

### 4. Consultar estado del job

`GET /api/v1/academy/players/import/{jobId}`

Respuesta:

```json
{
  "data": {
    "jobId": "uuid",
    "status": "PROCESSING",
    "progress": 45,
    "summary": {
      "totalRows": 120,
      "processedRows": 54,
      "successRows": 50,
      "errorRows": 4
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

## Estados del Job

Estados válidos:

- `QUEUED`
- `VALIDATING`
- `PROCESSING`
- `COMPLETED`
- `COMPLETED_WITH_ERRORS`
- `FAILED`

Estados terminales:

- `COMPLETED`
- `COMPLETED_WITH_ERRORS`
- `FAILED`

Regla:

- el polling del frontend debe detenerse al llegar a un estado terminal

## Contrato de la plantilla

### Hoja `Datos`

Debe contener solo columnas editables necesarias para el import.

Para MVP:

- no incluir `categoryKey` por fila
- la categoría se define una sola vez en el flujo

Columnas:

- `documentType`
- `firstName`
- `lastName`
- `birthDate`
- `documentNumber`
- `email`
- `phone`
- `nationality`
- `gender`
- `federationId`
- `dominantFoot`

### Hoja `Referencias`

Debe incluir catálogos válidos generados por backend:

- tipos de documento
- nacionalidades
- pies dominantes
- categorías activas del tenant

Formato recomendado:

- `label`
- `value`

En categorías además:

- `categoryKey`
- `status`

## Reglas de Negocio

- La categoría se aplica al archivo completo.
- No se pide `categoryKey` por fila.
- El import debe validar archivo `.xlsx`.
- El import debe validar estructura de plantilla.
- El import debe validar duplicados por academia.
- El import debe validar formato de fechas.
- Si hay filas válidas e inválidas, se persisten las válidas.
- Los errores deben reportarse por fila y campo.

## Modelo Técnico

### `PlayerImportJob`

Campos mínimos:

- `id`
- `academyId`
- `createdBy`
- `categoryId`
- `originalFileName`
- `filePath`
- `status`
- `progress`
- `totalRows`
- `processedRows`
- `successRows`
- `errorRows`
- `errors`
- `startedAt`
- `finishedAt`
- `createdAt`
- `updatedAt`

### Repositorio

Debe permitir:

- guardar job
- recuperar job por `academyId + jobId`

## Trazabilidad SDD

- Fuente funcional: `docs/backlog/stories/EP-007/HU-007-import-players-bulk.md`
- Contrato HTTP base: `docs/contracts/api-reference.md`
- Memoria persistente: `docs/architecture/memory/project-memory.md`
- Auditoría técnica: `docs/architecture/audits/SDD-backend-audit.md`
- UX satélite: `docs/flows/player/player-import-ux-spec.md`

## Procesamiento

### Estrategia

- crear el job al recibir el archivo
- persistir el archivo en storage local
- procesar el archivo asociado al job
- actualizar progreso iterativamente
- guardar resumen y errores

### Modo de Ejecución

Si el entorno usa `sync://`:

- no requiere infraestructura adicional
- el job se ejecuta en el mismo proceso PHP
- sigue existiendo persistencia de job y polling

Si luego se migra a cola real:

- se puede usar worker separado
- se gana desacoplamiento operativo
- se mantiene el mismo contrato HTTP

## Errores por Fila

Cada error debe exponer:

- `row`
- `field`
- `message`

Ejemplos:

- documento duplicado
- fecha inválida
- campo obligatorio faltante
- categoría inválida

## Contratos Relacionados

El módulo `Player` también expone en listados:

- `categoryName`
- `genderName`
- `age`
- `createdAt`

Filtros vigentes:

- `gender`
- `categoryId`
- `createdAtFrom`
- `createdAtTo`
- `birthDateFrom`
- `birthDateTo`

## Documentación Que Debe Permanecer Alineada

- `docs/backlog/stories/EP-007/HU-007-import-players-bulk.md`
- `docs/contracts/api-reference.md`
- `specs/14-current-state.md`
- `docs/architecture/memory/project-memory.md`
- `docs/flows/player/player-import-ux-spec.md`

## Criterio de Aceptación

- El usuario puede seleccionar categoría, descargar plantilla, subir Excel y seguir navegando.
- El frontend puede consultar progreso y detener polling en estados terminales.
- El backend conserva trazabilidad del job.
- La plantilla es generada por backend y refleja el tenant actual.

## Code Audit Notes

Esta implementación sigue una estrategia simple y compatible con hosting de menor capacidad:

- `sync://` se mantiene como mecanismo de ejecución para no introducir cola/worker adicional.
- `PlayerImportJob` usa mapping XML, no anotaciones Doctrine.
- La hoja `Referencias` expone categorías activas del tenant completo.
- El job valida `categoryId` antes de procesar y reporta errores por fila con campo explícito.
- `COMPLETED_WITH_ERRORS` finaliza con `progress = 100` para que el frontend cierre el polling sin ambiguedades.

Deudas aceptadas para el MVP:

- el procesamiento sigue ocurriendo en el mismo request lifecycle de PHP
- la validación de errores por fila puede seguir refinandose con excepciones tipadas si el volumen de reglas crece
- la validación de la plantilla puede endurecerse más adelante si el contrato se vuelve más estricto

## Frontend Implementation Prompt

El frontend debe implementar un dialog de importación de jugadores consumiendo estos endpoints:

- `GET /api/v1/academy/categories/options`
- `GET /api/v1/academy/players/import/template?categoryId={uuid}`
- `POST /api/v1/academy/players/import`
- `GET /api/v1/academy/players/import/{jobId}`

Flujo:

1. seleccionar categoría
2. descargar plantilla oficial
3. subir Excel
4. recibir `jobId`
5. hacer polling hasta estado terminal
6. mostrar resumen y errores por fila

Reglas UX:

- usar `dialog`
- no bloquear navegación
- mostrar progress bar
- mostrar estados del job
- mostrar errores por fila
- no pedir `categoryKey` por fila
