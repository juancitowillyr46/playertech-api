# Current State

Este documento registra el estado actual de la base tecnica, su trazabilidad y el criterio para continuar la siguiente iteracion.

---

# Persistent Memory

La memoria persistente consolidada del proyecto vive en [`docs/architecture/memory/project-memory.md`](../docs/architecture/memory/project-memory.md).

Ese documento resume la arquitectura real, los modulos presentes, los contratos vigentes, las decisiones tecnicas y los riesgos conocidos para que nuevas sesiones no dependan del historial del chat.

---

# Implemented Foundation

La base tecnica actual incluye:

* README de entrada del repositorio.
* Estructura inicial del proyecto.
* Contenedores Docker para app y MySQL.
* Runtime minimo de Symforny.
* Endpoint de salud en `/api/v1/health`.
* Configuracion base de Doctrine, Security, JWT y OpenAPI.
* Primer commit de forundation.

---

# Traceability

| Item | Type | Estado | Commit | Notes |
| ---- | ---- | ------ | ------ | ----- |
| README base | Documentation | Done | `b40e311` | Entrada principal del repositorio |
| Foundation bootstrap | Technical Enabler | Done | `7c3de8e` | Symforny, Docker, health endpoint y base runtime |
| Health endpoint | Functional | Done | `7c3de8e` | `/api/v1/health` responseonde JSON |
| Docker stack | Non-Functional / Technical Enabler | Done | `7c3de8e` | Ejecucion dentro de contenedores |
| Identity auth module refactor | Technical Enabler | Done | `87f6f9b` | Login resuelto por Symforny Security `json_login`; `/me`, handlers JWT y entidad movidos a `Modules/Identity` |
| Identity technical user model | Technical Enabler | Done | `87f6f9b` | `AccountUser` usa Doctrine attributes y GUID string para acelerar la forundation sin perder compatibilidad |
| Identity users CRUD runtime | Functional / Technical Enabler | Done | `unrastreared` | CRUD de users expuesto en `/api/v1/platforrm/users` y `/api/v1/academy/users`, con DTOs, handlers, exceptions de dominio y responseuesta JSON estándar |
| Identity validation migration | Technical Enabler | Done | `unrastreared` | La validación de crear/actualizar users se mueve a `Presentation` con requests dedicadas; `Application` queda con DTOs puros para el flujo de usuarios |
| Academy validation migration | Technical Enabler | Done | `unrastreared` | La validación de crear, actualizar y tenant signup de `Academy` se mueve a `Presentation`; `Application` queda con DTOs puros para esos flujos |
| Category validation migration | Technical Enabler | Done | `unrastreared` | La validación de crear y actualizar de `Category` se mueve a `Presentation`; `Application` queda con DTOs puros para esos flujos |
| Venue validation migration | Technical Enabler | Done | `unrastreared` | La validación de crear y actualizar de `Venue` se mueve a `Presentation`; `Application` queda con DTOs puros para esos flujos |
| Team validation migration | Technical Enabler | Done | `unrastreared` | La validación de crear y actualizar de `Team` se mueve a `Presentation`; `Application` queda con DTOs puros para esos flujos |
| Team Venue parity actualizar | Technical Enabler / Documentation | Done | `unrastreared` | `Team` normaliza `sort`, corrige la longitud de `name` según el VO compartido y alinea su referencia HTTP/Postman con el contrato real del módulo |
| Team category name contract | Functional / Documentation | Done | `unrastreared` | `Team` expone `categoryName` plano en listar/show/crear/actualizar para simplificar el consumo dthe frontend sin anidar un objeto `category`; el signup usa un responseonse propio para el primer equipo con el mismo enriquecimiento |
| Staff options selector endpoint | Functional / Technical Enabler | Done | `unrastreared` | `Staff` expone `/api/v1/academy/staff/options` como selector liviano para usuarios staff del tenant actual, con filtro opcional por rol |
| Staff options selector pattern | Functional / Technical Enabler | Done | `unrastreared` | `Staff` expone `/api/v1/academy/staff/options` como contrato liviano para selects, filtrando por academia y rol sin hidratar entidades completas |
| Staff options availability filter | Functional / Technical Enabler | Done | `unrastreared` | `Staff` ahora permite filtrar `/api/v1/academy/staff/options` por `teamId` para excluir miembros ya asignados al equipo desde `team_staff_assignments` |
| Player validation migration | Technical Enabler | Done | `unrastreared` | La validación de crear, actualizar y asociación de acudiente de `Player` se mueve a `Presentation`; `Application` queda con DTOs puros para esos flujos |
| Guardian validation migration | Technical Enabler | Done | `unrastreared` | La validación de crear de `Guardian` se mueve a `Presentation`; `Application` queda con DTOs puros para ese flujo |
| PaymentConcept validation migration | Technical Enabler | Done | `unrastreared` | La validación de crear y actualizar de `PaymentConcept` se movió a `Presentation`; `Application` quedó con DTOs puros para esos flujos |
| API controller forundation | Technical Enabler | Done | `unrastreared` | Base HTTP común para validación y resolución del usuario autenticado, reduciendo duplicación entre controladores |
| First unit test baseline | Technical Enabler | Done | `unrastreared` | PHPUnit inicial valida `AcademyId`, `AccountUser` y `UserAdministrationPolicy` |
| Tenant signup integration test | Technical Enabler | Done | `unrastreared` | `RegisterTenantHandler` valida alta de tenant contra una base de datos MySQL de test con bus de mensajes desacoplado |
| Category module completion | Functional / Technical Enabler | Done | `9d1cca1` | `Category` quedo completa con crear, listar, actualizar, activate, inactivate y Finder centralizado |
| Venue module completion | Functional / Technical Enabler | Done | `b8eec30` | `Venue` quedo completa con crear, listar, actualizar, activate e inactivate |
| Spec domain alignment | Documentation | Done | `679df05` | Se alinearon dominio, entidades, relaciones y modelo de base de datos con el diseño player-centric |
| Shared health endpoint | Technical Enabler | Done | `87f6f9b` | HealthController moved to Shared/Presentation/Http |
| Legacy forlder cleanup | Technical Enabler | Done | `87f6f9b` | Eliminados `src/Command`, `src/Controller`, `src/Entity`, `src/EventSubscriber` y `src/Security` heredados |
| Root platforrm command | Technical Enabler | Done | `87f6f9b` | `app:user:crear-root` registra usuarios `ROLE_ROOT` sin tenant |
| UUID storage conversion | Technical Enabler | Done | `87f6f9b` | La tabla `users` paso a UUID legible como string (`CHAR(36)`) |
| Platforrm vs tenant identity contexts | Architectural Constraint / Technical Enabler | Done | `fc14bd8` | ROLE_ROOT opera sin tenant; usuarios tenant requieren `academy_id` y `TenantContext` |
| Auth JWT | Functional | Done | `87f6f9b` | Login JWT y `/api/v1/auth/me` operativos |
| Tenant context | Non-Functional / Architectural Constraint | Done | `fc14bd8` | `TenantContext` resuelve el contexto de plataforrma y tenant desde el JWT |
| Academy module bootstrap | Functional / Technical Enabler | Done | `e795224` | Primer endpoint tenant-scoped `GET /api/v1/academy/me` valida contexto de academia |
| Academy gestionarment endpoints | Functional / Technical Enabler | Done | `bc2d4e1` | Refactorización a Arquitectura Hexagonal completa. Archivos movidos a `app/src/Modules/Academy`. Módulo sirve como referencia técnica. |
| Shared typed value objects | Technical Enabler | Done | `bcc18f2` | `Name`, `Email`, `Address`, `City`, `PhoneNumber`, `LogoPath`, `CreadoAt` y `UpdatedAt` quedan tipados como VOs reutilizables |
| Academy typed VO mapping forundation | Technical Enabler | Done | `5f95e40` | `AcademyId` usa Doctrine custom type y el XML de `Academy` consume los VOs compartidos como embeddables |
| Tenant academy profile actualizar | Functional | Done | `5f95e40` | `PUT /api/v1/academy/me` permite que el tenant actualice su propia academia |
| Academy CQRS application refactor | Technical Enabler | Done | `ff61ec1` | Los casos de uso de `Academy` pasaron a `Application/Command`, `Application/Query` y `Application/Handler` |
| Module creation guide | Documentation / Technical Enabler | Done | `0801f4f` | Guia operativa para crear nuevos modulos siguiendo el patron de `Academy` |
| Tenant signup onboarding spec | Documentation / Functional | Done | `unrastreared` | Nueva épica `EP-014` y HU-001 para alta simplificada de tenant con activación por correo |
| Tenant signup runtime implementation | Functional / Technical Enabler | Done | `unrastreared` | Signup tenant, activación por correo, Mailpit y flujo de login validado |
| Player module base | Functional / Technical Enabler | Done | `unrastreared` | `Player` arranca con `POST /api/v1/academy/players`, custom type UUID, XML mapping y test unitario del alta |
| Team module baseline | Functional / Technical Enabler | Done | `unrastreared` | `Team` arranca con CRUD tenant-scoped, custom type UUID, XML mapping y controladores delgados sobre `/api/v1/academy/teams` |
| Team test baseline | Technical Enabler | Done | `unrastreared` | Cobertura inicial de `Team` con unit, integration y functional tests; las suites con MySQL compartido se ejecutan en serie |
| TeamAssignment module baseline | Functional / Technical Enabler | Done | `unrastreared` | `TeamAssignment` materializa la gestión de asignaciones deportivas con principal activo, finalización e historial sobre jugadores y equipos |
| Player listar baseline | Functional / Technical Enabler | Done | `unrastreared` | `GET /api/v1/academy/players` listara jugadores del tenant actual con DTO resumido, forto, `createdAt` y prueba unitaria |
| Player detail baseline | Functional / Technical Enabler | Done | `unrastreared` | `GET /api/v1/academy/players/{playerId}` devuelve detalle del jugador dentro del tenant con `PlayerResponse` y prueba unitaria |
| Player actualizar baseline | Functional / Technical Enabler | Done | `unrastreared` | `PUT /api/v1/academy/players/{playerId}` actualiza datos del jugador dentro del tenant con validación de unicidad y prueba unitaria |
| Player status gestionarment | Functional / Technical Enabler | Done | `unrastreared` | `PATCH /api/v1/academy/players/{playerId}/inactivate` y `/activate` cambian el estado del jugador con cobertura unitaria |
| Player status gestionarment story | Functional / Documentation | Done | `unrastreared` | HU-005 consolidada documenta deactivar y reactivar como una sola gestion de estado |
| Player bulk import async | Functional / Technical Enabler | Done | `unrastreared` | `POST /api/v1/academy/players/import` crea un job asíncrono con categoría seleccionada previamente, plantilla oficial desde backend y polling de progreso |
| Backlog normalization pass 1 | Documentation | Done | `unrastreared` | Se movieron HUs duplicadas o mal ubicadas a `docs/backlog/stories/_archive/` y se renombró la HU de detalle de sede para alinear `EP-002` |
| Payment evidence puedeonical owner | Documentation | Done | `unrastreared` | `HU-004-attach-payment-evidence.md` quedó puedeonizada en `EP-012`; la copia de `EP-009` fue archivada |
| Backlog normalization pass 2 | Documentation | Done | `unrastreared` | `EP-007` quedó reducido a una HU puedeónica de estado; `EP-003` archivó la HU de invitación duplicada de alta administrativa |
| Backlog normalization pass 3 | Documentation | Done | `unrastreared` | `EP-009` quedó con numeración continua y evidencia de pago puedeónica en `EP-012` |
| Category business key forundation | Functional / Technical Enabler | Done | `unrastreared` | `Category` ahora expone `category_key` estable, unico por academia, para contratos API e importaciones |
| Guardian module forundation | Functional / Technical Enabler | Done | `unrastreared` | `LegalGuardian` queda disponible como aggregate root con XML puro, custom type UUID y endpoint de alta dentro de Academy |
| Guardian lifecycle completion | Functional / Technical Enabler | Done | `unrastreared` | `LegalGuardian` ahora expone create, list, detail, update, inactivate y activate con contratos HTTP, handlers y pruebas unitarias asociadas |
| PlayerGuardian relation forundation | Functional / Technical Enabler | Done | `unrastreared` | `PlayerGuardian` cubre asociar, cambiar principal y eliminar relación con soft delete y aislamiento por academia |
| Doctrine Tenant Filter | Non-Functional / Technical Enabler | Done | `unrastreared` | Filtro global que aísla automáticamente las consultas por `academy_id` para seguridad multi-tenant |
| Doctrine AuditSubscriber | Non-Functional / Technical Enabler | Done | `unrastreared` | Filler centralizado de `auditTrail` para entidades auditable en persistencia Doctrine |
| Cross-tenant isolation test | Technical Enabler | Done | `unrastreared` | Prueba de integración valida que una academia no puede leer registros de otra aunque conozca el ID |
| Tenant signup initial team | Functional | Done | `unrastreared` | `POST /api/v1/public/tenants/signup` recibe `category_id` y `team_name`, valida la categoría y crea el primer equipo del tenant |
| Tenant activation idempotency | Functional / Technical Enabler | Done | `unrastreared` | `GET /api/v1/public/tenants/activate/{token}` ahora devuelve `alreadyActivated` cuando el token sigue vigente y responseonde `404` problem-details si el token es inválido o expiró |
| Test database guard rail | Technical Enabler | Done | `unrastreared` | `tests/bootstrap.php` ahora falla si PHPUnit intenta usar una base distinta de `*_test` |
| Onboarding catalog repair command | Technical Enabler | Done | `unrastreared` | Se agregó `app:category:seed-onboarding` para reponer el catálogo público de onboarding en `playertech` sin tocar migraciones |

