# Current State

Este documento registra el estado actual de la base tecnica, su trazabilidad y el criterio para continuar la siguiente iteracion.

---

# Implemented Foundation

La base tecnica actual incluye:

* README de entrada del repositorio.
* Estructura inicial del proyecto.
* Contenedores Docker para app y MySQL.
* Runtime minimo de Symfony.
* Endpoint de salud en `/api/v1/health`.
* Configuracion base de Doctrine, Security, JWT y OpenAPI.
* Primer commit de foundation.

---

# Traceability

| Item | Type | Status | Commit | Notes |
| ---- | ---- | ------ | ------ | ----- |
| README base | Documentation | Done | `b40e311` | Entrada principal del repositorio |
| Foundation bootstrap | Technical Enabler | Done | `7c3de8e` | Symfony, Docker, health endpoint y base runtime |
| Health endpoint | Functional | Done | `7c3de8e` | `/api/v1/health` responde JSON |
| Docker stack | Non-Functional / Technical Enabler | Done | `7c3de8e` | Ejecucion dentro de contenedores |
| Identity auth module refactor | Technical Enabler | Done | `87f6f9b` | Login resuelto por Symfony Security `json_login`; `/me`, handlers JWT y entidad movidos a `Modules/Identity` |
| Identity technical user model | Technical Enabler | Done | `87f6f9b` | `AccountUser` usa Doctrine attributes y GUID string para acelerar la foundation sin perder compatibilidad |
| Identity users CRUD runtime | Functional / Technical Enabler | Done | `untracked` | CRUD de users expuesto en `/api/v1/platform/users` y `/api/v1/academy/users`, con DTOs, handlers, exceptions de dominio y respuesta JSON estándar |
| API controller foundation | Technical Enabler | Done | `untracked` | Base HTTP común para validación y resolución del usuario autenticado, reduciendo duplicación entre controladores |
| First unit test baseline | Technical Enabler | Done | `untracked` | PHPUnit inicial valida `AcademyId`, `AccountUser` y `UserAdministrationPolicy` |
| Tenant signup integration test | Technical Enabler | Done | `untracked` | `RegisterTenantHandler` valida alta de tenant contra una base de datos MySQL de test con bus de mensajes desacoplado |
| Category module completion | Functional / Technical Enabler | Done | `9d1cca1` | `Category` quedo completa con create, list, update, activate, inactivate y Finder centralizado |
| Venue module completion | Functional / Technical Enabler | Done | `b8eec30` | `Venue` quedo completa con create, list, update, activate e inactivate |
| Spec domain alignment | Documentation | Done | `679df05` | Se alinearon dominio, entidades, relaciones y modelo de base de datos con el diseño player-centric |
| Shared health endpoint | Technical Enabler | Done | `87f6f9b` | HealthController moved to Shared/Presentation/Http |
| Legacy folder cleanup | Technical Enabler | Done | `87f6f9b` | Eliminados `src/Command`, `src/Controller`, `src/Entity`, `src/EventSubscriber` y `src/Security` heredados |
| Root platform command | Technical Enabler | Done | `87f6f9b` | `app:user:create-root` registra usuarios `ROLE_ROOT` sin tenant |
| UUID storage conversion | Technical Enabler | Done | `87f6f9b` | La tabla `users` paso a UUID legible como string (`CHAR(36)`) |
| Platform vs tenant identity contexts | Architectural Constraint / Technical Enabler | Done | `fc14bd8` | ROLE_ROOT opera sin tenant; usuarios tenant requieren `academy_id` y `TenantContext` |
| Auth JWT | Functional | Done | `87f6f9b` | Login JWT y `/api/v1/auth/me` operativos |
| Tenant context | Non-Functional / Architectural Constraint | Done | `fc14bd8` | `TenantContext` resuelve el contexto de plataforma y tenant desde el JWT |
| Academy module bootstrap | Functional / Technical Enabler | Done | `e795224` | Primer endpoint tenant-scoped `GET /api/v1/academy/me` valida contexto de academia |
| Academy management endpoints | Functional / Technical Enabler | Done | `bc2d4e1` | Refactorización a Arquitectura Hexagonal completa. Archivos movidos a `app/src/Modules/Academy`. Módulo sirve como referencia técnica. |
| Shared typed value objects | Technical Enabler | Done | `bcc18f2` | `Name`, `Email`, `Address`, `City`, `PhoneNumber`, `LogoPath`, `CreatedAt` y `UpdatedAt` quedan tipados como VOs reutilizables |
| Academy typed VO mapping foundation | Technical Enabler | Done | `5f95e40` | `AcademyId` usa Doctrine custom type y el XML de `Academy` consume los VOs compartidos como embeddables |
| Tenant academy profile update | Functional | Done | `5f95e40` | `PUT /api/v1/academy/me` permite que el tenant actualice su propia academia |
| Academy CQRS application refactor | Technical Enabler | Done | `ff61ec1` | Los casos de uso de `Academy` pasaron a `Application/Command`, `Application/Query` y `Application/Handler` |
| Module creation guide | Documentation / Technical Enabler | Done | `0801f4f` | Guia operativa para crear nuevos modulos siguiendo el patron de `Academy` |
| Tenant signup onboarding spec | Documentation / Functional | Done | `untracked` | Nueva épica `EP-014` y HU-001 para alta simplificada de tenant con activación por correo |
| Tenant signup runtime implementation | Functional / Technical Enabler | Done | `untracked` | Signup tenant, activación por correo, Mailpit y flujo de login validado |
| Player module base | Functional / Technical Enabler | Done | `untracked` | `Player` arranca con `POST /api/v1/academy/players`, custom type UUID, XML mapping y test unitario del alta |
| Team module baseline | Functional / Technical Enabler | Done | `untracked` | `Team` arranca con CRUD tenant-scoped, custom type UUID, XML mapping y controladores delgados sobre `/api/v1/academy/teams` |
| Team test baseline | Technical Enabler | Done | `untracked` | Cobertura inicial de `Team` con unit, integration y functional tests; las suites con MySQL compartido se ejecutan en serie |
| Player list baseline | Functional / Technical Enabler | Done | `untracked` | `GET /api/v1/academy/players` lista jugadores del tenant actual con DTO resumido y prueba unitaria |
| Player detail baseline | Functional / Technical Enabler | Done | `untracked` | `GET /api/v1/academy/players/{playerId}` devuelve detalle del jugador dentro del tenant con `PlayerResponse` y prueba unitaria |
| Player update baseline | Functional / Technical Enabler | Done | `untracked` | `PUT /api/v1/academy/players/{playerId}` actualiza datos del jugador dentro del tenant con validación de unicidad y prueba unitaria |
| Player status management | Functional / Technical Enabler | Done | `untracked` | `PATCH /api/v1/academy/players/{playerId}/inactivate` y `/activate` cambian el estado del jugador con cobertura unitaria |
| Player status management story | Functional / Documentation | Done | `untracked` | HU-005 consolidada documenta desactivar y reactivar como una sola gestion de estado |
| Player bulk import baseline | Functional / Technical Enabler | Done | `untracked` | `POST /api/v1/academy/players/import` permite carga masiva desde Excel con `category_key` y validación completa por fila |
| Category business key foundation | Functional / Technical Enabler | Done | `untracked` | `Category` ahora expone `category_key` estable, unico por academia, para contratos API e importaciones |
| Guardian module foundation | Functional / Technical Enabler | Done | `untracked` | `LegalGuardian` queda disponible como aggregate root con XML puro, custom type UUID y endpoint de alta dentro de Academy |
| PlayerGuardian relation foundation | Functional / Technical Enabler | Done | `untracked` | `PlayerGuardian` cubre asociar, cambiar principal y eliminar relación con soft delete y aislamiento por academia |
| Doctrine Tenant Filter | Non-Functional / Technical Enabler | Done | `untracked` | Filtro global que aísla automáticamente las consultas por `academy_id` para seguridad multi-tenant |
| Doctrine AuditSubscriber | Non-Functional / Technical Enabler | Done | `untracked` | Filler centralizado de `auditTrail` para entidades auditable en persistencia Doctrine |
| Cross-tenant isolation test | Technical Enabler | Done | `untracked` | Prueba de integración valida que una academia no puede leer registros de otra aunque conozca el ID |
| Tenant signup initial team | Functional | Done | `untracked` | `POST /api/v1/public/tenants/signup` recibe `category_id` y `team_name`, valida la categoría y crea el primer equipo del tenant |

