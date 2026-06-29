# ADR-002: Introducir una jerarquía compartida de excepciones de dominio

- Estado: Aceptado
- Fecha: 2026-06-26
- Decisor: Equipo de Arquitectura
- Impacto: Shared, Todos los módulos

---

# Contexto

Actualmente cada módulo define sus propias excepciones de dominio, por ejemplo:

- AcademyAlreadyExistsException
- VenueAlreadyExistsException
- CategoryAlreadyExistsException
- UserAlreadyExistsException

Antes, el `ProblemDetailsExceptionSubscriber` debía conocer explícitamente cada una de estas excepciones para traducirlas a respuestas HTTP.

Ese enfoque incrementaba el acoplamiento entre la infraestructura compartida (`Shared`) y los módulos del dominio.

---

# Decisión

Introducir una jerarquía de excepciones compartidas dentro del módulo `Shared`.

Propuesta:

```text
Shared
└── Domain
    └── Exception
        ├── BusinessRuleException
        ├── ConflictException
        ├── NotFoundException
        ├── ForbiddenException
        ├── UnauthorizedException
        └── ValidationException
```

Las excepciones específicas de cada módulo heredan de una excepción compartida.

Ejemplos:

```php
final class CategoryAlreadyExistsException extends ConflictException
{
}
```

```php
final class VenueNotFoundException extends NotFoundException
{
}
```

```php
final class UserTenantScopeViolationException extends ForbiddenException
{
}
```

---

# Consecuencias

El `ProblemDetailsExceptionSubscriber` ya no depende de excepciones concretas por módulo para casos comunes.

Traduce por tipo base:

- `ConflictException` -> `409`
- `NotFoundException` -> `404`
- `ForbiddenException` -> `403`
- `UnauthorizedException` -> `401`
- `ValidationException` -> `422`

La infraestructura permanece estable aunque se agreguen nuevos módulos al sistema.

---

# Beneficios

- Reduce el acoplamiento entre Shared y los módulos.
- Cumple mejor el principio Open/Closed.
- Simplifica el `ProblemDetailsExceptionSubscriber`.
- Facilita la incorporación de nuevos módulos.
- Centraliza la semántica de errores del dominio.

---

# Riesgos

Requiere refactor de excepciones existentes para heredar de las nuevas clases base.

---

# Estado actual

La jerarquía compartida ya quedó aplicada en la base técnica.

Las excepciones de conflicto, not found y forbidden ya heredan desde `Shared`.

El `ProblemDetailsExceptionSubscriber` traduce por tipo base en lugar de conocer excepciones concretas por módulo.

---

# Prioridad

Media.

Se recomienda mantener esta convención para cualquier nuevo módulo funcional.