---

# Commit References

* `7c3de8e` - `chore: bootstrap PlayerTech API forundation`
* `b40e311` - `docs: improve project README`
* `87f6f9b` - `feat(identity): align technical forundation and docs`
* `fc14bd8` - `feat(identity): add tenant context forundation`
* `e795224` - `feat(academy): add tenant academy context endpoint`
* `bc2d4e1` - `refactor(academy): apply hexagonal architecture and domain purity`
* `bcc18f2` - `feat(shared): add typed academy value objects`
* `5f95e40` - `feat(academy): introduce typed vo mapping`
* `ff61ec1` - `refactor(academy): move use cases to application handlers`
* `419ded4` - `feat(academy): implement academy gestionarment endpoints`
* `37cc830` - `feat(identity): add users crud forundation`
* `ae7cbc7` - `refactor(identity): reduce controller duplication`
* `f02ee94` - `test(identity): add initial unit test baseline`
* `9f72c99` - `test(academy): add mysql-backed tenant signup integration`
* `72bba8a` - `feat(category): implement actualizar use case and refine category gestionarment`
* `0e2d016` - `feat(category): implement category listaring use case`
* `e869926` - `feat(category): implement activate and inactivate category endpoints`
* `9d1cca1` - `feat(category): complete category module and improve exception handling`
* `aa6a37e` - `feat(venue): implement Venue module with Create (POST) use case`
* `af65397` - `feat(venue): implement listar venue use case`
* `5fe29d5` - `feat(venue): implement actualizar venue use case`
* `b8eec30` - `feat(venue): implement active and inactive use case`
* `679df05` - `docs(specs): align domain, entities, relationships and database model with player-centric design`
* `b76e1d2` - `refactor(category): introduce CategoryFinder to centralize category retrieval logic`

