# Tareas: Catálogo compartido de parentescos

**Input**: Documentos de diseño de `specs/026-relationship-catalog/`

**Prerequisitos**: `plan.md`, `spec.md`, `research.md`, `data-model.md`, `contracts/relationship-options.md` y `quickstart.md`.

**Organización**: Las tareas están agrupadas por historia de usuario y mantienen trazabilidad con `EP-025` y `HU-002`.

## Phase 1: Setup

**Propósito**: Confirmar el contexto de implementación y los consumidores afectados antes de modificar el catálogo.

- [ ] T001 Revisar `EP-025`, `HU-002`, `spec.md`, `plan.md`, `research.md` y `contracts/relationship-options.md` para confirmar el alcance de `EP-025`.
- [ ] T002 [P] Auditar referencias a catálogos de parentesco y a `/api/v1/academy/relationships/options` en `app/`, `app/tests/`, `postman/`, `docs/` y `specs/`.
- [ ] T003 [P] Confirmar en `app/config/routes/attributes.yaml` y `app/config/packages/security.yaml` la carga de controllers compartidos, autenticación JWT y contexto tenant requeridos para la ruta oficial.

## Phase 2: Foundational

**Propósito**: Preparar el límite compartido y la estrategia de migración antes de implementar el endpoint.

- [ ] T004 Definir la ubicación canónica `app/src/Shared/Domain/Relationship/` para el catálogo y documentar cualquier conflicto de namespace o autoload en `app/composer.json`.
- [ ] T005 [P] Definir la matriz de consumidores y referencias que deben migrar desde cualquier lista paralela hacia `App\Shared\Domain\Relationship\Relationship` en `specs/026-relationship-catalog/research.md` o en el plan de implementación.
- [ ] T006 [P] Preparar los escenarios funcionales de autenticación, contexto de academia y respuesta API en `app/tests/Functional/Shared/Relationship/RelationshipControllerTest.php`.

## Phase 3: User Story 1 - Consultar parentescos (Priority: P1) 🎯 MVP

**Goal**: Exponer un catálogo oficial, estable y reutilizable mediante `GET /api/v1/academy/relationships/options` para cualquier usuario autenticado con contexto válido de academia.

**Independent Test**: Con un usuario tenant autenticado, consultar la ruta oficial y verificar `200 OK`, ocho opciones en orden estable, campos `value`/`label` y `meta: {}`; repetir con usuario no autenticado y sin contexto tenant para verificar los errores estándar.

### Tests for User Story 1

- [X] T007 [P] [US1] Crear pruebas unitarias para `label()`, `options()`, valores oficiales, etiquetas y orden estable en `app/tests/Unit/Shared/Domain/Relationship/RelationshipTest.php`.
- [X] T008 [P] [US1] Crear prueba funcional del contrato `GET /api/v1/academy/relationships/options`, envelope `data`/`meta` y ocho opciones en `app/tests/Functional/Shared/Relationship/RelationshipControllerTest.php`.
- [X] T009 [P] [US1] Añadir escenarios funcionales de usuario no autenticado, usuario `ROLE_ROOT` sin tenant y usuario tenant autenticado en `app/tests/Functional/Shared/Relationship/RelationshipControllerTest.php`.

### Implementación de User Story 1