---

# Commit References

* `7c3de8e` - `chore: bootstrap PlayerTech API foundation`
* `b40e311` - `docs: improve project README`
* `87f6f9b` - `feat(identity): align technical foundation and docs`
* `fc14bd8` - `feat(identity): add tenant context foundation`
* `e795224` - `feat(academy): add tenant academy context endpoint`
* `bc2d4e1` - `refactor(academy): apply hexagonal architecture and domain purity`
* `bcc18f2` - `feat(shared): add typed academy value objects`
* `5f95e40` - `feat(academy): introduce typed vo mapping`
* `ff61ec1` - `refactor(academy): move use cases to application handlers`
* `419ded4` - `feat(academy): implement academy management endpoints`
* `37cc830` - `feat(identity): add users crud foundation`
* `ae7cbc7` - `refactor(identity): reduce controller duplication`
* `f02ee94` - `test(identity): add initial unit test baseline`
* `9f72c99` - `test(academy): add mysql-backed tenant signup integration`
* `72bba8a` - `feat(category): implement update use case and refine category management`
* `0e2d016` - `feat(category): implement category listing use case`
* `e869926` - `feat(category): implement activate and inactivate category endpoints`
* `9d1cca1` - `feat(category): complete category module and improve exception handling`
* `aa6a37e` - `feat(venue): implement Venue module with Create (POST) use case`
* `af65397` - `feat(venue): implement list venue use case`
* `5fe29d5` - `feat(venue): implement update venue use case`
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