---

# Requirement Classification

## Functional

Capacidades visibles para el usuario o consumidor de la API.

Ejemplos:

* Health endpoint.
* Login JWT.
* Crear academia.
* Listar usuarios.

## Non-Functional

Condiciones de calidad, operacion o arquitectura.

Ejemplos:

* Docker obligatorio.
* Multi-tenant por `academy_id`.
* Soft delete.
* Auditoria.
* Stateless JWT.

## Technical Enabler

Piezas de infraestructura o runtime que habilitan la base funcional.

Ejemplos:

* `composer.json`.
* `Dockerfile`.
* `docker-compose.yml`.
* `Kernel.php`.
* Routing base.

---

# Next Steps
1. Validar endpoints de Academy con usuario `ROLE_ROOT` y usuario tenant.
2. Preparar el siguiente dominio de negocio sobre la misma base.
3. Iniciar el desarrollo del módulo `Sports` siguiendo el patrón de referencia de `Academy`.
4. Mantener trazabilidad por commit en cada iteracion.
---

# Working Rule

Cada cambio importante debera dejar trazabilidad en este documento o en el orden de ejecucion, con referencia al commit corresponseondiente y clasificacion funcional o no funcional.

---

# Current Iteration Notes

* SDD governance refined: `ADR-004` is accepted as the mandatory pagination standard; `docs/contracts/api-reference.md` is the canonical HTTP reference; the constitution now defines source precedence, accepted ADR authority, pre-generation context checks, and requirement-to-test traceability.
* Database migration standards formalized in `docs/database/migration-standards.md`; feature plans must document schema changes, tasks must include migrations, and quickstarts must validate them on `*_test`.

