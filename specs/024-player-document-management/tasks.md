# Tareas: Gestión de documentos del jugador

**Entrada**: Documentos de diseño de `/specs/024-player-document-management/`

**Prerrequisitos**: plan.md (requerido), spec.md (requerido para historias), research.md, data-model.md, contracts/, quickstart.md, ADRs aceptados, contrato API canónico, [`docs/database/migration-standards.md`](../../docs/database/migration-standards.md) y estándares de pruebas

**Organización**: Las tareas se agrupan por historia de usuario para permitir implementación y pruebas independientes.

## Formato: `[ID] [P?] [Story] Descripción`

- **[P]**: Puede ejecutarse en paralelo
- **[Story]**: Historia de usuario a la que pertenece la tarea
- Incluir rutas exactas de archivos en las descripciones

---

## Fase 1: Preparación (infraestructura compartida)

**Propósito**: Estructura base y soporte compartido para el subdominio documental de Player

- [ ] T001 Crear la carpeta de subdominio documental en `app/src/Modules/Player/Domain/Document/`
- [ ] T002 Crear la carpeta de aplicación documental en `app/src/Modules/Player/Application/Document/`
- [ ] T003 Crear la carpeta de persistencia documental en `app/src/Modules/Player/Infrastructure/Persistence/`
- [ ] T004 Crear la carpeta HTTP documental en `app/src/Modules/Player/Presentation/Http/Document/`
- [ ] T005 Crear la carpeta de contratos del feature en `specs/024-player-document-management/contracts/`

---

## Fase 2: Fundamentos (prerrequisitos bloqueantes)

**Propósito**: Dominio base, modelo de metadata y abstracciones de almacenamiento requeridas por todas las historias

- [ ] T006 Definir el valor objeto o catálogo de tipos de documento soportados en `app/src/Modules/Player/Domain/Document/DocumentType.php`
- [ ] T007 [P] Definir la entidad de dominio `PlayerDocument` con estado, auditoría y soft delete en `app/src/Modules/Player/Domain/Document/PlayerDocument.php`
- [ ] T008 [P] Definir la interfaz de repositorio de documentos en `app/src/Modules/Player/Domain/Document/PlayerDocumentRepository.php`
- [ ] T009 Definir el contrato de almacenamiento privado para archivos documentales en `app/src/Modules/Player/Domain/Document/PlayerDocumentStorage.php`
- [ ] T010 Definir el contrato de validación de archivos documentales en `app/src/Modules/Player/Domain/Document/PlayerDocumentUploadValidator.php`
- [ ] T011 [P] Crear el mapping XML base de `PlayerDocument` en `app/src/Modules/Player/Infrastructure/Persistence/Doctrine/Mapping/PlayerDocument/PlayerDocument.orm.xml`
- [ ] T012 Implementar la infraestructura de almacenamiento privado para documentos en `app/src/Modules/Player/Infrastructure/Persistence/FileSystemPlayerDocumentStorage.php`
- [ ] T013 Implementar el repositorio Doctrine de documentos en `app/src/Modules/Player/Infrastructure/Persistence/PlayerDocumentRepository.php`
- [ ] T014 Crear la migración Doctrine de `player_documents` en `app/migrations/`, incluyendo UUID, `academy_id`, `player_id`, metadata documental, auditoría, soft delete, foreign keys e índices para listados activos, y registrar el wiring en `app/config/services.yaml` y `app/config/packages/doctrine.yaml`

**Checkpoint**: La base documental existe y las historias pueden construirse sobre contratos reales

---

## Fase 3: Historia de usuario 1 - Listar documentos del jugador (Prioridad: P1)

**Objetivo**: Permitir que Owner/Admin consulte los documentos activos de un jugador con paginación y aislamiento por tenant

**Prueba independiente**: Consultar la lista de documentos de un jugador del tenant actual y verificar paginación, colección vacía y bloqueo cross-tenant

### Implementación de la historia de usuario 1

