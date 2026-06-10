# 09-roadmap.md

# Product Roadmap — PlayerTech MVP

Este documento define la estrategia de construcción del MVP de PlayerTech bajo un enfoque incremental, modular y alineado a Clean Architecture + CQRS ligero.

El objetivo es entregar valor temprano sin comprometer escalabilidad futura.

---

# Phase 0 — Foundation Setup

## Objective

Preparar la base técnica del sistema.

---

## Tasks

### Backend Setup

* Proyecto Symfony 7.4 + PHP 8.4
* Configuración inicial de Clean Architecture
* Estructura de módulos base:

  * Shared
  * Users
  * Academies

---

### Infrastructure

* Docker Compose (php-fpm, nginx, mysql)
* Configuración de entorno local
* Configuración de variables de entorno

---

### Database

* Setup MySQL 8+
* Base migraciones iniciales
* Auditoría base (created_at, updated_at, deleted_at)

---

### Security Foundation

* Symfony Security
* JWT authentication base
* Login endpoint inicial

---

# Phase 1 — Core Multi-Tenant + Users

## Objective

Implementar el núcleo del SaaS Multi-Tenant.

---

## Features

### Authentication

* Login con email/password
* Generación JWT
* Contexto academy_id desde token

---

### Users Module

* Creación de usuarios
* Roles base:

  * ROLE_ROOT
  * ROLE_ACADEMIC_ADMIN

---

### Academies Module

* Registro de academias
* Asociación de usuarios a academias
* Aislamiento multi-tenant funcional

---

## Deliverable

Sistema capaz de:

* Autenticar usuarios
* Aislar datos por academia
* Gestionar usuarios básicos

---

# Phase 2 — Domain Core (Academy Operations)

## Objective

Construir el núcleo operativo de academias.

---

## Features

### Players Module

* Registro de jugadores
* Consulta de jugadores
* Estado del jugador (ACTIVE/INACTIVE)

---

### Legal Guardians Module

* Registro de tutores legales
* Asociación N:M con jugadores (PlayerGuardian)

---

### Categories Module

* Creación de categorías por edad
* Validación min/max age

---

### Teams Module

* Creación de equipos
* Asociación con categorías

---

### Venues Module

* Gestión de sedes

---

## Deliverable

Sistema capaz de:

* Registrar jugadores
* Relacionar tutores
* Crear estructura deportiva básica

---

# Phase 3 — Membership & Financial Core

## Objective

Introducir control administrativo y financiero.

---

## Features

### Memberships

* Inscripción de jugadores
* Control de vigencia
* Historial de membresías

---

### Payments

* Registro de pagos
* Asociación a:

  * Membership
  * Player
  * LegalGuardian
* Evidencias de pago (upload)

---

### Payment Concepts

* REGISTRATION
* MONTHLY_FEE
* OTHER

---

## Deliverable

Sistema capaz de:

* Gestionar inscripciones
* Registrar pagos
* Control financiero básico

---

# Phase 4 — Sports Operations Layer

## Objective

Extender capacidades deportivas operativas.

---

## Features

### Team Assignments

* Asignación de jugadores a equipos
* Soporte multi-equipo simultáneo
* Fechas de vigencia

---

### Domain Rules Enforcement

* Validación de categorías vs jugadores
* Reglas de asignación deportiva

---

## Deliverable

Sistema capaz de:

* Gestionar estructura deportiva real
* Asignar jugadores dinámicamente

---

# Phase 5 — Events & Observability

## Objective

Activar arquitectura orientada a eventos.

---

## Features

### Domain Events

* PlayerRegistered
* MembershipCreated
* PaymentRegistered

---

### Symfony Messenger

* Ejecución sync (MVP)
* Handlers básicos

---

### Logging

* Monolog integration
* security.log
* application.log

---

## Deliverable

Sistema observable y extensible.

---

# Phase 6 — API Hardening & UX Readiness

## Objective

Preparar sistema para consumo frontend real.

---

## Features

### API Stability

* Estabilización endpoints v1
* Consistencia ProblemDetails
* Paginación estándar
* Filtros básicos

---

### Frontend (Angular 20+)

* Login
* Listado de jugadores
* Registro de jugadores
* Gestión básica de academias

---

## Deliverable

Sistema usable end-to-end.

---

# Phase 7 — MVP Release

## Objective

Liberar primera versión funcional.

---

## Features

* Gestión de academias
* Gestión de usuarios
* Gestión de jugadores
* Gestión de tutores
* Matrículas (membership)
* Pagos con evidencia
* Asignación a equipos

---

## Success Criteria

* Multi-tenant funcionando correctamente
* Datos aislados por academia
* Flujo completo jugador → matrícula → pago
* API estable v1
* Base preparada para evolución

---

# Future Roadmap (Post MVP)

* Coaches module
* Attendance system
* Tournament registrations
* Referee fees
* Notifications system
* Parent portal
* Mobile app
* Payment automation
* Reporting & analytics

---

# Delivery Strategy

## Principles

* Incremental delivery
* Vertical slices (feature complete)
* No over-engineering
* Early validation with real users

---

## Architecture Constraint

Todos los módulos deben respetar:

* Clean Architecture
* CQRS ligero
* Domain-first design
* Multi-tenant isolation
* Event-driven readiness