* Auth/JWT reordenado a `Modules/Identity`.
* El login no usa AuthController; se ejecuta desde el firewall `json_login`.
* `AccountUser` queda como entidad tecnica acoplada al framework por pragmatismo.
* El almacenamiento UUID ya esta normalizado como string legible en la tabla `users`.
* Login y `/auth/me` validados en runtime.
* `GET /api/v1/auth/me`, `PUT /api/v1/auth/me/name` y el flujo público de restablecimiento de contraseña quedaron implementados para usuarios.
* CRUD de users validado en runtime para contexto plataforrma, incluyendo crear, actualizar, disable y enable con responseuesta JSON estándar.
* Se introdujo una base HTTP común para evitar duplicación de validación y resolución del actor autenticado.
* La base de pruebas ya tiene su primer baseline unitario verde.
* La primera integración de signup de tenant ya corre contra base de datos MySQL de test y valida persistencia real.
* La estrategia de pruebas quedó separada por intención: unit tests sin BD y con mocks; integration y functional tests sobre `playertech_test`, usando `SchemaResetter` solo cuando el escenario necesita recrear el esquema completo.
* `SchemaResetter` ahora incluye una guardia explícita y rechaza cualquier base que no termine en `*_test` antes de ejecutar un borrado de esquema.
* `ROLE_ROOT` opera sin tenant; usuarios tenant requieren `academy_id` y `TenantContext`.
* `Academy` ya expone `GET /api/v1/academy/me` como contexto tenant, `PUT /api/v1/academy/me` para autogestión del tenant y `GET /api/v1/platforrm/academies` como API de plataforrma.
* La API de usuario autenticado quedó separada de la API de academia: `auth/me` expone identidad, `auth/me/name` actualiza sólo el nombre y el reset de contraseña usa endpoints públicos dedicados.
* `ProfileController` ahora reutiliza el flujo público de restablecimiento de contraseña desde `POST /api/v1/auth/me/password-reset/request`, usando el correo del usuario autenticado.
* La nomenclatura funcional de roles quedó alineada a `ROLE_ACADEMY_ADMIN` en docs y Postman; `ROLE_ROOT` sigue siendo el rol de plataforrma sin tenant.
* Los endpoints de `Academy` quedaron validados como parte del flujo base tenant/root y siguen protegidos por `TenantContext` y el filtro de persistencia.
* `Academy` ahora usa `AcademyId` como Doctrine custom type y VOs compartidos como embeddables XML, sirviendo como referencia del patrón para los demas modulos.
* Los VOs compartidos ya estan versionados en git y el mapping XML de `Academy` los consume de forrma consistente.
* La capa HTTP de `Academy` quedo delgada y delega en CQRS con commands, queries y handlers.
* `Academy` ya responseonde mediante DTOs de salida por caso de uso, incluyendo contratos anidados para flujos como tenant signup.
* `Academy` ahora expone `registrationSource` para distinguir tenants creados por `signup` y por `platforrm`, y ese dato también aparece en los listarados de academias.
* Los recursos de media se estandarizan como objetos JSON con `path`, `url`, `mime_type`, `size` y `checksum`; `Academy` expone `shield` con ese contrato y `Player` heredará el mismo patrón para `photo`.
* Los adjuntos documentales usan un contrato separado de `Media`, con `fileName` y `source`, para soportes PDF y documentos externos sin mezclarlo con imágenes.
* Existe una guia operativa para construir nuevos modulos sin depender de modelos previos.
* `Academy` queda definido como el modulo de referencia oficial para nuevos contextos: CQRS, XML puro, VOs tipados, soft delete, validacion forrmal, controllers delgados y separacion root/tenant.
* Se forrmalizó la regla de comunicación entre módulos: primero contratos de aplicación síncronos, luego eventos internos si aportan claridad, y `Messenger` solo ante una necesidad real de asincronía.
* Los comandos de validacion de pruebas y migraciones quedaron documentados en `docs/architecture/guides/testing-strategy.md`.
* La separacion de configuracion `local`/`test`/`prod` quedó documentada en `docs/architecture/guides/environment-guide.md`.
* La validacion principal de suites de integracion y funcionales debe correr sobre la base `test` para simular CI/CD; `local` queda para desarrollo interactivo.
* `Mailpit` queda adoptado como la herramienta base de desarrollo local para validar envios de correo y flujos de activacion.
* `EP-003` queda reorientada para distinguir usuarios de plataforrma y usuarios tenant; la creacion del owner/admin inicial del tenant se documenta como historia explicita.
* La creación de tenant por `signup` y por `platforrm` quedó unificada con trazabilidad explícita de origen en `Academy`.
* `Category` y `Venue` ya quedaron implementados como módulos funcionales completos y el backlog debe seguir su mismo lifecycle con historias faltantes o inconsistentes.
* `Category` y `Venue` comparten ahora el patrón de recuperación por `Finder`, reduciendo duplicación en handlers y homogeneizando Application.
* `Player` ya quedó documentado también en `docs/domains/player/player-domain-spec.md` como contrato central del dominio, para no depender solo del feature spec y del flujo de importación.
* El listarado de `Player` expone `photo`, `categoryName`, `genderName`, `age` y `createdAt`; además, el detalle, la forto y la importación masiva quedaron alineados con el contrato HTTP vigente.
* La importación de `Player` queda documentada como flujo central en `docs/flows/player/player-import-flow-spec.md` y su UX satélite en `docs/flows/player/player-import-ux-spec.md`.
* El backend ya valida la categoría seleccionada antes de crear un job de importación y la plantilla oficial de Excel se genera con referencias desde backend en una hoja `Referencias` con categorías activas, formatos correctos y tablas de valores válidos.
* `Player` ahora expone eliminación de forto mediante `DELETE /api/v1/academy/players/{playerId}/photo`, no solo subida/reemplazo.
* `Team`, `Venue` y `Category` también quedaron reforrzados con contrato central de dominio en `docs/domains/` para reflejar `categoryName`, sort normalizado, `categoryKey` estable y reglas de primaria/estado.
* `Academy`, `Identity`, `Guardian`, `Billing` y `Staff` también quedaron reforrzados con documento central de dominio en `docs/domains/`, alineando contexto tenant, reset de contraseña, relación base de acudientes, bloque financiero y selectors de staff con the backend real.
* Los módulos desarrollados ya usan XML Mapping Doctrine como estándar persistente; las entidades de `Academy`, `Category`, `Venue`, `Identity`, `Guardian`, `Player`, `Membership`, `PaymentConcept`, `Charge`, `Payment`, `Staff`, `Team` y `TeamAssignment` están responsealdadas por mapping XML explícito.
* `Player`, `Category`, `Venue`, `Team`, `Guardian`, `Academy`, `Membership`, `PaymentConcept`, `Charge`, `Payment`, `Staff` y `TeamAssignment` ya tienen superficie HTTP, DTOs y contratos de persistencia que deben reflejarse en la documentación antes de introducir nuevos cambios funcionales.
* `Category` y `Venue` ahora normalizan `sort` mediante un mapa seguro antes de construir el `ORDER BY`, evitando enviar el campo crudo a Doctrine.
* `Category` unifico el contrato de actualizar en `categoryKey` camelCase y el listarado ahora expone `academyId` para alinearse con el detalle.
* `Category` ahora genera `categoryKey` desde the backend a partir del `name`, eliminando ese campo del payload de crear/actualizar y manteniéndolo como contrato de salida.
* `Category` ahora expone `GET /api/v1/academy/categories/options` como listarado liviano sin paginación para combos de frontend.
* El backlog de `Category` ya tiene historias explícitas para listarar, actualizar y cambiar estado, alineadas con el código existente.
* `Venue` quedó homologado con `Category` mediante `Finder Services` y `ShowVenueQuery` ahora requiere contexto tenant completo.
* `CategoryController` y `VenueController` quedaron homogeneizados para usar el `TenantContext` del controlador y no mezclar inyección por parámetro.
* La jerarquía compartida de excepciones de `Shared` quedó aplicada y el `ProblemDetailsExceptionSubscriber` traduce por tipo base.
* `EP-007` quedó reescrita como inicio forrmal del dominio `Player` y ya tiene HUs mínimas para registrar, listarar, consultar, actualizar y deactivar.
* `HU-001` de `EP-007` quedó implementada y validada en runtime con `POST /api/v1/academy/players`.
* `HU-002` de `EP-007` quedó implementada y validada en runtime con `GET /api/v1/academy/players`.
* El listarado de `Player` ya expone `photo`, `categoryName`, `genderName`, `age` y `createdAt` como campos de salida, con filtros planeados por `gender`, `categoryId`, `createdAtFrom`, `createdAtTo`, `birthDateFrom` y `birthDateTo`.
* La importación masiva de `Player` quedó documentada como subfeature separada en `specs/007-player/import/` para mantener el lifecycle base limpio y desacoplado.
* La suspension de una academia bloquea a todos sus usuarios, pero no elimina ni deactiva usuarios en cascada.
* Las validaciones de negocio de `Academy` devuelven Problem Details JSON; el caso de duplicado de correo se resuelve con excepcion de dominio y responseuesta `409`.
* `Academy` incorpora soft delete con `deleted_at` y `deleted_by`, y Doctrine ya tiene un filtro global para excluir entidades borradas lógicamente.
* Se documentó una épica nueva para onboarding de tenant (`EP-014`) sin alterar el flujo de creación de tenants por `ROLE_ROOT`.
* El onboarding tenant ya tiene implementación base: signup público, correo de activación y endpoint de activación.
* El endpoint público de activación ahora es idempotente cuando el token sigue disponible: si el usuario vuelve a entrar al enlace, the backend puede marcar `alreadyActivated`; si el token no existe o expiró, responseonde `404` en Problem Details en lugar de `500`.
* La base de desarrollo `playertech` y la base de pruebas `playertech_test` están separadas por configuración; además, el bootstrap de PHPUnit y `SchemaResetter` bloquean el uso accidental de una base que no termine en `_test` y ya no borran datos por defecto.
* Existe deuda en migraciones antiguas: `Version20260704000000` falla al reejecutarse por un `DROP COLUMN logo` sobre una columna ya ausente, así que el `migrate` completo de `dev` queda bloqueado hasta corregir esa versión histórica.
* Para reparar el catálogo público de onboarding en `playertech` existe el comando `app:category:seed-onboarding`, útil cuando la tabla se vacía pero la migración ya figura como ejecutada.
* El contrato público de onboarding pasa a usar un catálogo global de categorías y el signup clonará la categoría elegida dentro de the academy; la implementación sigue pendiente.
* El signup público valida ahora duplicados de `contactEmail` y `phone` en backend; además, existe `GET /api/v1/public/tenants/availability` para que el front consulte disponibilidad reactiva de email y celular con un solo contrato.
* Se pobló el catálogo `onboarding_categories` con el rango `Sub 4` a `Sub 20` como base pública de onboarding para frontend y signup.
* `Player` quedó priorizado como siguiente módulo de negocio sobre `EP-009`, `EP-010` y `EP-012`.
* Se inició el módulo `Membership` como primer slice técnico de `EP-009`, con base de dominio, mapping XML, repositorio, controller y casos de uso de crear/consultar matrícula activa.
* `EP-009` quedó consolidada como módulo funcional completo: matrícula activa, cargos iniciales, historial, suspensión y retiro con cobertura unitaria y documentación HTTP operativa en Postman.
* El bloque financiero fue reordenado: `EP-009` genera cargos iniciales pendientes, `EP-011` administra conceptos de pago, `EP-012` gestiona cargos, pagos, evidencia y deuda, y `EP-013` resume cartera y estado operativo.
* La frontera financiera quedó estabilizada: `Membership` administra la matrícula y puede disparar cargos automáticos; `Charge` modela la deuda concreta; `Payment` registra el recaudo y `PaymentAllocation` distribuye ese recaudo sobre cargos existentes; `PaymentConcept` sigue siendo el catálogo reusable de motivos de cobro.
* `Membership` ya adopta el patrón de arquitectura esperado: validación en `Presentation`, `MembershipFinder` en `Application` y excepciones de dominio herederas de `Shared`.
* `EP-012` quedó cerrado funcional y técnicamente con `Charge`, `Payment`, `PaymentAllocation`, deuda, historial, evidencia y puedecelación; la validación final ya se cubrió sobre `test`.
* `EP-013` quedó materializada como dashboard operativo con jugadores activos, matrículas vigentes, cargos pendientes y resumen de cartera.
* `EP-011` quedó consolidada como módulo funcional completo de conceptos de pago: crear, listarar, consultar, actualizar, deactivar y generar automáticamente el `code` desde el `name`, con cobertura unitaria y documentación HTTP operativa en Postman.
* Se documentó el modelo financiero explícito en `docs/domains/billing/financial-domain-model.md` para separar `PaymentConcept`, `Charge`, `Payment`, `PaymentAllocation` y sus identificadores de negocio.
* El historial financiero ahora puede consultarse también por `guardianId` y el registro de pagos admite `allocations[]` para distribuir un pago entre varios cargos, manteniendo la conciliación parcial fuera del MVP.
* Se inició la estandarización de listarados con paginación uniforrme (`page`, `per_page`, `sort`, `direction`) en endpoints visibles por frontend.
* `EP-021` quedó materializada con el desarrollo del módulo `Staff` y `TeamStaffAssignment`, cobertura unitaria base, documentación API y colección Postman para el flujo de staff por equipo.
* `EP-010` quedó materializada con el módulo `TeamAssignment`, que introduce la relación jugador-equipo con historial, principal activo y finalización, y ahora deja explícito que la duplicidad solo se bloquea si existe una asignación activa al mismo equipo.
* `EP-005` equipos ya quedó cubierto como base de organización deportiva y sirve como referencia de CRUD tenant-scoped.
* La cobertura de pruebas para `Team` ya incluye dominio, persistencia Doctrine y endpoint HTTP crítico; las suites compartidas sobre MySQL se corren en serie para evitar colisiones de esquema.
* La subida de escudo institucional para `Academy` y la forto del jugador para `Player` ya quedaron implementadas como historias de media separadas.
* Las categorias ahora tienen `category_key` estable para soportar importaciones y contratos de integracion sin depender del UUID.
* La auditoria Doctrine ya quedó centralizada con un `AuditSubscriber` y el filtro `SoftDelete` está activo.
* `HU-003` de `EP-007` quedó implementada y validada en runtime con `GET /api/v1/academy/players/{playerId}`.
* `HU-004` de `EP-007` quedó implementada y validada en runtime con `PUT /api/v1/academy/players/{playerId}`.
* `HU-005` de `EP-007` quedó consolidada como gestión de estado del jugador: deactivar y reactivar con endpoints `PATCH /api/v1/academy/players/{playerId}/inactivate` y `/activate`.
* Se abrió la historia `HU-007` de `EP-007` para importación masiva de jugadores desde Excel como base de migración de datos.
* `HU-007` de `EP-007` quedó orientada a un flujo asíncrono con selección previa de categoría, plantilla generada por backend y polling de progreso.
* El módulo `Player` ahora incluye `category_id` como referencia opcional y el endpoint de importación masiva `POST /api/v1/academy/players/import` trabaja con `categoryId` por job, no por fila.
* La plantilla oficial de importación de jugadores se genera desde backend con hojas `Datos` y `Referencias`.
* El flujo documental central de importación de jugadores se reorganizó fuera de `docs/domains/player/` hacia `docs/flows/player/` para separar dominio puro de flujo y UX.
* `HU-013` de `EP-001` quedó implementada con `POST /api/v1/academy/me/shield` para subir y reemplazar el escudo institucional de the academy.
* El flujo `POST /api/v1/academy/me/shield` ahora valida MIME permitido antes de ir a `FileStorage`, alineándose con el patrón de `Player` para evitar errores genéricos por subidas inválidas.
* Se añadió cobertura funcional específica para `POST /api/v1/academy/me/shield` en `AcademyMeControllerTest`, validando la subida multipart de un PNG y la forrma básica del contrato `shield`.
* `DELETE /api/v1/academy/me/shield` quedó disponible para eliminar el escudo institucional con responseuesta `204 No Content`, y la referencia HTTP/frontend ya lo documenta como contrato oficial.
* Se amplió `EP-001` con el perfil básico de academia: `country`, `department`, `city`, `address`, teléforno normalizado y consentimientos legales obligatorios en el signup de tenant.
* La colección Postman quedó actualizada para reflejar el nuevo contrato de `Academy` y `TenantSignup`, incluyendo `country`, `department` y los consentimientos obligatorios.
* El perfil fiscal de academia vive actualmente dentro de `academies` y actúa como fuente operativa para comprobantes de pago; no se modeló todavía una tabla separada ni la integración de facturación electrónica DIAN.
* Se separó el contrato tenant de academia en dos endpoints: `GET /api/v1/academy/context` para el contexto operativo de sesión y `GET /api/v1/academy/me` para el perfil real de the academy, manteniendo intacto el CRUD de `platforrm/academies` para `ROLE_ROOT`.
* `HU-015` de `EP-001` quedó implementada con `POST /api/v1/platforrm/academies` para provisionar tenants completos desde la plataforrma con academia, owner/admin inicial, correo de bienvenida y primer equipo.
* La colección Postman quedó actualizada con contratos de ejemplo para `POST /api/v1/public/tenants/signup` y `POST /api/v1/platforrm/academies`.
* La colección Postman se usa como referencia operativa de contrato HTTP para el front mientras no exista Swagger/OpenAPI interactivo.
* Se documentó la futura épica `EP-023` para inforrmación tributaria de academias y comprobantes DIAN, separándola del perfil base de `Academy`.
* `EP-023` quedó refinada para cubrir inforrmación tributaria, comprobantes operativos descargables y soporte fiscal externo, dejando la capa DIAN como evolución futura.
* `EP-023` ya tiene su primera rebanada técnica implementada: perfil tributario de academia con endpoints de consulta y actualización desde `academy/me` y `platforrm/academies`.
* `EP-023` añadió el comprobante operativo de pago como recurso HTTP consultable desde `/api/v1/academy/payments/{paymentId}/receipt`, generado a partir del pago y del concepto asociado.
* `EP-023` añadió la vinculación de soportes fiscales en PDF con `POST /api/v1/academy/fiscal-attachments`, manteniendo la emisión fiscal fuera del core.
* `EP-023` quedó como fuente principal para los datos fiscales de the academy usados en comprobantes, mientras `EP-006` complementa la inforrmación del acudiente con documento, dirección y correo opcional.
* El comprobante operativo de pago ahora toma los datos fiscales principales de the academy para que la emisión parta de un emisor principal/default coherente.
* El perfil fiscal del MVP se presenta al usuario como `Inforrmación fiscal` y se mantiene como un único perfil principal por academia.
* La documentación operativa de `EP-006` ya refleja el alta de acudientes con `documentType`, `documentNumber`, `address` y `relationship` para que el front consuma el contrato actualizado.
* Los comprobantes de pago del MVP deben tomar siempre the academy marcada como principal/default para los datos fiscales del emisor.
* `HU-009` de `EP-007` quedó implementada con `PATCH /api/v1/academy/players/{playerId}/photo` para subir y reemplazar la forto del jugador.
* `HU-009` de `EP-003` quedó implementada: el signup público crea el primer equipo con `category_id` y `team_name`, validando categoría activa y duplicados por academia/categoría; la responseuesta del alta usa un contract específico para onboarding y no el responseonse operativo de `Team`.
* El MVP checklistar debe mantener como cerradas las historias de media ya implementadas: escudo institucional de `Academy` y forto de `Player`.
* `EP-006` ya expone lectura y creación de acudientes por academia en HTTP, incluyendo el campo `relationship`, y `EP-008` ya cubre la relación jugador-acudiente con alta de acudiente, asociación, cambio de principal, eliminación lógica y vista por jugador.
* `EP-006` completó su ciclo de vida de acudientes con actualización, inactivación y reactivación, cerrando el contrato completo del módulo.
* El bloque de módulos aún pendiente para el MVP ya no incluye `EP-012`; `EP-008`, `EP-009`, `EP-010`, `EP-011`, `EP-012` y `EP-013` ya se consideran resueltos.
* La capa fiscal forrmal sigue fuera del MVP y quedó concentrada en `EP-023`.
* Se documentó una auditoría SDD dthe backend en `docs/architecture/audits/SDD-backend-audit.md`, con diagnóstico de madurez, vacíos de trazabilidad y propuesta incremental de adopción.
* Se adoptó una versión liviana de SDD para trabajo individual: `docs/contracts/api-reference.md` queda como referencia HTTP operativa principal y `AGENTS.md` incorpora reglas simples de puedeonicidad y trazabilidad mínima.
* Se consolidó un índice de contratos HTTP en `docs/contracts/api-reference.md` para centralizar la sincronización con frontend y QA sin duplicar la especificación operativa.
* Se forrmalizó una política SDD escalonada en `docs/architecture/policies/sdd-policy.md` y dos plantillas de cambio en `docs/architecture/templates/change-template-light.md` y `docs/architecture/templates/change-template-full.md` para futuras features.
* Se documentó la evolución del modelo de cobro de `EP-009` en `docs/domains/billing/billing-evolution-notes.md`, incluyendo el estado actual, casos de uso reales y los diagramas de flujo actual y objetivo.
* Se redefinió el perfil base de `Player` en `docs/domains/domain-overver.md` para separar identidad, atributos deportivos y datos que deben vivir en asignaciones o compras.
* Se documentó un criterio SDD para la evolución del perfil de `Player` en `docs/domains/player/player-profile-evolution-notes.md`, con reglas para decidir qué atributos viven en el aggregate y cuáles deben quedar fuera.
* El perfil base de `Player` incorporó `email` y `phone` como datos de contacto opcionales, sincronizados entre dominio, API, Postman y persistencia.
* Se documentó una estrategia local-first de observabilidad en `docs/architecture/guides/observability-local-guide.md` para logs estructurados, correlation id y metricas basicas sin depender aun de una plataforrma externa.
---

