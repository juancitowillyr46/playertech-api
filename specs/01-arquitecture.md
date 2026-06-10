# 01-arquitecture.md

# Architecture Overview

Este documento define la arquitectura base de PlayerTech para el MVP: un monolito modular, tenant-aware, con capas claramente separadas y preparado para crecer sin reescrituras costosas.

---

# Architectural Principles

* Monolito modular antes que microservicios.
* Separación explícita entre dominio, aplicación, infraestructura y presentación.
* Multi-tenant por `academy_id`.
* Autenticación stateless con JWT.
* Seguridad y aislamiento aplicados en todas las capas.
* Evolución incremental a partir de una base estable.

---

# Architecture Style

## Monolithic Modular Architecture

La aplicación se construirá como una sola unidad desplegable, pero internamente organizada por módulos funcionales.

### Benefits

* Menor complejidad operativa.
* Más velocidad para el MVP.
* Mejor cohesión funcional.
* Menor riesgo de acoplamiento entre equipos futuros.

---

# Domain Contexts

Los contextos de dominio iniciales son:

* Identity & Access
* Academy Management
* Sports Management
* Membership Management
* Financial Management

Cada contexto tendrá límites explícitos y no deberá depender de detalles internos de otros módulos.

---

# Layer Model

## Presentation

Responsable de:

* Controllers
* Request/Response DTOs
* Serialization
* HTTP validation entrypoints

## Application

Responsable de:

* Use cases
* Commands
* Queries
* Handlers
* Orquestación de reglas de negocio

## Domain

Responsable de:

* Aggregates
* Entities
* Value Objects
* Domain invariants
* Domain events si se necesitan

## Infrastructure

Responsable de:

* Doctrine mappings
* Repositories
* Security adapters
* External integrations
* File storage adapters

## Shared

Responsable de:

* Base entities
* Tenant context
* Audit support
* Common exceptions
* Common result types

---

# Module Structure

Cada módulo seguirá una estructura consistente:

```text
Modules/<ModuleName>/
├── Domain/
├── Application/
├── Infrastructure/
└── Presentation/
```

Los módulos iniciales recomendados son:

* Academy
* Auth
* Users
* Sports
* Membership
* Payments

---

# Bounded Context Rules

* Un módulo no debe leer directamente la infraestructura privada de otro módulo.
* La comunicación entre módulos debe realizarse mediante servicios de aplicación, contratos internos o eventos internos.
* Los agregados deben proteger sus invariantes desde el dominio.
* Las consultas de un módulo no deben saltarse el contexto de tenant.

---

# Cross-Cutting Concerns

## Security

* JWT stateless.
* RBAC simple.
* Validación de roles antes de entrar a la aplicación.

## Tenant Isolation

* `academy_id` obligatorio en entidades tenant-aware.
* Tenant resuelto desde JWT.
* Filtro Doctrine obligatorio para el contexto activo.

## Auditing

* `created_at`
* `created_by`
* `updated_at`
* `updated_by`
* `deleted_at`
* `deleted_by`

## Soft Delete

* No se permiten eliminaciones físicas para entidades de negocio.
* Las consultas operativas excluyen eliminados.

## Error Handling

* Respuestas consistentes.
* Errores de validación y negocio separados.
* ProblemDetails para errores HTTP.

---

# Tenant Strategy

## Tenant Resolution

El tenant se obtiene desde el JWT y se carga en un `TenantContext`.

## Enforcement Layers

1. Security layer autentica y valida el token.
2. Application layer usa el contexto de tenant.
3. Persistence layer aplica filtros por `academy_id`.

## Platform Access

`ROLE_ROOT` opera en contexto de plataforma y puede acceder a operaciones globales que no pertenecen a una academia específica.

---

# Identity Model

El modelo de identidad se basa en:

* User
* Role
* Permission
* UserRole
* RolePermission

Aunque el MVP use solo dos roles iniciales, la arquitectura debe soportar crecimiento futuro sin rediseño.

---

# Communication Pattern

La comunicación entre módulos será interna y síncrona en el MVP.

## Recommended Flow

* Presentation recibe el request.
* Application resuelve el caso de uso.
* Domain valida invariantes.
* Infrastructure persiste y consulta.

## Allowed Interactions

* Servicios de aplicación de otros módulos.
* Eventos internos cuando aporten claridad.
* Contratos compartidos en `Shared`.

## Not Allowed

* Dependencias directas entre capas que rompan el aislamiento.
* Acceso cruzado a tablas sin pasar por la capa de aplicación o repositorio del módulo dueño.

---

# Symfony Integration

* `Symfony Security` para autenticación y autorización.
* `Doctrine ORM` para persistencia.
* `Doctrine Filters` para aislamiento por tenant.
* `Serializer` para contratos HTTP.
* `Validator` para validación de entrada.
* `Messenger` solo si una necesidad real del MVP lo justifica.

---

# Evolution Strategy

La arquitectura debe permitir incorporar nuevos módulos sin romper los existentes.

Futuras extensiones posibles:

* Attendance
* Coaches
* Tournaments
* Billing
* Notifications
* Analytics

Estas capacidades no deben contaminar la base inicial del MVP.
