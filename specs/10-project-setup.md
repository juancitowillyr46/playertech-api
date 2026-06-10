# 10-project-setup.md

# Project Setup

Este documento define la base técnica mínima para levantar PlayerTech como un monolito modular en Symfony, antes de implementar cualquier épica o historia de usuario.

---

# Objective

Proveer un entorno reproducible, sólido y escalable para desarrollar el MVP de PlayerTech sin mezclar decisiones de negocio con decisiones de plataforma.

---

# Entry Point

El punto de entrada del repositorio es `README.md`.

Ese archivo debe resumir:

* Qué es PlayerTech.
* Requisitos mínimos.
* Estructura del repositorio.
* Cómo levantar la base técnica.
* Qué documentación técnica debe leerse primero.

---

# Architectural Decision

## Style

La aplicación será un **monolito modular**.

### Why

* Reduce complejidad operativa en el MVP.
* Permite evolución por módulos sin dividir la aplicación en microservicios.
* Facilita comunicación interna entre dominios.
* Mantiene una sola base de despliegue, autenticación y persistencia.

---

## Module Boundaries

Cada módulo representará un contexto funcional y tendrá sus propias capas internas.

Estructura recomendada:

```text
app/
└── src/
    ├── Shared/
    └── Modules/
        ├── Academy/
        ├── Auth/
        ├── Users/
        ├── Sports/
        ├── Membership/
        └── Payments/
```

Cada módulo seguirá esta organización:

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

Toda ejecución del proyecto debe realizarse dentro de contenedores Docker.

La secuencia mínima esperada es:

```bash
docker compose -f docker/docker-compose.yml up -d --build
```

Luego, dentro del entorno de aplicación:

```bash
cd app
composer install
```

Y finalmente validar Symfony:

```bash
php bin/console
```

No se debe asumir ejecución local fuera de contenedores para el flujo normal de desarrollo.

---

# Local Environment

Todos los servicios iniciales se ejecutarán mediante Docker.

Servicios mínimos:

```text
app
mysql
```

Redis, colas u otros servicios solo se incorporarán cuando exista una necesidad real del MVP o una historia que lo justifique.

---

# Docker Architecture

## App Container

Responsabilidades:

* PHP 8.4
* Apache
* Composer
* Extensiones necesarias para Symfony y Doctrine

Extensiones mínimas recomendadas:

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
├── http/
└── README.md
```

## app

Contiene el proyecto Symfony.

## docs

Contiene documentación de negocio, dominio, backlog y decisiones funcionales.

## specs

Contiene la documentación técnica y arquitectónica que gobierna el arranque y la evolución del MVP.

## docker

Contiene configuración de infraestructura local.

## http

Contiene colecciones HTTP para probar la API.

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

El dominio no deberá depender de atributos Doctrine.

## Identifiers

Todas las entidades utilizarán UUID como identificador principal.

Representación física en MySQL:

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

El tenant se resolverá desde el JWT y se expondrá en un `TenantContext` disponible para la capa de aplicación.

## Platform Context

Las operaciones de plataforma pertenecientes a `ROLE_ROOT` podrán trabajar sin tenant de academia o con un contexto de plataforma separado, según lo defina la capa de seguridad.

---

# Foundation First

El arranque del proyecto debe construirse en este orden:

1. Base de Symfony.
2. Seguridad.
3. Contexto de tenant.
4. Persistencia y auditoría.
5. Contratos base de API.
6. Módulos fundacionales.
7. Luego, historias de usuario específicas.

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

La base técnica se considera lista cuando:

* Symfony arranca correctamente.
* Doctrine conecta con MySQL.
* Las migraciones se ejecutan.
* JWT genera y valida tokens.
* La API responde bajo `/api/v1`.
* El tenant se resuelve desde JWT.
* Las consultas quedan aisladas por `academy_id`.
* PHPUnit ejecuta pruebas mínimas de la base.

---

# Non Goals

No se implementarán todavía:

* Microservicios.
* Redis.
* RabbitMQ.
* MinIO.
* Integraciones externas.
* Lógica completa de HUs antes de cerrar la base técnica.