Cada cambio importante debera dejar trazabilidad en este documento o en el orden de ejecucion, con referencia al commit correspondiente y clasificacion funcional o no funcional.

---

# Current Iteration Notes

* Auth/JWT reordenado a `Modules/Identity`.
* El login no usa AuthController; se ejecuta desde el firewall `json_login`.
* `AccountUser` queda como entidad tecnica acoplada al framework por pragmatismo.
* El almacenamiento UUID ya esta normalizado como string legible en la tabla `users`.
* Login y `/auth/me` validados en runtime.
* CRUD de users validado en runtime para contexto plataforma, incluyendo create, update, disable y enable con respuesta JSON estándar.
* Se introdujo una base HTTP común para evitar duplicación de validación y resolución del actor autenticado.
* La base de pruebas ya tiene su primer baseline unitario verde.
* La primera integración de signup de tenant ya corre contra base de datos MySQL de test y valida persistencia real.
* `ROLE_ROOT` opera sin tenant; usuarios tenant requieren `academy_id` y `TenantContext`.
* `Academy` ya expone `GET /api/v1/academy/me` como contexto tenant, `PUT /api/v1/academy/me` para autogestión del tenant y `GET /api/v1/platform/academies` como API de plataforma.
* Los endpoints de `Academy` quedaron validados como parte del flujo base tenant/root y siguen protegidos por `TenantContext` y el filtro de persistencia.
* `Academy` ahora usa `AcademyId` como Doctrine custom type y VOs compartidos como embeddables XML, sirviendo como referencia del patrón para los demas modulos.
* Los VOs compartidos ya estan versionados en git y el mapping XML de `Academy` los consume de forma consistente.
* La capa HTTP de `Academy` quedo delgada y delega en CQRS con commands, queries y handlers.
* `Academy` ya responde mediante DTOs de salida por caso de uso, incluyendo contratos anidados para flujos como tenant signup.
* Los recursos de media se estandarizan como objetos JSON con `path`, `url`, `mime_type`, `size` y `checksum`; `Academy` expone `shield` con ese contrato y `Player` heredará el mismo patrón para `photo`.
* Existe una guia operativa para construir nuevos modulos sin depender de modelos previos.
* `Academy` queda definido como el modulo de referencia oficial para nuevos contextos: CQRS, XML puro, VOs tipados, soft delete, validacion formal, controllers delgados y separacion root/tenant.
* Los comandos de validacion de pruebas y migraciones quedaron documentados en `specs/11-testing-strategy.md`.
* La separacion de configuracion `local`/`test`/`prod` quedó documentada en `specs/17-environment-guide.md`.
* `Mailpit` queda adoptado como la herramienta base de desarrollo local para validar envios de correo y flujos de activacion.
* `EP-003` queda reorientada para distinguir usuarios de plataforma y usuarios tenant; la creacion del owner/admin inicial del tenant se documenta como historia explicita.
* `Category` y `Venue` ya quedaron implementados como módulos funcionales completos y el backlog debe seguir su mismo lifecycle con historias faltantes o inconsistentes.
* `Category` y `Venue` comparten ahora el patrón de recuperación por `Finder`, reduciendo duplicación en handlers y homogeneizando Application.
* El backlog de `Category` ya tiene historias explícitas para listar, actualizar y cambiar estado, alineadas con el código existente.
* `Venue` quedó homologado con `Category` mediante `Finder Services` y `ShowVenueQuery` ahora requiere contexto tenant completo.
* `CategoryController` y `VenueController` quedaron homogeneizados para usar el `TenantContext` del controlador y no mezclar inyección por parámetro.
* La jerarquía compartida de excepciones de `Shared` quedó aplicada y el `ProblemDetailsExceptionSubscriber` traduce por tipo base.
* `EP-007` quedó reescrita como inicio formal del dominio `Player` y ya tiene HUs mínimas para registrar, listar, consultar, actualizar y desactivar.
* `HU-001` de `EP-007` quedó implementada y validada en runtime con `POST /api/v1/academy/players`.
* `HU-002` de `EP-007` quedó implementada y validada en runtime con `GET /api/v1/academy/players`.
* La suspension de una academia bloquea a todos sus usuarios, pero no elimina ni desactiva usuarios en cascada.
* Las validaciones de negocio de `Academy` devuelven Problem Details JSON; el caso de duplicado de correo se resuelve con excepcion de dominio y respuesta `409`.
* `Academy` incorpora soft delete con `deleted_at` y `deleted_by`, y Doctrine ya tiene un filtro global para excluir entidades borradas lógicamente.
* Se documentó una épica nueva para onboarding de tenant (`EP-014`) sin alterar el flujo de creación de tenants por `ROLE_ROOT`.
* El onboarding tenant ya tiene implementación base: signup público, correo de activación y endpoint de activación.
* `Player` quedó priorizado como siguiente módulo de negocio sobre `EP-009`, `EP-010` y `EP-012`.
* Se inició el módulo `Membership` como primer slice técnico de `EP-009`, con base de dominio, mapping XML, repositorio, controller y casos de uso de crear/consultar matrícula activa.
* `EP-005` equipos ya quedó cubierto como base de organización deportiva y sirve como referencia de CRUD tenant-scoped.
* La cobertura de pruebas para `Team` ya incluye dominio, persistencia Doctrine y endpoint HTTP crítico; las suites compartidas sobre MySQL se corren en serie para evitar colisiones de esquema.
* La subida de escudo institucional para `Academy` y la foto del jugador para `Player` ya quedaron implementadas como historias de media separadas.
* Las categorias ahora tienen `category_key` estable para soportar importaciones y contratos de integracion sin depender del UUID.
* La auditoria Doctrine ya quedó centralizada con un `AuditSubscriber` y el filtro `SoftDelete` está activo.
* `HU-003` de `EP-007` quedó implementada y validada en runtime con `GET /api/v1/academy/players/{playerId}`.
* `HU-004` de `EP-007` quedó implementada y validada en runtime con `PUT /api/v1/academy/players/{playerId}`.
* `HU-005` de `EP-007` quedó consolidada como gestión de estado del jugador: desactivar y reactivar con endpoints `PATCH /api/v1/academy/players/{playerId}/inactivate` y `/activate`.
* Se abrió la historia `HU-007` de `EP-007` para importación masiva de jugadores y categorías desde Excel como base de migración de datos.
* `HU-007` de `EP-007` quedó implementada con carga masiva de jugadores desde Excel, validación de categorías y rechazo total ante errores.
* El módulo `Player` ahora incluye `category_id` como referencia opcional y el endpoint de importación masiva `POST /api/v1/academy/players/import` consume `category_key` como referencia de negocio.
* `HU-013` de `EP-001` quedó implementada con `POST /api/v1/academy/me/shield` para subir y reemplazar el escudo institucional de la academia.
* `HU-009` de `EP-007` quedó implementada con `PATCH /api/v1/academy/players/{playerId}/photo` para subir y reemplazar la foto del jugador.
* `HU-009` de `EP-003` quedó implementada: el signup público crea el primer equipo con `category_id` y `team_name`, validando categoría activa y duplicados por academia/categoría.
* El MVP checklist debe mantener como cerradas las historias de media ya implementadas: escudo institucional de `Academy` y foto de `Player`.
* `EP-008` quedó implementada para relaciones jugador-acudiente con alta de acudiente, asociación, cambio de principal y eliminación lógica.
---