- [ ] T015 [US1] Crear la query `ListPlayerDocumentsQuery` en `app/src/Modules/Player/Application/Document/Query/ListPlayerDocumentsQuery.php`
- [ ] T016 [US1] Crear la respuesta paginada `PlayerDocumentListResponse` en `app/src/Modules/Player/Application/Document/Response/PlayerDocumentListResponse.php`
- [ ] T017 [US1] Crear el handler `ListPlayerDocumentsHandler` en `app/src/Modules/Player/Application/Document/Handler/ListPlayerDocumentsHandler.php`
- [ ] T018 [US1] Implementar la recuperación paginada de documentos activos en `app/src/Modules/Player/Infrastructure/Persistence/PlayerDocumentRepository.php`
- [ ] T019 [US1] Crear el endpoint HTTP de listado en `app/src/Modules/Player/Presentation/Http/Document/PlayerDocumentController.php`
- [ ] T020 [US1] Añadir la transformación de respuesta estándar para documentos listados en `app/src/Modules/Player/Application/Document/Response/PlayerDocumentItemResponse.php`
- [ ] T021 [US1] Conectar el listado con la resolución de tenant del contexto autenticado en `app/src/Modules/Player/Presentation/Http/Document/PlayerDocumentController.php`

**Checkpoint**: El listado de documentos del jugador funciona de forma independiente

---

## Fase 4: Historia de usuario 2 - Cargar documento del jugador (Prioridad: P2)

**Objetivo**: Permitir cargar un documento válido para un jugador, persistiendo archivo y metadata de forma segura

**Prueba independiente**: Subir un archivo soportado para un jugador del tenant y verificar metadata, almacenamiento privado y validación previa

### Implementación de la historia de usuario 2

- [ ] T022 [US2] Crear la request HTTP multipart de subida en `app/src/Modules/Player/Presentation/Http/Document/Request/CreatePlayerDocumentRequest.php`
- [ ] T023 [US2] Crear el command `CreatePlayerDocumentCommand` en `app/src/Modules/Player/Application/Document/Command/CreatePlayerDocumentCommand.php`
- [ ] T024 [US2] Crear la respuesta `PlayerDocumentResponse` para creación en `app/src/Modules/Player/Application/Document/Response/PlayerDocumentResponse.php`
- [ ] T025 [US2] Crear el handler `CreatePlayerDocumentHandler` en `app/src/Modules/Player/Application/Document/Handler/CreatePlayerDocumentHandler.php`
- [ ] T026 [US2] Implementar la validación de tipo, tamaño, extensión, MIME y sanitización de nombre en `app/src/Modules/Player/Domain/Document/PlayerDocumentUploadValidator.php`
- [ ] T027 [US2] Implementar la generación de nombre interno único y persistencia privada del archivo en `app/src/Modules/Player/Infrastructure/Persistence/FileSystemPlayerDocumentStorage.php`
- [ ] T028 [US2] Implementar el guardado de metadata y auditoría del documento en `app/src/Modules/Player/Infrastructure/Persistence/PlayerDocumentRepository.php`
- [ ] T029 [US2] Conectar la ruta HTTP de subida en `app/src/Modules/Player/Presentation/Http/Document/PlayerDocumentController.php`
- [ ] T030 [US2] Asegurar limpieza del archivo cuando falle la persistencia de metadata en `app/src/Modules/Player/Application/Document/Handler/CreatePlayerDocumentHandler.php`

**Checkpoint**: La subida de documentos funciona de extremo a extremo con validación y rollback de archivo

---

## Fase 5: Historia de usuario 3 - Ver y descargar documento (Prioridad: P3)

**Objetivo**: Permitir ver un documento inline o descargarlo como adjunto preservando acceso y nombre de archivo

**Prueba independiente**: Abrir un documento PDF/imagen inline y descargarlo como adjunto verificando headers y control de tenant

### Implementación de la historia de usuario 3

