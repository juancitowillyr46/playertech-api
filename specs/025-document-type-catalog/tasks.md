# Tareas: Catálogo compartido de tipos de documento

**Input**: Documentos de diseño de `specs/025-document-type-catalog/`

**Prerequisitos**: `plan.md`, `spec.md`, `research.md`, `data-model.md`, `contracts/document-type-options.md` y `quickstart.md`.

**Organización**: Las tareas están agrupadas por historia de usuario y mantienen trazabilidad con `EP-025` y `HU-001`.

## Phase 1: Setup

**Propósito**: Confirmar el contexto de implementación y los consumidores afectados antes de modificar el catálogo.

- [x] T001 Revisar `EP-025`, `HU-001`, `spec.md`, `plan.md`, `research.md` y `contracts/document-type-options.md` para confirmar el alcance de `EP-025`.
- [x] T002 [P] Auditar referencias a `App\Modules\Player\Domain\Document\DocumentType` y a `/api/v1/academy/players/document-types/options` en `app/`, `app/tests/`, `postman/`, `docs/` y `specs/`.
- [x] T003 [P] Confirmar en `app/config/routes/attributes.yaml` y `app/config/packages/security.yaml` la carga de controllers compartidos, autenticación JWT y contexto tenant requeridos para la ruta oficial.

## Phase 2: Foundational

**Propósito**: Preparar el límite compartido y la estrategia de migración antes de implementar el endpoint.

- [x] T004 Definir la ubicación canónica `app/src/Shared/Domain/Document/` para el catálogo y documentar cualquier conflicto de namespace o autoload en `app/composer.json`.
- [x] T005 [P] Definir la matriz de consumidores y referencias que deben migrar desde Player hacia `App\Shared\Domain\Document\DocumentType` en `specs/025-document-type-catalog/research.md` o en el plan de implementación.
- [x] T006 [P] Preparar los escenarios funcionales de autenticación, contexto de academia y respuesta API en `app/tests/Functional/Shared/Document/DocumentTypeControllerTest.php`.

**Checkpoint**: El límite de `Shared`, las referencias a migrar y los escenarios de prueba están identificados; la implementación de la historia puede comenzar.

## Phase 3: User Story 1 - Consultar tipos de documento (Priority: P1) 🎯 MVP

**Goal**: Exponer un catálogo oficial, estable y reutilizable mediante `GET /api/v1/academy/document-types/options` para cualquier usuario autenticado con contexto válido de academia.

**Independent Test**: Con un usuario tenant autenticado, consultar la ruta oficial y verificar `200 OK`, seis opciones en orden estable, campos `value`/`label` y `meta: {}`; repetir con usuario no autenticado y sin contexto tenant para verificar los errores estándar.

### Tests for User Story 1

- [x] T007 [P] [US1] Crear pruebas unitarias para `label()`, `options()`, valores oficiales, etiquetas y orden estable en `app/tests/Unit/Shared/Domain/Document/DocumentTypeTest.php`.
- [x] T008 [P] [US1] Crear prueba funcional del contrato `GET /api/v1/academy/document-types/options`, envelope `data`/`meta` y seis opciones en `app/tests/Functional/Shared/Document/DocumentTypeControllerTest.php`.
- [x] T009 [P] [US1] Añadir escenarios funcionales de usuario no autenticado, usuario `ROLE_ROOT` sin tenant y usuario tenant autenticado en `app/tests/Functional/Shared/Document/DocumentTypeControllerTest.php`.

### Implementación de User Story 1