# Technical Foundation Checklist

## Done

* Docker stack base.
* Symfony runtime base.
* Doctrine y migraciones base.
* Tabla tecnica `users`.
* UUID como string legible (`CHAR(36)`) en `users`.
* Login JWT mediante Symfony Security `json_login`.
* Endpoint `/api/v1/auth/me`.
* Comando `app:user:create-root`.
* Identity ubicado bajo `Modules/Identity`.
* Health endpoint ubicado en `Shared`.

## Checklist de Base Técnica Sólida (Critical Path)

Para considerar la base lista antes de implementar cualquier lógica de negocio, debemos cerrar estos puntos:

### 1. Multi-Tenant Infrastructure
- [x] **TenantContext**: Objeto inmutable/servicio que contenga el `academy_id` activo.
- [x] **JWT Custom Claims**: Incluir `academy_id` en el payload generado para usuarios no-root.
- [x] **TenantResolver**: Listener que capture el JWT, extraiga el `academy_id` e hidrate el `TenantContext`.
- [x] **Doctrine Tenant Filter**: Filtro SQL automático que aplique `WHERE academy_id = X` en todas las queries de negocio.

### 2. Security & Routing Separation
- [x] **Platform Firewall/Access**: Bloquear rutas `/api/v1/platform/*` solo para `ROLE_ROOT`.
- [x] **Tenant Access Enforcement**: Validar que si el usuario no es Root, el `TenantContext` *deba* estar presente; de lo contrario, devolver 403.

