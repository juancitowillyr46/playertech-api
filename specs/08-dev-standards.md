# 08-dev-standards.md

# Development Standards

Este documento define los estándares de desarrollo para PlayerTech.

Su objetivo es garantizar:

* Consistencia.
* Mantenibilidad.
* Legibilidad.
* Escalabilidad.
* Calidad técnica.

Todos los módulos deberán seguir estas reglas.

---

# Development Philosophy

## Simplicity First

La solución más simple que cumpla los requisitos de negocio será la opción preferida.

---

## Domain Driven Development

Las decisiones de diseño deberán priorizar el lenguaje del negocio sobre consideraciones técnicas.

---

## Readability Over Cleverness

El código debe ser fácil de leer y entender.

Se evitarán soluciones excesivamente complejas o difíciles de mantener.

---

## Explicit Over Implicit

Las dependencias y comportamientos deberán ser explícitos.

---

## Composition Over Inheritance

Se favorecerá composición antes que herencia.

---

# SOLID Principles

## Single Responsibility Principle

Cada clase debe tener una única razón para cambiar.

Ejemplo:

```text
CreatePlayerHandler
```

No debe:

* Persistir directamente.
* Enviar correos.
* Generar reportes.
* Ejecutar lógica ajena al caso de uso.

---

## Open Closed Principle

El sistema deberá ser extensible sin modificar código existente.

Ejemplo:

```text
StorageProvider
```

Implementaciones:

```text
LocalStorageProvider

S3StorageProvider

CloudflareR2StorageProvider
```

---

## Liskov Substitution Principle

Las implementaciones deberán respetar completamente los contratos definidos.

---

## Interface Segregation Principle

Las interfaces deberán ser pequeñas y específicas.

Evitar:

```php
interface PlayerRepository
{
    // decenas de métodos
}
```

Preferir contratos especializados.

---

## Dependency Inversion Principle

Las capas superiores dependerán de abstracciones.

Nunca de implementaciones concretas.

---

# Clean Code Standards

## Functions

Las funciones deberán:

* Tener una única responsabilidad.
* Ser pequeñas.
* Tener nombres descriptivos.
* Evitar efectos secundarios innecesarios.

---

## Classes

Las clases deberán:

* Tener una responsabilidad clara.
* Ser cohesivas.
* Evitar dependencias excesivas.

---

## Naming

El nombre debe expresar intención.

Evitar:

```text
Manager
Helper
Utils
Processor
```

Preferir:

```text
RegisterPlayerHandler

PlayerRepository

PaymentValidator
```

---

## Comments

Los comentarios deben explicar el "por qué".

No repetir lo que el código ya expresa.

---

## Magic Values

Se prohíben valores mágicos.

Utilizar:

* Constantes
* Enumeraciones
* Value Objects

---

# Module Structure

Todos los módulos deberán seguir:

```text
Module

├── Domain
├── Application
├── Infrastructure
└── Presentation
```

---

# Domain Layer Standards

## Domain Purity

El dominio no debe depender de:

* Symfony
* Doctrine
* MySQL
* Angular
* Frameworks externos

---

## Entities

Las entidades deberán contener comportamiento.

Se evitará el modelo anémico.

Incorrecto:

```php
class Player
{
    private string $name;
}
```

Correcto:

```php
class Player
{
    public function assignGuardian(): void
    {
    }
}
```

---

## Value Objects

Todos los Value Objects deberán:

* Ser inmutables.
* Validar invariantes.
* Implementar igualdad por valor.

---

## Domain Events

Los eventos deberán nombrarse en pasado.

Ejemplos:

```text
PlayerRegistered

MembershipCreated

PaymentRegistered
```

---

# Application Layer Standards

## CQRS

La capa de aplicación debe separar claramente:

* Commands para mutaciones.
* Queries para lecturas.
* Handlers como orquestadores del caso de uso.

Los controladores sólo deben adaptar HTTP hacia el caso de uso.

## Commands

Convención:

```text
CreatePlayerCommand

UpdatePlayerCommand

RegisterPaymentCommand
```

---

## Queries

Convención:

```text
GetPlayerByIdQuery

SearchPlayersQuery

GetMembershipsQuery
```

---

## Handlers

Los handlers representan casos de uso.

Convención:

```text
CreatePlayerHandler

RegisterPaymentHandler
```

---

## DTOs

Los DTOs deberán ser:

* Simples.
* Inmutables.
* Sin lógica de negocio.
* Separados por propósito:
  * `Input DTOs` para requests.
  * `Response DTOs` para respuestas.
  * `View Models` para consultas complejas.

---

# Infrastructure Standards

## Repository Implementations

Convención:

```text
DoctrinePlayerRepository

DoctrineMembershipRepository
```

---

## Storage Providers

Convención:

```text
LocalStorageProvider

S3StorageProvider
```

---

## External Integrations

Toda integración externa deberá estar encapsulada.

Nunca exponer SDKs directamente al dominio.

---

# Presentation Standards

## Controllers

Un controller debe tener una única responsabilidad.

Ejemplo:

```text
CreatePlayerController
```

En PlayerTech, el controller no debe contener lógica de negocio ni orquestación compleja.
Debe delegar en handlers de Application Layer.

---

## Request Validation

La validación deberá realizarse antes de ejecutar el caso de uso.

---

## Response Mapping

Las respuestas deberán construirse mediante DTOs o Response Models.

Nunca exponer entidades directamente.

### Convención de respuestas

* Listados: usar DTOs de item resumido.
* Detalles: usar DTOs de detalle.
* Relaciones: usar DTOs anidados, no entidades Doctrine.
* No devolver arrays crudos desde el controller si ya existe un Response DTO.

---

# Repository Pattern

## Contracts

Los contratos pertenecen al dominio.

Ejemplo:

```text
PlayerRepository
```

---

## Implementations

Las implementaciones pertenecen a infraestructura.

Ejemplo:

```text
DoctrinePlayerRepository
```

---

# Factory Pattern

Utilizar cuando la creación de objetos requiera lógica compleja.

Ejemplos:

```text
PlayerFactory

PaymentFactory
```

---

# Strategy Pattern

Utilizar cuando existan múltiples comportamientos intercambiables.

Ejemplos:

```text
PaymentCalculationStrategy

StorageStrategy
```

---

# Specification Pattern

Permitido para reglas complejas de búsqueda o validación.

---

# Anti Patterns

Evitar:

```text
God Objects

God Services

Massive Controllers

Massive Repositories

Static Helpers

Business Logic In Controllers

Business Logic In Repositories

Business Logic In DTOs
```

---

# Doctrine Standards

## Mapping Strategy

Utilizar exclusivamente:

```text
XML Mapping
```

---

## Attributes

No utilizar:

```php
#[ORM\Entity]
#[ORM\Column]
```

en entidades de dominio.

---

## Custom Doctrine Types

Utilizar tipos personalizados para Value Objects.

Ejemplos:

```text
UuidType

EmailType

MoneyType

PhoneNumberType
```

---

# Database Standards

## Primary Keys

Todas las entidades utilizarán:

```text
UUID
```

---

## Multi-Tenant

Toda entidad de negocio deberá incluir:

```text
academy_id
```

---

## Audit Fields

Obligatorios:

```text
created_at
created_by

updated_at
updated_by

deleted_at
deleted_by
```

---

## Soft Delete

Todas las eliminaciones serán lógicas.

---

# API Standards

## Versioning

Formato:

```text
/api/v1
```

---

## Documentation

Toda API deberá documentarse mediante OpenAPI.

---

## Error Handling

Los errores deberán seguir Problem Details (RFC 9457).

## Local Email Tooling

Para desarrollo local y validacion de flujos de correo se adopta:

```text
Mailpit
```

Uso recomendado:

* Vista local de correos enviados.
* Validacion manual de links de activacion y reseteo.
* Pruebas sin depender de un proveedor externo.

Mailpit debe considerarse la opcion base en contenedores locales mientras no exista una decision distinta por ambiente.

---

# Testing Standards

## Unit Tests

Obligatorios para:

* Value Objects
* Domain Services
* Aggregates
* Domain Rules

---

## Integration Tests

Obligatorios para:

* Repositories
* Doctrine Mappings
* API Endpoints

---

## End-to-End Tests

Deseables para flujos críticos.

Ejemplos:

```text
Player Registration

Membership Creation

Payment Registration
```

---

# Code Review Checklist