# Technical Foundation Checklistar

## Done

* Docker stack base.
* Symforny runtime base.
* Doctrine y migraciones base.
* Tabla tecnica `users`.
* UUID como string legible (`CHAR(36)`) en `users`.
* Login JWT mediante Symforny Security `json_login`.
* Endpoint `/api/v1/auth/me`.
* Comando `app:user:crear-root`.
* Identity ubicado bajo `Modules/Identity`.
* Health endpoint ubicado en `Shared`.
* La validación de alta y actualización de usuarios en `Identity` ya vive en `Presentation`; `Application` conserva DTOs sin dependencias del framework para ese flujo.
* La validación de `Academy` para crear, actualizar, signup y autogestión ya vive en `Presentation`; `Application` conserva DTOs sin dependencias del framework para esos flujos.
* La validación de `Category` para crear y actualizar ya vive en `Presentation`; `Application` conserva DTOs sin dependencias del framework para esos flujos.
* La validación de `Venue` para crear y actualizar ya vive en `Presentation`; `Application` conserva DTOs sin dependencias del framework para esos flujos.
* La validación de `Team` para crear y actualizar ya vive en `Presentation`; `Application` conserva DTOs sin dependencias del framework para esos flujos.
* La validación de `Player` para crear, actualizar y asociación de acudiente ya vive en `Presentation`; `Application` conserva DTOs sin dependencias del framework para esos flujos.
* La validación de `Guardian` para crear ya vive en `Presentation`; `Application` conserva DTOs sin dependencias del framework para ese flujo.
* La validación de `PaymentConcept` para crear y actualizar ya vive en `Presentation`; `Application` conserva DTOs sin dependencias del framework para esos flujos.
* El signup público de tenant volvió a requerir `phone`, `country`, `department`, `city` y `address`; al registrarse se crea también la sede principal en `venues` con esos datos y se marca como `is_primary`.

