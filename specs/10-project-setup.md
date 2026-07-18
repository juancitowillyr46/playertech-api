# Project Setup

Este documento define la base tecnica minima para levantar PlayerTech como un monolito modular en Symfony, antes de implementar cualquier epica o historia de usuario.

---

# Objective

Proveer un entorno reproducible, solido y escalable para desarrollar el MVP de PlayerTech sin mezclar decisiones de negocio con decisiones de plataforma.

---

# Entry Point

El punto de entrada del repositorio es `README.md`.

Ese archivo debe resumir:

* Que es PlayerTech.
* Requisitos minimos.
* Estructura del repositorio.
* Como levantar la base tecnica.
* Que documentacion tecnica debe leerse primero.

La referencia documental mínima para trabajar en el proyecto es:

1. `README.md`
2. `specs/14-current-state.md`
3. `specs/12-execution-order.md`
4. `specs/16-api-reference.md`
5. `docs/contracts/api-reference.md`
6. `docs/architecture/*`

---

# Architectural Decision

## Style

La aplicacion sera un **monolito modular**.

### Why

* Reduce complejidad operativa en el MVP.
* Permite evolucion por modulos sin dividir la aplicacion en microservicios.
* Facilita comunicacion interna entre dominios.
* Mantiene una sola base de despliegue, autenticacion y persistencia.

---

## Module Boundaries

Cada modulo representara un contexto funcional y tendra sus propias capas internas.

Estructura recomendada:

```text
app/
└── src/
    ├── Shared/
    └── Modules/
        ├── Academy/
        ├── Identity/
        ├── Sports/
        ├── Membership/
        └── Payments/
```

El modulo Identity concentra autenticacion, usuarios, roles, JWT y adaptadores tecnicos de seguridad.

La entidad tecnica de autenticacion (AccountUser) puede estar acoplada al framework por decision pragmatica, usando atributos Doctrine y propiedades primitivas para acelerar la foundation sin convertirla en referencia obligatoria para otros dominios.

Cada modulo seguira esta organizacion:

```text
Module/
├── Domain/
├── Application/
├── Infrastructure/
└── Presentation/
```

---

# Technology Stack

## Backend

* PHP 8.4
* Symfony 7.4

## Database

* MySQL 8+

## API

* REST API
* OpenAPI 3
* ProblemDetails para errores

## Authentication

* Symfony Security
* JWT

## Persistence

* Doctrine ORM
* Doctrine Migrations
* XML Mapping

## Testing

* PHPUnit

---

# Execution Strategy

Toda ejecucion del proyecto debe realizarse dentro de contenedores Docker.

La secuencia minima esperada es:

```bash
docker compose -f docker/docker-compose.yml up -d --build
```

Luego, dentro del entorno de aplicacion:

```bash
cd app
composer install
```

Y finalmente validar Symfony:

```bash
php bin/console
```

No se debe asumir ejecucion local fuera de contenedores para el flujo normal de desarrollo.

---

# Local Environment

Todos los servicios iniciales se ejecutaran mediante Docker.

Servicios minimos:

```text
app
mysql
```

Redis, colas u otros servicios solo se incorporaran cuando exista una necesidad real del MVP o una historia que lo justifique.

---

# Docker Architecture

## App Container

Responsabilidades:

* PHP 8.4
* Apache
* Composer
* Extensiones necesarias para Symfony y Doctrine

Extensiones minimas recomendadas:

```text
pdo
pdo_mysql
intl
zip
xml
```

## Database Container

Responsabilidades:

* MySQL 8+
* Persistencia local mediante volumen Docker

---

# Directory Structure

Estructura base del repositorio:

```text
playtech/
├── app/
├── docs/
├── specs/
├── docker/
└── README.md
```

## app

Contiene el proyecto Symfony.

## docs

Contiene documentacion de negocio, dominio, backlog y decisiones funcionales.

## specs

Contiene la documentacion tecnica y arquitectonica que gobierna el arranque y la evolucion del MVP.

## docker

Contiene configuracion de infraestructura local.

## README.md

Documento de entrada para cualquier colaborador nuevo.

---

# Required Packages

## Base

```bash
composer create-project symfony/skeleton:^7.4 .
composer require webapp
composer require orm
composer require doctrine/doctrine-migrations-bundle
composer require serializer
composer require validator
composer require security
composer require lexik/jwt-authentication-bundle
composer require messenger
composer require nelmio/api-doc-bundle
composer require symfony/uid
composer require --dev phpunit/phpunit
```

---

# Persistence Strategy

## ORM

Doctrine ORM.

## Mapping

XML Mapping.

El dominio de negocio no debera depender de atributos Doctrine. Excepciones tecnicas acotadas en `Identity`, como `AccountUser`, pueden usar atributos Doctrine cuando simplifiquen la foundation sin comprometer trazabilidad.

## Identifiers

Todas las entidades utilizaran UUID como identificador principal.

```sql
BINARY(16)
```

---

# Multi-Tenant Setup

## Tenant Model

* Shared Database
* Shared Schema
* `academy_id` en todas las entidades de negocio

## Tenant Resolution

El tenant se resolvera desde el JWT y se exponera en un `TenantContext` disponible para la capa de aplicacion.

## Platform Context

Las operaciones de plataforma pertenecientes a `ROLE_ROOT` trabajan sin tenant de academia.

Reglas:

* `ROLE_ROOT` representa administracion global de la plataforma SaaS.
* Los usuarios `ROLE_ROOT` viven en la misma tabla `users`.
* Los usuarios `ROLE_ROOT` deben tener `academy_id = null`.
* Los usuarios `ROLE_ROOT` no consumen endpoints de negocio tenant-scoped como si pertenecieran a una academia.
* Los usuarios `ROLE_ROOT` pueden ejecutar operaciones de plataforma como crear academias, crear administradores tenant y consultar estado operativo global.

## Tenant Identity Context

Los usuarios de academia trabajan dentro de un tenant.

Reglas:

* Todo usuario tenant debe tener `academy_id` informado.
* Los roles tenant no deben operar con `academy_id = null`.
* El JWT debe incluir la identidad del usuario, sus roles y el `academy_id` cuando aplique.
* Los endpoints de negocio deben exigir tenant resuelto antes de ejecutar casos de uso.
* Las consultas de negocio deben aislar datos por `academy_id`.

Esta separacion permite mantener una sola tabla tecnica de usuarios sin mezclar permisos de plataforma con permisos de academia.

---

# Foundation First

El arranque del proyecto debe construirse en este orden:

1. Base de Symfony.
2. Seguridad.
3. Contexto de tenant.
4. Persistencia y auditoria.
5. Contratos base de API.
6. Modulos fundacionales.
7. Luego, historias de usuario especificas.

---

# Environment Variables

Archivo:

```text
.env.local
```

Variables iniciales esperadas:

```env
DATABASE_URL="mysql://playertech:playertech@mysql:3306/playertech"
JWT_PASSPHRASE=change_this_password
```

---

# Verification Checklist

La base tecnica se considera lista cuando:

* Symfony arranca correctamente.
* Doctrine conecta con MySQL.
* Las migraciones se ejecutan.
* JWT genera y valida tokens.
* La API responde bajo `/api/v1`.
* El tenant se resuelve desde JWT.
* Las consultas quedan aisladas por `academy_id`.
* PHPUnit ejecuta pruebas minimas de la base.

---

# Non Goals

No se implementaran todavia:

* Microservicios.
* Redis.
* RabbitMQ.
* MinIO.
* Integraciones externas.
* Logica completa de HUs antes de cerrar la base tecnica.