- [ ] T031 [US3] Crear la query `ShowPlayerDocumentQuery` en `app/src/Modules/Player/Application/Document/Query/ShowPlayerDocumentQuery.php`
- [ ] T032 [US3] Crear la query `DownloadPlayerDocumentQuery` en `app/src/Modules/Player/Application/Document/Query/DownloadPlayerDocumentQuery.php`
- [ ] T033 [US3] Crear las respuestas `PlayerDocumentFileResponse` y `PlayerDocumentDownloadResponse` en `app/src/Modules/Player/Application/Document/Response/`
- [ ] T034 [US3] Crear los handlers `ShowPlayerDocumentHandler` y `DownloadPlayerDocumentHandler` en `app/src/Modules/Player/Application/Document/Handler/`
- [ ] T035 [US3] Implementar lectura segura del archivo privado en `app/src/Modules/Player/Infrastructure/Persistence/FileSystemPlayerDocumentStorage.php`
- [ ] T036 [US3] Implementar la entrega inline y attachment con headers correctos en `app/src/Modules/Player/Presentation/Http/Document/PlayerDocumentController.php`
- [ ] T037 [US3] Añadir control de tenant y validación de documento activo antes de servir el archivo en `app/src/Modules/Player/Application/Document/Handler/ShowPlayerDocumentHandler.php`
- [ ] T038 [US3] Añadir control de tenant y validación de documento activo antes de descargar el archivo en `app/src/Modules/Player/Application/Document/Handler/DownloadPlayerDocumentHandler.php`

**Checkpoint**: La visualización y descarga están disponibles y protegidas por tenant

---

## Fase 6: Historia de usuario 4 - Reemplazar y eliminar documento (Prioridad: P4)

**Objetivo**: Reemplazar un documento sin cambiar su identificador y eliminarlo limpiando archivo y metadata

**Prueba independiente**: Reemplazar un documento con otro archivo y luego eliminarlo verificando que mantiene ID, actualiza metadata y deja de aparecer en listados

### Implementación de la historia de usuario 4

- [ ] T039 [US4] Crear la request HTTP multipart de reemplazo en `app/src/Modules/Player/Presentation/Http/Document/Request/ReplacePlayerDocumentRequest.php`
- [ ] T040 [US4] Crear el command `ReplacePlayerDocumentCommand` en `app/src/Modules/Player/Application/Document/Command/ReplacePlayerDocumentCommand.php`
- [ ] T041 [US4] Crear el handler `ReplacePlayerDocumentHandler` en `app/src/Modules/Player/Application/Document/Handler/ReplacePlayerDocumentHandler.php`
- [ ] T042 [US4] Implementar la lógica de reemplazo conservando el identificador en `app/src/Modules/Player/Domain/Document/PlayerDocument.php`
- [ ] T043 [US4] Implementar el borrado físico del archivo anterior tras reemplazo exitoso en `app/src/Modules/Player/Infrastructure/Persistence/FileSystemPlayerDocumentStorage.php`
- [ ] T044 [US4] Crear el command `DeletePlayerDocumentCommand` en `app/src/Modules/Player/Application/Document/Command/DeletePlayerDocumentCommand.php`
- [ ] T045 [US4] Crear el handler `DeletePlayerDocumentHandler` en `app/src/Modules/Player/Application/Document/Handler/DeletePlayerDocumentHandler.php`
- [ ] T046 [US4] Implementar soft delete y borrado físico del archivo en `app/src/Modules/Player/Infrastructure/Persistence/PlayerDocumentRepository.php` y `app/src/Modules/Player/Infrastructure/Persistence/FileSystemPlayerDocumentStorage.php`
- [ ] T047 [US4] Conectar las rutas HTTP de reemplazo y borrado en `app/src/Modules/Player/Presentation/Http/Document/PlayerDocumentController.php`

**Checkpoint**: El reemplazo y la eliminación quedan funcionales con preservación de identidad y limpieza de archivo

---

## Fase 7: Historia de usuario 5 - Validar cargas documentales (Prioridad: P5)

**Objetivo**: Rechazar archivos inválidos antes de persistirlos de forma permanente

**Prueba independiente**: Subir archivos faltantes, vacíos, grandes o con formato inválido y confirmar rechazo antes del almacenamiento definitivo

### Implementación de la historia de usuario 5

- [ ] T048 [US5] Crear tests unitarios de validación de archivo en `app/tests/Unit/Modules/Player/Document/PlayerDocumentUploadValidatorTest.php`
- [ ] T049 [US5] Ajustar la validación de extensión y MIME real en `app/src/Modules/Player/Domain/Document/PlayerDocumentUploadValidator.php`
- [ ] T050 [US5] Asegurar el rechazo de archivos vacíos y de más de 3,145,728 bytes en `app/src/Modules/Player/Domain/Document/PlayerDocumentUploadValidator.php`
- [ ] T051 [US5] Normalizar el nombre original a metadata segura en `app/src/Modules/Player/Domain/Document/PlayerDocumentUploadValidator.php`
- [ ] T052 [US5] Confirmar que el flujo de subida falla antes de persistir cuando la validación no pasa en `app/src/Modules/Player/Application/Document/Handler/CreatePlayerDocumentHandler.php`