## Checklistar de Base Técnica Sólida (Critical Path)

Para considerar la base listara antes de implementar cualquier lógica de negocio, debemos cerrar estos puntos:

### 1. Multi-Tenant Infrastructure
- [x] **TenantContext**: Objeto inmutable/servicio que contenga el `academy_id` activo.
- [x] **JWT Custom Claims**: Incluir `academy_id` en el payload generado para usuarios no-root.
- [x] **TenantResolver**: Listener que capture el JWT, extraiga el `academy_id` e hidrate el `TenantContext`.
- [x] **Doctrine Tenant Filter**: Filtro SQL automático que aplique `WHERE academy_id = X` en todas las queries de negocio.

### 2. Security & Routing Separation
- [x] **Platforrm Firewall/Access**: Bloquear rutas `/api/v1/platforrm/*` solo para `ROLE_ROOT`.
- [x] **Tenant Access Enforrcement**: Validar que si el usuario no es Root, el `TenantContext` *deba* estar presente; de lo contrario, devolver 403.

### 3. API Reliability
- [x] **ProblemDetails (RFC 9457)**: Subscriber para capturar excepciones y devolver el forrmato estándar de errores.
- [x] **Validation Mapping**: Convertir errores de `symforny/validator` al forrmato `ProblemDetails`.

