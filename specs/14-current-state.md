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
2. Iniciar el desarrollo del módulo `Sports` siguiendo el patrón de referencia de `Academy`.
3. Mantener trazabilidad por commit en cada iteracion.
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
* `ROLE_ROOT` opera sin tenant; usuarios tenant requieren `academy_id` y `TenantContext`.
* `Academy` ya expone `GET /api/v1/academy/me` como contexto tenant, `PUT /api/v1/academy/me` para autogestión del tenant y `GET /api/v1/platform/academies` como API de plataforma.
* `Academy` ahora usa `AcademyId` como Doctrine custom type y VOs compartidos como embeddables XML, sirviendo como referencia del patrón para los demas modulos.
* Los VOs compartidos ya estan versionados en git y el mapping XML de `Academy` los consume de forma consistente.
* La capa HTTP de `Academy` quedo delgada y delega en CQRS con commands, queries y handlers.
* Existe una guia operativa para construir nuevos modulos sin depender de modelos previos.
* Las validaciones de negocio de `Academy` devuelven Problem Details JSON; el caso de duplicado de correo se resuelve con excepcion de dominio y respuesta `409`.
* `Academy` incorpora soft delete con `deleted_at` y `deleted_by`, y Doctrine ya tiene un filtro global para excluir entidades borradas lógicamente.
* Se documentó una épica nueva para onboarding de tenant (`EP-014`) sin alterar el flujo de creación de tenants por `ROLE_ROOT`.
* El onboarding tenant ya tiene implementación base: signup público, correo de activación y endpoint de activación.
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
- [ ] **Doctrine Tenant Filter**: Filtro SQL automático que aplique `WHERE academy_id = X` en todas las queries de negocio.

### 2. Security & Routing Separation
- [x] **Platform Firewall/Access**: Bloquear rutas `/api/v1/platform/*` solo para `ROLE_ROOT`.
- [x] **Tenant Access Enforcement**: Validar que si el usuario no es Root, el `TenantContext` *deba* estar presente; de lo contrario, devolver 403.

### 3. API Reliability
- [ ] **ProblemDetails (RFC 9457)**: Subscriber para capturar excepciones y devolver el formato estándar de errores.
- [ ] **Validation Mapping**: Convertir errores de `symfony/validator` al formato `ProblemDetails`.

### 4. Audit & Persistence
- [ ] **AuditSubscriber**: Automatizar el llenado de `created_by` y `updated_by` usando el usuario del Token.
- [ ] **SoftDelete Filter**: Asegurar que las consultas excluyan registros con `deleted_at` por defecto.

### 5. Validation
- [ ] **Test de Aislamiento**: Prueba técnica que confirme que un usuario de la Academia A no puede ver datos de la Academia B aunque conozca el ID.

---

## Pending Features (Post-Foundation)

* Completar colecciones `.http` con ejemplos de error y éxito.
* Flujo de creación de Academia (exclusivo para Root).
* Formalizar el onboarding de tenant como siguiente bloque funcional tras `EP-001`.
