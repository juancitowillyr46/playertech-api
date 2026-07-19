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
| Identity validation migration | Technical Enabler | Done | `untracked` | La validación de create/update users se mueve a `Presentation` con requests dedicadas; `Application` queda con DTOs puros para el flujo de usuarios |
| Academy validation migration | Technical Enabler | Done | `untracked` | La validación de create, update y tenant signup de `Academy` se mueve a `Presentation`; `Application` queda con DTOs puros para esos flujos |
| Category validation migration | Technical Enabler | Done | `untracked` | La validación de create y update de `Category` se mueve a `Presentation`; `Application` queda con DTOs puros para esos flujos |
| Venue validation migration | Technical Enabler | Done | `untracked` | La validación de create y update de `Venue` se mueve a `Presentation`; `Application` queda con DTOs puros para esos flujos |
| Team validation migration | Technical Enabler | Done | `untracked` | La validación de create y update de `Team` se mueve a `Presentation`; `Application` queda con DTOs puros para esos flujos |
| Player validation migration | Technical Enabler | Done | `untracked` | La validación de create, update y asociación de acudiente de `Player` se mueve a `Presentation`; `Application` queda con DTOs puros para esos flujos |
| Guardian validation migration | Technical Enabler | Done | `untracked` | La validación de create de `Guardian` se mueve a `Presentation`; `Application` queda con DTOs puros para ese flujo |
| PaymentConcept validation migration | Technical Enabler | Done | `untracked` | La validación de create y update de `PaymentConcept` se movió a `Presentation`; `Application` quedó con DTOs puros para esos flujos |
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
| TeamAssignment module baseline | Functional / Technical Enabler | Done | `untracked` | `TeamAssignment` materializa la gestión de asignaciones deportivas con principal activo, finalización e historial sobre jugadores y equipos |
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
| Tenant activation idempotency | Functional / Technical Enabler | Done | `untracked` | `GET /api/v1/public/tenants/activate/{token}` ahora devuelve `alreadyActivated` cuando el token sigue vigente y responde `404` problem-details si el token es inválido o expiró |
| Test database guard rail | Technical Enabler | Done | `untracked` | `tests/bootstrap.php` ahora falla si PHPUnit intenta usar una base distinta de `*_test` |
| Onboarding catalog repair command | Technical Enabler | Done | `untracked` | Se agregó `app:category:seed-onboarding` para reponer el catálogo público de onboarding en `playertech` sin tocar migraciones |

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
* `GET /api/v1/auth/me`, `PUT /api/v1/auth/me/name` y el flujo público de restablecimiento de contraseña quedaron implementados para usuarios.
* CRUD de users validado en runtime para contexto plataforma, incluyendo create, update, disable y enable con respuesta JSON estándar.
* Se introdujo una base HTTP común para evitar duplicación de validación y resolución del actor autenticado.
* La base de pruebas ya tiene su primer baseline unitario verde.
* La primera integración de signup de tenant ya corre contra base de datos MySQL de test y valida persistencia real.
* `ROLE_ROOT` opera sin tenant; usuarios tenant requieren `academy_id` y `TenantContext`.
* `Academy` ya expone `GET /api/v1/academy/me` como contexto tenant, `PUT /api/v1/academy/me` para autogestión del tenant y `GET /api/v1/platform/academies` como API de plataforma.
* La API de usuario autenticado quedó separada de la API de academia: `auth/me` expone identidad, `auth/me/name` actualiza sólo el nombre y el reset de contraseña usa endpoints públicos dedicados.
* `ProfileController` ahora reutiliza el flujo público de restablecimiento de contraseña desde `POST /api/v1/auth/me/password-reset/request`, usando el correo del usuario autenticado.
* La nomenclatura funcional de roles quedó alineada a `ROLE_ACADEMY_ADMIN` en docs y Postman; `ROLE_ROOT` sigue siendo el rol de plataforma sin tenant.
* Los endpoints de `Academy` quedaron validados como parte del flujo base tenant/root y siguen protegidos por `TenantContext` y el filtro de persistencia.
* `Academy` ahora usa `AcademyId` como Doctrine custom type y VOs compartidos como embeddables XML, sirviendo como referencia del patrón para los demas modulos.
* Los VOs compartidos ya estan versionados en git y el mapping XML de `Academy` los consume de forma consistente.
* La capa HTTP de `Academy` quedo delgada y delega en CQRS con commands, queries y handlers.
* `Academy` ya responde mediante DTOs de salida por caso de uso, incluyendo contratos anidados para flujos como tenant signup.
* `Academy` ahora expone `registrationSource` para distinguir tenants creados por `signup` y por `platform`, y ese dato también aparece en los listados de academias.
* Los recursos de media se estandarizan como objetos JSON con `path`, `url`, `mime_type`, `size` y `checksum`; `Academy` expone `shield` con ese contrato y `Player` heredará el mismo patrón para `photo`.
* Los adjuntos documentales usan un contrato separado de `Media`, con `fileName` y `source`, para soportes PDF y documentos externos sin mezclarlo con imágenes.
* Existe una guia operativa para construir nuevos modulos sin depender de modelos previos.
* `Academy` queda definido como el modulo de referencia oficial para nuevos contextos: CQRS, XML puro, VOs tipados, soft delete, validacion formal, controllers delgados y separacion root/tenant.
* Se formalizó la regla de comunicación entre módulos: primero contratos de aplicación síncronos, luego eventos internos si aportan claridad, y `Messenger` solo ante una necesidad real de asincronía.
* Los comandos de validacion de pruebas y migraciones quedaron documentados en `specs/11-testing-strategy.md`.
* La separacion de configuracion `local`/`test`/`prod` quedó documentada en `specs/17-environment-guide.md`.
* La validacion principal de suites de integracion y funcionales debe correr sobre la base `test` para simular CI/CD; `local` queda para desarrollo interactivo.
* `Mailpit` queda adoptado como la herramienta base de desarrollo local para validar envios de correo y flujos de activacion.
* `EP-003` queda reorientada para distinguir usuarios de plataforma y usuarios tenant; la creacion del owner/admin inicial del tenant se documenta como historia explicita.
* La creación de tenant por `signup` y por `platform` quedó unificada con trazabilidad explícita de origen en `Academy`.
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
* El endpoint público de activación ahora es idempotente cuando el token sigue disponible: si el usuario vuelve a entrar al enlace, el backend puede marcar `alreadyActivated`; si el token no existe o expiró, responde `404` en Problem Details en lugar de `500`.
* La base de desarrollo `playertech` y la base de pruebas `playertech_test` están separadas por configuración; además, el bootstrap de PHPUnit ahora bloquea el uso accidental de una base que no termine en `_test` y ya no borra datos por defecto.
* Existe deuda en migraciones antiguas: `Version20260704000000` falla al reejecutarse por un `DROP COLUMN logo` sobre una columna ya ausente, así que el `migrate` completo de `dev` queda bloqueado hasta corregir esa versión histórica.
* Para reparar el catálogo público de onboarding en `playertech` existe el comando `app:category:seed-onboarding`, útil cuando la tabla se vacía pero la migración ya figura como ejecutada.
* El contrato público de onboarding pasa a usar un catálogo global de categorías y el signup clonará la categoría elegida dentro de la academia; la implementación sigue pendiente.
* Se pobló el catálogo `onboarding_categories` con el rango `Sub 4` a `Sub 20` como base pública de onboarding para frontend y signup.
* `Player` quedó priorizado como siguiente módulo de negocio sobre `EP-009`, `EP-010` y `EP-012`.
* Se inició el módulo `Membership` como primer slice técnico de `EP-009`, con base de dominio, mapping XML, repositorio, controller y casos de uso de crear/consultar matrícula activa.
* `EP-009` quedó consolidada como módulo funcional completo: matrícula activa, cargos iniciales, historial, suspensión y retiro con cobertura unitaria y documentación HTTP operativa en Postman.
* El bloque financiero fue reordenado: `EP-009` genera cargos iniciales pendientes, `EP-011` administra conceptos de pago, `EP-012` gestiona cargos, pagos, evidencia y deuda, y `EP-013` resume cartera y estado operativo.
* `Membership` ya adopta el patrón de arquitectura esperado: validación en `Presentation`, `MembershipFinder` en `Application` y excepciones de dominio herederas de `Shared`.
* `EP-012` quedó cerrado funcional y técnicamente con `Charge`, `Payment`, `PaymentAllocation`, deuda, historial, evidencia y cancelación; la validación final ya se cubrió sobre `test`.
* `EP-013` quedó materializada como dashboard operativo con jugadores activos, matrículas vigentes, cargos pendientes y resumen de cartera.
* `EP-011` quedó consolidada como módulo funcional completo de conceptos de pago: crear, listar, consultar, actualizar, desactivar y generar automáticamente el `code` desde el `name`, con cobertura unitaria y documentación HTTP operativa en Postman.
* Se documentó el modelo financiero explícito en `specs/18-financial-domain-model.md` para separar `PaymentConcept`, `Charge`, `Payment`, `PaymentAllocation` y sus identificadores de negocio.
* El historial financiero ahora puede consultarse también por `guardianId` y el registro de pagos admite `allocations[]` para distribuir un pago entre varios cargos, manteniendo la conciliación parcial fuera del MVP.
* Se inició la estandarización de listados con paginación uniforme (`page`, `per_page`, `sort`, `direction`) en endpoints visibles por frontend.
* `EP-021` quedó materializada con el desarrollo del módulo `Staff` y `TeamStaffAssignment`, cobertura unitaria base, documentación API y colección Postman para el flujo de staff por equipo.
* `EP-010` quedó materializada con el módulo `TeamAssignment`, que introduce la relación jugador-equipo con historial, principal activo y finalización, y ahora deja explícito que la duplicidad solo se bloquea si existe una asignación activa al mismo equipo.
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
* Se amplió `EP-001` con el perfil básico de academia: `country`, `department`, `city`, `address`, teléfono normalizado y consentimientos legales obligatorios en el signup de tenant.
* La colección Postman quedó actualizada para reflejar el nuevo contrato de `Academy` y `TenantSignup`, incluyendo `country`, `department` y los consentimientos obligatorios.
* El perfil fiscal de academia vive actualmente dentro de `academies` y actúa como fuente operativa para comprobantes de pago; no se modeló todavía una tabla separada ni la integración de facturación electrónica DIAN.
* Se separó el contrato tenant de academia en dos endpoints: `GET /api/v1/academy/context` para el contexto operativo de sesión y `GET /api/v1/academy/me` para el perfil real de la academia, manteniendo intacto el CRUD de `platform/academies` para `ROLE_ROOT`.
* `HU-015` de `EP-001` quedó implementada con `POST /api/v1/platform/academies` para provisionar tenants completos desde la plataforma con academia, owner/admin inicial, correo de bienvenida y primer equipo.
* La colección Postman quedó actualizada con contratos de ejemplo para `POST /api/v1/public/tenants/signup` y `POST /api/v1/platform/academies`.
* La colección Postman se usa como referencia operativa de contrato HTTP para el front mientras no exista Swagger/OpenAPI interactivo.
* Se documentó la futura épica `EP-023` para información tributaria de academias y comprobantes DIAN, separándola del perfil base de `Academy`.
* `EP-023` quedó refinada para cubrir información tributaria, comprobantes operativos descargables y soporte fiscal externo, dejando la capa DIAN como evolución futura.
* `EP-023` ya tiene su primera rebanada técnica implementada: perfil tributario de academia con endpoints de consulta y actualización desde `academy/me` y `platform/academies`.
* `EP-023` añadió el comprobante operativo de pago como recurso HTTP consultable desde `/api/v1/academy/payments/{paymentId}/receipt`, generado a partir del pago y del concepto asociado.
* `EP-023` añadió la vinculación de soportes fiscales en PDF con `POST /api/v1/academy/fiscal-attachments`, manteniendo la emisión fiscal fuera del core.
* `EP-023` quedó como fuente principal para los datos fiscales de la academia usados en comprobantes, mientras `EP-006` complementa la información del acudiente con documento, dirección y correo opcional.
* El comprobante operativo de pago ahora toma los datos fiscales principales de la academia para que la emisión parta de un emisor principal/default coherente.
* El perfil fiscal del MVP se presenta al usuario como `Información fiscal` y se mantiene como un único perfil principal por academia.
* La documentación operativa de `EP-006` ya refleja el alta de acudientes con `documentType`, `documentNumber`, `address` y `relationship` para que el front consuma el contrato actualizado.
* Los comprobantes de pago del MVP deben tomar siempre la academia marcada como principal/default para los datos fiscales del emisor.
* `HU-009` de `EP-007` quedó implementada con `PATCH /api/v1/academy/players/{playerId}/photo` para subir y reemplazar la foto del jugador.
* `HU-009` de `EP-003` quedó implementada: el signup público crea el primer equipo con `category_id` y `team_name`, validando categoría activa y duplicados por academia/categoría.
* El MVP checklist debe mantener como cerradas las historias de media ya implementadas: escudo institucional de `Academy` y foto de `Player`.
* `EP-006` ya expone lectura y creación de acudientes por academia en HTTP, incluyendo el campo `relationship`, y `EP-008` ya cubre la relación jugador-acudiente con alta de acudiente, asociación, cambio de principal, eliminación lógica y vista por jugador.
* El bloque de módulos aún pendiente para el MVP ya no incluye `EP-012`; `EP-008`, `EP-009`, `EP-010`, `EP-011`, `EP-012` y `EP-013` ya se consideran resueltos.
* La capa fiscal formal sigue fuera del MVP y quedó concentrada en `EP-023`.
* Se documentó una auditoría SDD del backend en `docs/architecture/SDD-backend-audit.md`, con diagnóstico de madurez, vacíos de trazabilidad y propuesta incremental de adopción.
* Se adoptó una versión liviana de SDD para trabajo individual: `specs/16-api-reference.md` queda como referencia HTTP operativa principal y `AGENTS.md` incorpora reglas simples de canonicidad y trazabilidad mínima.
* Se consolidó un índice de contratos HTTP en `docs/contracts/api-reference.md` para centralizar la sincronización con frontend y QA sin duplicar la especificación operativa.
* Se formalizó una política SDD escalonada en `docs/architecture/sdd-policy.md` y dos plantillas de cambio en `docs/architecture/change-template-light.md` y `docs/architecture/change-template-full.md` para futuras features.
* Se documentó la evolución del modelo de cobro de `EP-009` en `docs/architecture/EP-009-billing-model-evolution.md`, incluyendo el estado actual, casos de uso reales y los diagramas de flujo actual y objetivo.
* Se redefinió el perfil base de `Player` en `specs/02-domains.md` para separar identidad, atributos deportivos y datos que deben vivir en asignaciones o compras.
* Se documentó un criterio SDD para la evolución del perfil de `Player` en `docs/architecture/player-profile-evolution-sdd.md`, con reglas para decidir qué atributos viven en el aggregate y cuáles deben quedar fuera.
* El perfil base de `Player` incorporó `email` y `phone` como datos de contacto opcionales, sincronizados entre dominio, API, Postman y persistencia.
* Se documentó una estrategia local-first de observabilidad en `specs/19-observability-local.md` para logs estructurados, correlation id y metricas basicas sin depender aun de una plataforma externa.
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
* La validación de alta y actualización de usuarios en `Identity` ya vive en `Presentation`; `Application` conserva DTOs sin dependencias del framework para ese flujo.
* La validación de `Academy` para create, update, signup y autogestión ya vive en `Presentation`; `Application` conserva DTOs sin dependencias del framework para esos flujos.
* La validación de `Category` para create y update ya vive en `Presentation`; `Application` conserva DTOs sin dependencias del framework para esos flujos.
* La validación de `Venue` para create y update ya vive en `Presentation`; `Application` conserva DTOs sin dependencias del framework para esos flujos.
* La validación de `Team` para create y update ya vive en `Presentation`; `Application` conserva DTOs sin dependencias del framework para esos flujos.
* La validación de `Player` para create, update y asociación de acudiente ya vive en `Presentation`; `Application` conserva DTOs sin dependencias del framework para esos flujos.
* La validación de `Guardian` para create ya vive en `Presentation`; `Application` conserva DTOs sin dependencias del framework para ese flujo.
* La validación de `PaymentConcept` para create y update ya vive en `Presentation`; `Application` conserva DTOs sin dependencias del framework para esos flujos.

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
* Cerrar el bloque restante del MVP en este orden: `EP-013`.
* A partir de ese cierre, desarrollar los ADR faltantes sobre decisiones ya estabilizadas.

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
* La consulta de `EP-010` para asignaciones de jugador ahora se orienta a una respuesta compuesta con `team` anidado para evitar lookups adicionales en frontend.
* `EP-021` añadió el flujo unificado de alta de staff con acceso en `POST /api/v1/academy/staff/onboarding`, creando usuario y staff en una sola operación y resolviendo invitación o contraseña inicial.
* `EP-006` funciona como módulo maestro de acudientes con listado, detalle y creación; `EP-008` queda como módulo operativo para relaciones jugador-acudiente y vista de acudientes por jugador.
* `EP-003` ya incorporó el flujo inicial de usuarios administrativos por invitación y activación con correo, como primer slice de la evolución de staff.
* `EP-002` amplió el contrato de sedes para exponer `address` y `phone` opcionales también en el listado, no solo en el detalle.