### 4. Audit & Persistence
- [x] **AuditSubscriber**: Automatizar el llenado de `created_by` y `updated_by` usando el usuario del Token.
- [x] **SoftDelete Filter**: Asegurar que las consultas excluyan registros con `deleted_at` por defecto.

### 5. Validation
- [x] **Test de Aislamiento**: Prueba técnica que confirme que un usuario de la Academia A no puede ver datos de la Academia B aunque conozca el ID.

---

## Pending Features (Post-Foundation)

* Flujo de creación de Academia (exclusivo para Root).
* Formalizar el onboarding de tenant como siguiente bloque funcional tras `EP-001`.
* Reutilizar `Academy` como plantilla de implementacion para los siguientes modulos.
* Completar el backlog de `Category` con historias explícitas para listarar, actualizar, activar e inactivar, porque ya existen en código.
* Cerrar el bloque restante del MVP en este orden: `EP-013`.
* A partir de ese cierre, desarrollar los ADR faltantes sobre decisiones ya estabilizadas.

---

# MVP Pending Checklistar

## Foundation y Seguridad

- [x] Doctrine Tenant Filter global para aislar consultas por `academy_id`.
- [x] AuditSubscriber para `created_by` y `updated_by`.
- [x] SoftDelete Filter global para excluir registros borrados lógicamente.
- [x] Test de aislamiento cross-tenant para validar que una academia no vea datos de otra.

