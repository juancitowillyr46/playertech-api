# 11-testing-strategy.md

# Testing Strategy

La estrategia de pruebas debe proteger la base técnica antes de expandir el negocio.

---

# Testing Goals

* Validar reglas de dominio.
* Verificar seguridad y tenant isolation.
* Asegurar contratos HTTP.
* Evitar regresiones al agregar módulos.

---

# Test Layers

## Unit Tests

Aplican a:

* Value Objects
* Entities
* Aggregates
* Domain invariants
* Application rules sin infraestructura

## Integration Tests

Aplican a:

* Repositories
* Doctrine mappings
* Tenant filters
* Database constraints

## Functional Tests

Aplican a:

* Login
* Autorización
* Endpoints de API
* Respuestas y contratos HTTP

## Contract Tests

Aplican a:

* Formato de respuesta
* ProblemDetails
* Paginación
* Filtros permitidos

---

# Minimum Foundation Coverage

Antes de empezar HUs, la base técnica debe cubrir al menos:

* Autenticación JWT.
* Aislamiento por tenant.
* Acceso por rol.
* Auditoría básica.
* Soft delete.
* Modelo de respuesta de API.
* Persistencia de entidades base.

---

# Test Priorities

## Priority 1

* Security
* Tenant resolution
* Database mappings

## Priority 2

* API contracts
* Domain invariants
* Core repositories

## Priority 3

* Módulos de negocio adicionales
* Casos borde
* Reglas de consulta avanzadas

---

# Test Data Strategy

* Usar fixtures mínimas y explícitas.
* Separar datos de plataforma y datos de tenant.
* Evitar fixtures enormes o difíciles de mantener.

---

# Quality Criteria

La base técnica se considera bien protegida cuando:

* Las pruebas pasan sin depender del orden de ejecución.
* Las pruebas de tenant evitan fugas de datos.
* Las validaciones críticas están cubiertas.
* Los cambios de seguridad rompen tests si se alteran reglas.