Antes de aprobar cambios verificar:

* Cumple SOLID.
* Respeta Clean Architecture.
* No introduce dependencias indebidas.
* Tiene cobertura de pruebas.
* Mantiene alta cohesión.
* Mantiene bajo acoplamiento.
* Respeta convenciones de nombres.
* Respeta multi-tenancy.
* Respeta auditoría.
* Respeta soft delete.

---

# Definition of Done

Una funcionalidad se considera terminada cuando:

* Cumple requisitos funcionales.
* Incluye pruebas.
* Incluye documentación.
* Respeta arquitectura.
* Respeta estándares de desarrollo.
* No introduce deuda técnica significativa.

---

# Reference Module

`Academy` es el modulo de referencia tecnica para los demas modulos del sistema.

Debe servir como ejemplo oficial de:

* CQRS.
* XML Mapping puro.
* Value Objects tipados.
* Soft delete.
* Validacion formal en DTOs.
* Controllers delgados.
* Trazabilidad por commit.
* Separacion clara entre `ROLE_ROOT` y contexto tenant.

Todo nuevo modulo debera partir de este criterio salvo justificacion documentada.


# Aggregate Root Guidelines

Los Aggregate Roots representan los límites de consistencia del dominio.

Se utilizan únicamente cuando es necesario proteger reglas de negocio críticas y garantizar integridad transaccional.

No todas las entidades del sistema deben ser Aggregate Roots.

---

## Current Aggregate Roots

El sistema define los siguientes Aggregate Roots:

### Player
Responsable del ciclo de vida del jugador.

Incluye reglas como:
- Estado del jugador
- Inscripción deportiva
- Asignaciones deportivas (a nivel conceptual)

---

### LegalGuardian
Responsable del ciclo de vida del tutor legal.

Características:
- Puede existir independientemente de los jugadores
- Puede estar asociado a múltiples jugadores
- Representa una entidad reutilizable del dominio

---

### Membership
Representa la permanencia administrativa del jugador en la academia.

Controla:
- Estado de inscripción
- Vigencia de la relación jugador–academia
- Historial de membresías

---

### Payment
Representa el ciclo de vida de los pagos.

Controla:
- Registro de pagos
- Evidencias
- Anulación de pagos
- Relación con membership y tutor responsable

---

## Relationship Entities (NO Aggregates)

Las siguientes entidades NO son Aggregate Roots.

Solo representan relaciones de negocio:

- PlayerGuardian
- TeamAssignment

---

### PlayerGuardian

Representa la relación N:M entre Player y LegalGuardian.

Reglas:
- Un jugador puede tener múltiples tutores legales
- Un tutor puede estar asociado a múltiples jugadores
- Puede existir un tutor principal (is_primary)
- Puede contener flags de responsabilidad (pago, autorización, contacto de emergencia)

No tiene identidad de negocio independiente.

---

### TeamAssignment

Representa la asignación deportiva de un jugador a un equipo.

Reglas:
- Un jugador puede estar en múltiples equipos simultáneamente
- Tiene vigencia temporal (start_date / end_date)
- No representa matrícula ni relación financiera

---

## Aggregate Boundaries

Los Aggregate Roots deben respetar las siguientes reglas:

### 1. Comunicación entre Aggregates

Los agregados NO se referencian por objetos.

Solo pueden comunicarse mediante:

- IDs (UUID)
- Casos de uso (Application Layer)
- Eventos de dominio

---

### 2. Prohibido

No se permite:

- Incluir entidades completas de otro Aggregate dentro de uno
- Mantener referencias directas entre Aggregates
- Compartir estado interno entre Aggregates

---

### 3. Permitido

Se permite:

- Referenciar otros Aggregates por ID
- Sincronización mediante Domain Events
- Orquestación en Application Layer

---

## Design Intent

El objetivo de los Aggregate Roots es:

- Garantizar consistencia del dominio
- Reducir acoplamiento entre módulos
- Evitar modelos anémicos
- Mantener el dominio expresivo pero controlado

---

## Rule of Thumb

Si una entidad:

- Tiene ciclo de vida propio → puede ser Aggregate Root
- Solo representa relación → NO es Aggregate Root
- No tiene reglas de negocio fuertes → NO es Aggregate Root
