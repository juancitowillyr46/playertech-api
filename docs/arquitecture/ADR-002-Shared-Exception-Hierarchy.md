# ADR-008: Introducir una jerarquía compartida de excepciones de dominio

- Estado: Propuesto
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

El `ProblemDetailsExceptionSubscriber` debe conocer explícitamente cada una de estas excepciones para traducirlas a respuestas HTTP.

Ejemplo:

```php
if (
    $throwable instanceof AcademyAlreadyExistsException
    || $throwable instanceof VenueAlreadyExistsException
    || $throwable instanceof CategoryAlreadyExistsException
) {
    ...
}
```

Este enfoque incrementa el acoplamiento entre la infraestructura compartida (`Shared`) y los módulos del dominio.

Cada vez que se incorpora un nuevo módulo (Players, Teams, Coaches, etc.) será necesario modificar el subscriber global.

Esto viola parcialmente el principio **Open/Closed (OCP)**, ya que la infraestructura debe modificarse para soportar nuevas excepciones funcionales.

---

# Decisión

Introducir una jerarquía de excepciones compartidas dentro del módulo `Shared`.

Propuesta inicial:

```
Shared
└── Domain
    └── Exception
        ├── DomainException
        ├── ConflictException
        ├── NotFoundException
        ├── ValidationException
        ├── ForbiddenException
        ├── UnauthorizedException
        └── BusinessRuleException
```

Las excepciones específicas de cada módulo heredarán de una excepción compartida.

Ejemplo:

```php
final class CategoryAlreadyExistsException extends ConflictException
{
}
```

```php
final class VenueAlreadyExistsException extends ConflictException
{
}
```

```php
final class AcademyAlreadyExistsException extends ConflictException
{
}
```

---

# Consecuencias

El `ProblemDetailsExceptionSubscriber` dejará de depender de excepciones específicas de cada módulo.

Pasará de:

```php
if (
    $throwable instanceof AcademyAlreadyExistsException
    || $throwable instanceof VenueAlreadyExistsException
    || $throwable instanceof CategoryAlreadyExistsException
)
```

a simplemente:

```php
if ($throwable instanceof ConflictException) {
    ...
}
```

Lo mismo aplicará para:

- NotFoundException
- ForbiddenException
- UnauthorizedException
- BusinessRuleException

La infraestructura permanecerá estable aunque se agreguen nuevos módulos al sistema.

---

# Beneficios

- Reduce el acoplamiento entre Shared y los módulos.
- Cumple mejor el principio Open/Closed.
- Simplifica el `ProblemDetailsExceptionSubscriber`.
- Facilita la incorporación de nuevos módulos.
- Centraliza la semántica de errores del dominio.
- Escala mejor conforme crece el producto.

---

# Riesgos

Requiere un refactor de las excepciones existentes para que hereden de las nuevas clases base.

El cambio no modifica el comportamiento funcional de la API.

---

# Estado actual

No se implementará durante la fase MVP.

Se mantiene el enfoque actual para favorecer la velocidad de desarrollo.

Este ADR queda registrado como deuda técnica arquitectónica para una futura iteración de estabilización del núcleo compartido (`Shared`).

---

# Prioridad

Media.

Se recomienda ejecutar este refactor antes de que el número de módulos funcionales crezca significativamente (Players, Teams, Coaches, Trainings, Matches, Payments, etc.).