## Academy y Onboarding

- [x] Validar runtime de endpoints de `Academy` con usuario `ROLE_ROOT` y con usuario tenant.
- [x] Cerrar el flujo de signup de tenant con revisión final de contrato de correo y activación.

## PlayerTech Core MVP

- [x] `EP-007` Player base: registrar, listarar, ver detalle, actualizar y gestionar estado.
- [x] `EP-007` importación masiva de jugadores por Excel.
- [x] `EP-001` Escudo institucional de academia.
- [x] `EP-001` Perfil básico de academia con ubicación y consentimientos.
- [x] `EP-007` Foto del jugador.
- [x] `EP-008` Relaciones jugador-acudiente.
- [x] `EP-005` Equipos.
- [x] `EP-009` Matrículas y seguimiento de pagos.
- [x] `EP-010` Asignaciones deportivas.
- [x] `EP-011` Conceptos de pago.
- [x] `EP-012` Cargos y pagos.
- [x] `EP-013` Dashboard operativo.

## Base Operativa

- [x] Consolidar la documentación HTTP operativa en Postman y retirar los archivos HTTP duplicados.
- [x] Revisión final de `README` y guía de ejecución para el siguiente bloque funcional.
* `EP-010` ya quedó desglosada en historias explícitas para asignar, marcar principal, cambiar principal, finalizar y consultar asignaciones deportivas.
* La consulta de `EP-010` para asignaciones de jugador ahora se orienta a una responseuesta compuesta con `team` anidado para evitar lookups adicionales en frontend.
* `Team` quedó homologado con `Venue` en el patrón HTTP clave: `sort` seguro, validación de `name` alineada al VO compartido y ejemplos de Postman corregidos para reflejar el contrato real.
* `Team` ahora expone `categoryName` plano en listar/show/crear/actualizar para simplificar el consumo dthe frontend sin introducir un objeto `category` anidado.
* `Staff` expone `/api/v1/academy/staff/options` como contrato único de selector liviano, filtrando por academia, rol y estado sin hidratar entidades completas.
* `Staff` extiende `/api/v1/academy/staff/options` con `teamId` para devolver solo staff disponibles para asignación, excluyendo los ya vinculados al equipo en `team_staff_assignments`.
* `EP-021` añadió el flujo unificado de alta de staff con acceso en `POST /api/v1/academy/staff/onboarding`, creando usuario y staff en una sola operación y resolviendo invitación o contraseña inicial.
* El flujo público de activación de usuarios separa enlace y confirmación: el correo apunta a `APP_AUTH_FRONTEND_URL/activate-account/{token}` y `GET /api/v1/public/users/activate/{token}` redirige al frontend, mientras `POST /api/v1/public/users/activate/{token}` conserva la activación real con contraseña.
* `Staff` expone ahora `POST /api/v1/academy/staff/{userId}/activation/resend` con `mode = INVITATION|PASSWORD`; en invitación reenvía el enlace público y en password regenera la clave oficial, renueva el token y vuelve a notificar credenciales + activación.
* `Staff` expone también `GET /api/v1/academy/staff/{userId}` como detalle de ficha con `accessMode` calculado en backend, para que the frontend no infiera el modo de acceso desde `status`.
* El onboarding de staff ahora separa invitación y envío de clave: `sendInvitation=true` mantiene el flujo de invitación y `sendInvitation=false` genera una contraseña oficial, deja la cuenta en `PENDING_ACTIVATION` y envía usuario, clave temporal y enlace público de activación.
* `Category` ahora trata `description` como opcional de extremo a extremo: el request lo acepta nulo, el embeddable Doctrine lo persiste como nullable y la migración `Version20260725000100` alinea la base de datos con el contrato actual de creación.
* `EP-006` funciona como módulo maestro de acudientes con listarado, detalle y creación; `EP-008` queda como módulo operativo para relaciones jugador-acudiente y vista de acudientes por jugador.
* `EP-003` ya incorporó el flujo inicial de usuarios administrativos por invitación y activación con correo, como primer slice de la evolución de staff.
* `EP-002` amplió el contrato de sedes para exponer `address` y `phone` opcionales también en el listarado, no solo en el detalle.
* El listarado de `Venue` ahora normaliza `sort` permitidos en backend; `created_at` y aliases históricos se traducen a `auditTrail.createdAt.value` para evitar errores 500 por paths internos de Doctrine.
* El listarado de `Venue` quedó validado con una prueba unitaria de código puro (`ListVenuesHandlerTest`) ejecutada dentro del contenedor, sin BD, para confirmar el contrato de responseuesta del handler.
* `EP-024` incorporó la gestión privada de documentos del jugador: listado paginado, carga validada, vista inline, descarga, reemplazo conservando el UUID y eliminación con soft delete y borrado físico.
* Se corrigió el wiring del repositorio de jobs de importación de jugadores y se formalizó el UUID/mapping XML de `PlayerImportJob`, desbloqueando el arranque del contenedor Symfony.
* Los campos monetarios `DECIMAL(12,2)` ahora usan el tipo DBAL explícito `decimal_float`, compatible con las propiedades `float` existentes y con validación Doctrine limpia.
* EP-024 quedó validada con pruebas unitarias, integración de repositorio y prueba funcional HTTP de carga, listado, vista, descarga, reemplazo y eliminación.
* EP-024 quedó alineada con la colección Postman, incluyendo ejemplos exitosos y errores previsibles; el listado valida primero la pertenencia del jugador para evitar respuestas cross-tenant ambiguas.
* EP-025 centralizó el catálogo global de tipos de documento en `Shared`, publicó `GET /api/v1/academy/document-types/options` y dejó la ruta anterior bajo `players` fuera del contrato oficial.
* EP-025 también incorporó el catálogo global de parentescos en `Shared`, publicó `GET /api/v1/academy/relationships/options` y alineó la documentación operativa con el mismo patrón de catálogo estático.
* La estrategia de pruebas separa suites `unit`, `integration`, `functional` y `contract`; la guía de ejecución define cuándo usar `playertech_test`, cómo preparar migraciones y cómo ensayar actualizaciones con datos.
* Se formalizó el handoff entre chats Codex mediante contratos versionados en `specs/*/contracts/`, commit de referencia y la guía `frontend-contract-integration-guide.md`; el frontend no debe depender de prompts repetidos ni inferir contratos desde el código backend.
