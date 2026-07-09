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

## Test on Test Database

La validación prioritaria de integración y funcionalidad debe ejecutarse contra la base de datos `test`, no contra la base `local`.

Esto simula el comportamiento esperado en CI/CD:

* construir el esquema de prueba
* correr integración sobre MySQL aislado
* ejecutar funcionales HTTP sobre el mismo entorno de test
* detectar fallas de mapping, constraints y aislamiento por tenant antes de promover cambios

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

# Current Test Baseline

La base inicial de pruebas debe comenzar por:

* Unit tests de Value Objects y reglas puras.
* Unit tests de policies de dominio.
* Functional tests del contrato HTTP cuando exista infraestructura de testing web estable.

Para PlayerTech, la primera tanda valida:

* `AcademyId`
* `AccountUser`
* `UserAdministrationPolicy`

Y como primera integración real de infraestructura:

* `RegisterTenantHandler` contra base de datos MySQL de test y bus de mensajes desacoplado.

Esta capa sirve como red de seguridad antes de incorporar pruebas funcionales de API.

En ciclos de desarrollo y en CI/CD, la secuencia recomendada es:

1. unit tests.
2. integration tests contra `test`.
3. functional tests contra `test`.
4. contract checks de API y Postman.

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

---

# Test Commands

## Local / Docker

```bash
docker exec docker-app-1 bash -lc 'cd /var/www/html && vendor/bin/phpunit --testdox'
```

## Test puntual

```bash
docker exec docker-app-1 bash -lc 'cd /var/www/html && vendor/bin/phpunit --filter RegisterTenantHandlerTest --testdox'
```

## Migraciones de entorno de desarrollo

```bash
docker exec docker-app-1 bash -lc 'cd /var/www/html && php bin/console doctrine:migrations:migrate --no-interaction'
```

## Validación técnica de mapping

```bash
docker exec docker-app-1 bash -lc 'cd /var/www/html && php bin/console doctrine:mapping:info'
```

## Entornos

La separación de `local`, `test` y `prod` está documentada en `specs/17-environment-guide.md`.