- [X] T010 [US1] Crear `Relationship` en `app/src/Shared/Domain/Relationship/Relationship.php`, conservando los valores, etiquetas, `options()` y `fromInput()` definidos en el contrato.
- [ ] T011 [US1] Actualizar imports y referencias de parentesco en consumidores y pruebas para usar `App\Shared\Domain\Relationship\Relationship`.
- [X] T012 [US1] Confirmar el autoload, descubrimiento de servicios y carga de atributos del namespace compartido para `app/src/Shared/Presentation/Http/Academy/RelationshipController.php` en `app/config/services.yaml` y `app/config/routes/attributes.yaml`.
- [ ] T013 [US1] Actualizar la referencia canónica en `docs/contracts/api-reference.md` y la solicitud operativa en `postman/PlayerTech.postman_collection.json` con la ruta neutral, acceso, respuesta y retiro de cualquier ruta anterior.
- [X] T014 [US1] Crear el controller compartido en `app/src/Shared/Presentation/Http/Academy/RelationshipController.php` con `GET /academy/relationships/options`, respuesta `data` y `meta: {}`, y resolver el contexto de academia mediante el mecanismo vigente.
- [ ] T015 [US1] Migrar las referencias de consumo de Player y LegalGuardian hacia `App\Shared\Domain\Relationship\Relationship`, manteniendo la validación de relaciones en los módulos propietarios.
- [ ] T016 [US1] Confirmar que futuros consumidores de parentesco queden preparados para reutilizar `App\Shared\Domain\Relationship\Relationship` sin crear una lista propia; registrar cualquier consumidor futuro identificado en `docs/backlog/stories/EP-025-shared-master-data/HU-002-consult-relationships.md`.
- [X] T017 [US1] Ejecutar las pruebas de T007, T008 y T009 y corregir cualquier diferencia entre el comportamiento implementado y `specs/026-relationship-catalog/contracts/relationship-options.md`.

## Phase 4: Polish y preocupaciones transversales

**Propósito**: Alinear contratos operativos, trazabilidad y validaciones de calidad del repositorio.

- [ ] T018 [P] Registrar la implementación de `EP-025`/`HU-002`, la ubicación compartida y la ruta oficial en `specs/14-current-state.md`.
- [ ] T019 [P] Actualizar referencias cruzadas en las historias afectadas de Player y LegalGuardian, `specs/007-player/`, `specs/006-legal-guardian-management/` y cualquier spec que dependa del catálogo de parentescos.
- [ ] T020 Ejecutar el quickstart de `specs/026-relationship-catalog/quickstart.md` dentro de Docker, incluyendo `php -l`, `debug:router`, PHPUnit y validación de Postman.
- [ ] T021 Ejecutar `git diff --check` y revisar que no existan referencias activas a listas duplicadas de parentescos fuera del catálogo compartido.

## Dependencias y orden de ejecución

### Dependencias entre fases

- **Phase 1**: No tiene dependencias y debe completarse primero.
- **Phase 2**: Depende de Phase 1 y bloquea la historia de usuario.
- **Phase 3 / US1**: Depende de T004 y T005; T007-T009 pueden prepararse en paralelo; T013, T014 y T015 deben completarse antes de retirar rutas anteriores si existieran; T017 valida la implementación completa.
- **Phase 4**: Depende de que US1 esté implementada y probada.

### Dependencias de la historia

- **US1 (P1)**: No depende de otra historia funcional; depende de la preparación del límite `Shared` y del inventario de consumidores.

### Oportunidades de paralelización

- T002 y T003 pueden ejecutarse en paralelo.
- T005 y T006 pueden ejecutarse en paralelo después de T001.
- T007, T008 y T009 pueden redactarse en paralelo antes de implementar T010-T014.
- T018 y T019 pueden ejecutarse en paralelo después de T017.

## Estrategia de implementación

### MVP

1. Completar Phase 1 y Phase 2.
2. Ejecutar T007-T009 para fijar el comportamiento esperado.
3. Completar T010-T017 para publicar el catálogo compartido y validar consumidores.
4. Completar T018-T021 para Postman, contratos, current state y auditoría final.

### Entrega incremental

- Primer incremento: catálogo compartido y pruebas unitarias.
- Segundo incremento: endpoint neutral y pruebas funcionales.
- Tercer incremento: migración de consumidores.
- Cuarto incremento: Postman, contratos, current state y auditoría final.

## Notas

- `[P]` indica que la tarea puede ejecutarse en paralelo sin depender de una tarea incompleta.
- Cada tarea incluye al menos un archivo o ruta concreta para evitar trabajo ambiguo.
- No se incluyen tareas de tabla maestra, CRUD administrativo ni configuración por academia porque están fuera del alcance de `HU-002`.