- [x] T010 [US1] Mover `DocumentType` desde `app/src/Modules/Player/Domain/Document/DocumentType.php` a `app/src/Shared/Domain/Document/DocumentType.php`, conservando los valores, etiquetas, `options()` y `fromInput()` definidos en el contrato.
- [x] T011 [US1] Actualizar imports y referencias de `DocumentType` en `app/src/Modules/Player/`, `app/tests/Modules/Player/` y cualquier consumidor identificado por T002 para usar `App\Shared\Domain\Document\DocumentType`.
- [x] T012 [US1] Confirmar el autoload, descubrimiento de servicios y carga de atributos del namespace compartido para `app/src/Shared/Presentation/Http/Academy/DocumentTypeController.php` en `app/config/services.yaml` y `app/config/routes/attributes.yaml`.
- [x] T013 [US1] Alinear la plantilla y validaciones de importación de Player para consumir `App\Shared\Domain\Document\DocumentType` sin duplicar valores en `app/src/Modules/Player/Application/Service/PlayerImportTemplateFactory.php` y los handlers de importación relacionados.
- [x] T014 [US1] Actualizar antes del cambio HTTP la referencia canónica en `docs/contracts/api-reference.md` y la solicitud operativa en `postman/PlayerTech.postman_collection.json` con la ruta neutral, acceso, respuesta y retiro de la ruta anterior.
- [x] T015 [US1] Crear el controller compartido en `app/src/Shared/Presentation/Http/Academy/DocumentTypeController.php` con `GET /academy/document-types/options`, respuesta `data` y `meta: {}`, y resolver el contexto de academia mediante el mecanismo vigente.
- [x] T016 [US1] Migrar las referencias de gestión documental de Player y sus pruebas desde `App\Modules\Player\Domain\Document\DocumentType` hacia `App\Shared\Domain\Document\DocumentType`, manteniendo la validación de documentos en `app/src/Modules/Player/` y `app/tests/`.
- [x] T017 [US1] Confirmar que LegalGuardian queda preparado para consumir `App\Shared\Domain\Document\DocumentType` sin crear una lista propia; registrar cualquier consumidor futuro identificado en `docs/backlog/stories/EP-025-shared-master-data/HU-001-consult-document-types.md`.
- [x] T018 [US1] Ejecutar las pruebas de T007, T008 y T009 y corregir cualquier diferencia entre el comportamiento implementado y `specs/025-document-type-catalog/contracts/document-type-options.md`.
- [x] T019 [US1] Eliminar el controller específico de Player en `app/src/Modules/Player/Presentation/Http/Academy/PlayerDocumentTypeController.php` únicamente después de completar T013, T014, T015 y T016, retirando `/api/v1/academy/players/document-types/options`.

**Checkpoint**: `HU-001` es funcional y verificable independientemente; la ruta neutral responde correctamente y la ruta anterior ya no está publicada.

## Phase 4: Polish y preocupaciones transversales

**Propósito**: Alinear contratos operativos, trazabilidad y validaciones de calidad del repositorio.

- [x] T020 [P] Registrar la implementación de `EP-025`/`HU-001`, la ubicación compartida y la ruta oficial en `specs/14-current-state.md`.
- [x] T021 [P] Actualizar referencias cruzadas en `docs/backlog/epics/EP-024-player-document-management.md`, historias afectadas de EP-024, `specs/024-player-document-management/` y `specs/007-player/import/` para apuntar al catálogo de `EP-025`.
- [x] T022 Ejecutar el quickstart de `specs/025-document-type-catalog/quickstart.md` dentro de Docker, incluyendo `php -l`, `debug:router`, PHPUnit y validación de Postman.
- [x] T023 Ejecutar `git diff --check` y revisar que no existan referencias activas a la ruta anterior ni listas duplicadas de tipos de documento fuera del catálogo compartido.

## Dependencias y orden de ejecución

### Dependencias entre fases

- **Phase 1**: No tiene dependencias y debe completarse primero.
- **Phase 2**: Depende de Phase 1 y bloquea la historia de usuario.
- **Phase 3 / US1**: Depende de T004 y T005; T007-T009 pueden prepararse en paralelo; T013, T014 y T016 deben completarse antes de retirar la ruta anterior en T019; T018 valida la implementación completa.
- **Phase 4**: Depende de que US1 esté implementada y probada.

### Dependencias de la historia

- **US1 (P1)**: No depende de otra historia funcional; depende de la preparación del límite `Shared` y del inventario de consumidores.

### Oportunidades de paralelización

- T002 y T003 pueden ejecutarse en paralelo.
- T005 y T006 pueden ejecutarse en paralelo después de T001.
- T007, T008 y T009 pueden redactarse en paralelo antes de implementar T010-T015.
- T020 y T021 pueden ejecutarse en paralelo después de T019.

## Estrategia de implementación

### MVP

1. Completar Phase 1 y Phase 2.
2. Ejecutar T007-T009 para fijar el comportamiento esperado.
3. Completar T010-T018 para publicar el catálogo compartido y validar consumidores.
4. Completar T019 para retirar la ruta anterior después de la migración.
5. Validar US1 de forma independiente.
6. Completar la documentación operativa y la trazabilidad de Phase 4.

### Entrega incremental

- Primer incremento: enum compartido y pruebas unitarias.
- Segundo incremento: endpoint neutral y pruebas funcionales.
- Tercer incremento: migración de importación y consumidores.
- Cuarto incremento: Postman, contratos, current state y auditoría final.

## Notas

- `[P]` indica que la tarea puede ejecutarse en paralelo sin depender de una tarea incompleta.
- Cada tarea incluye al menos un archivo o ruta concreta para evitar trabajo ambiguo.
- No se incluyen tareas de tabla maestra, CRUD administrativo ni configuración por academia porque están fuera del alcance de `HU-001`.
