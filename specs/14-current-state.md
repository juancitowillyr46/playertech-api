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
| Auth JWT | Functional | Pending | - | Proximo paso de implementacion |
| Tenant context | Non-Functional / Architectural Constraint | Pending | - | Requerido para aislamiento multi-tenant |

---

# Commit References

* `7c3de8e` - `chore: bootstrap PlayerTech API foundation`
* `b40e311` - `docs: improve project README`
* `87f6f9b` - `feat(identity): align technical foundation and docs`
* `fc14bd8` - `feat(identity): add tenant context foundation`

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

1. Validar autenticacion real con usuario `ROLE_ROOT`.
2. Crear flujo minimo para usuario tenant con `academy_id`.
3. Implementar `TenantContext`.
4. Aplicar reglas de acceso plataforma vs tenant.
5. Implementar filtro tenant en consultas de negocio.
6. Mantener trazabilidad por commit en cada iteracion.

---

# Working Rule

Cada cambio importante debera dejar trazabilidad en este documento o en el orden de ejecucion, con referencia al commit correspondiente y clasificacion funcional o no funcional.

---

# Current Iteration Notes

* Auth/JWT reordenado a `Modules/Identity`.
* El login no usa AuthController; se ejecuta desde el firewall `json_login`.
* `AccountUser` queda como entidad tecnica acoplada al framework por pragmatismo.
* El almacenamiento UUID ya esta normalizado como string legible en la tabla `users`.
* Pendiente validar en runtime el login y el endpoint `/auth/me`.
* `ROLE_ROOT` opera sin tenant; usuarios tenant requieren `academy_id` y `TenantContext`.
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
- [ ] **TenantContext**: Objeto inmutable/servicio que contenga el `academy_id` activo.
- [ ] **JWT Custom Claims**: Incluir `academy_id` en el payload generado para usuarios no-root.
- [ ] **TenantResolver**: Listener que capture el JWT, extraiga el `academy_id` e hidrate el `TenantContext`.
- [ ] **Doctrine Tenant Filter**: Filtro SQL automático que aplique `WHERE academy_id = X` en todas las queries de negocio.

### 2. Security & Routing Separation
- [ ] **Platform Firewall/Access**: Bloquear rutas `/api/v1/platform/*` solo para `ROLE_ROOT`.
- [ ] **Tenant Access Enforcement**: Validar que si el usuario no es Root, el `TenantContext` *deba* estar presente; de lo contrario, devolver 403.

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