**Checkpoint**: La validación de carga bloquea archivos inválidos antes de almacenamiento permanente

---

## Fase 8: Ajustes finales y preocupaciones transversales

**Propósito**: Consistencia transversal, documentación y verificación final

- [ ] T053 [P] Agregar pruebas de integración del repositorio y mapping XML en `app/tests/Integration/Modules/Player/Document/PlayerDocumentRepositoryTest.php`
- [ ] T054 [P] Agregar pruebas funcionales HTTP para listar, subir, ver, descargar, reemplazar y eliminar en `app/tests/Functional/Modules/Player/Document/PlayerDocumentControllerTest.php`
- [ ] T055 Actualizar trazabilidad de estado actual en `specs/14-current-state.md`
- [ ] T056 Ejecutar y validar los escenarios de `specs/024-player-document-management/quickstart.md`
- [ ] T057 Verificar que los contratos en `specs/024-player-document-management/contracts/` coinciden con la implementación final y con `docs/contracts/api-reference.md` y `docs/architecture/ADR-004-paginated-list-endpoints.md`

---

## Dependencias y orden de ejecución

### Dependencias entre fases

- **Fase 1**: Sin dependencias.
- **Fase 2**: Depende de la Fase 1 y bloquea todas las historias.
- **Fase 3+**: Dependen de la Fase 2.
- **Fase 8**: Depende de completar las historias deseadas.

### Dependencias entre historias de usuario

- **US1**: Puede comenzar tras la Fase 2. No depende de otras historias.
- **US2**: Puede comenzar tras la Fase 2. Usa la misma infraestructura base, pero sigue siendo independiente.
- **US3**: Puede comenzar tras la Fase 2. Depende del contrato de lectura del documento.
- **US4**: Puede comenzar tras la Fase 2. Depende de que exista el modelo de documento.
- **US5**: Puede comenzar tras la Fase 2. Refuerza la validación de la historia de carga.

### Dentro de cada historia de usuario

- Validación y contratos antes de implementación de endpoints.
- Modelos/contratos antes de handlers.
- Handlers antes de wiring HTTP.
- Una historia debe quedar funcional y verificable antes de pasar a la siguiente.

### Oportunidades de paralelización

- T007, T008, T009, T011 pueden ejecutarse en paralelo.
- T015, T016, T020 pueden avanzar en paralelo una vez exista la base documental.
- T022, T023, T024 pueden avanzar en paralelo mientras T026 y T027 se resuelven.
- T031, T032, T033 pueden avanzar en paralelo.
- T039, T040, T044 pueden avanzar en paralelo.
- T053 y T054 pueden ejecutarse en paralelo al final.

---

## Ejemplo paralelo: Historia de usuario 2

```bash
Task: "Crear la request HTTP multipart de subida en `app/src/Modules/Player/Presentation/Http/Document/Request/CreatePlayerDocumentRequest.php`"
Task: "Crear el command `CreatePlayerDocumentCommand` en `app/src/Modules/Player/Application/Document/Command/CreatePlayerDocumentCommand.php`"
Task: "Crear la respuesta `PlayerDocumentResponse` para creación en `app/src/Modules/Player/Application/Document/Response/PlayerDocumentResponse.php`"
```

## Estrategia de implementación

### Primero el MVP

1. Completar las Fases 1 y 2.
2. Completar US1 para tener el listado base de documentos.
3. Validar el comportamiento de US1 antes de seguir.
4. Añadir US2 para habilitar la creación real de documentos.
5. Continuar con US3, US4 y US5 de forma incremental.

### Entrega incremental

1. Fases 1 + 2: base técnica.
2. US1: listado.
3. US2: carga.
4. US3: visualización y descarga.
5. US4: reemplazo y borrado.
6. US5: validación reforzada y endurecimiento.

### Notas

- Las tareas `[P]` usan archivos distintos y no tienen dependencias pendientes.
- La etiqueta `[Story]` vincula cada tarea con una historia para trazabilidad.
- Cada historia debe poder completarse y probarse de forma independiente.
- Crear un commit después de cada incremento lógico.