### 3. API Reliability
- [x] **ProblemDetails (RFC 9457)**: Subscriber para capturar excepciones y devolver el formato estándar de errores.
- [x] **Validation Mapping**: Convertir errores de `symfony/validator` al formato `ProblemDetails`.

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
* Completar el backlog de `Category` con historias explícitas para listar, actualizar, activar e inactivar, porque ya existen en código.
* Continuar con `EP-007` para cerrar `HU-002` a `HU-005` y luego retomar `EP-009` -> `EP-010` -> `EP-012`.

---

# MVP Pending Checklist

## Foundation y Seguridad

- [x] Doctrine Tenant Filter global para aislar consultas por `academy_id`.
- [x] AuditSubscriber para `created_by` y `updated_by`.
- [x] SoftDelete Filter global para excluir registros borrados lógicamente.
- [x] Test de aislamiento cross-tenant para validar que una academia no vea datos de otra.

## Academy y Onboarding

- [x] Validar runtime de endpoints de `Academy` con usuario `ROLE_ROOT` y con usuario tenant.
- [x] Cerrar el flujo de signup de tenant con revisión final de contrato de correo y activación.

## PlayerTech Core MVP

- [x] `EP-007` Player base: registrar, listar, ver detalle, actualizar y gestionar estado.
- [x] `EP-007` importación masiva de jugadores por Excel.
- [x] `EP-001` Escudo institucional de academia.
- [x] `EP-007` Foto del jugador.
- [x] `EP-008` Relaciones jugador-acudiente.
- [x] `EP-005` Equipos.
- [ ] `EP-009` Matrículas y seguimiento de pagos.
- [ ] `EP-012` Pagos.

## Base Operativa

- [x] Consolidar archivos `.http` con ejemplos de éxito y error por módulo.
- [x] Revisión final de `README` y guía de ejecución para el siguiente bloque funcional.


