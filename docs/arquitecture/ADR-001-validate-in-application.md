# ADR-001: Mover la validación de entrada a Presentation

## Estado

Aceptado

---

## Contexto

Durante la implementación del MVP se usó Symfony Validator dentro de DTOs de `Application` como una decisión pragmática para acelerar el desarrollo.

Esa decisión introdujo un acoplamiento innecesario entre la capa `Application` y Symfony, algo que contradice el objetivo de mantener el núcleo de negocio independiente del framework.

La validación pertenece al borde de entrada de la aplicación, no al núcleo de orquestación de casos de uso.

---

## Decisión

La validación de datos de entrada se realizará en la capa `Presentation`, preferentemente mediante objetos Request específicos y `Symfony Validator` aplicado allí.

La capa `Application` deberá exponer DTOs, Commands y Queries **agnósticos del framework**, sin atributos de validación Symfony ni dependencias directas de infraestructura web.

Flujo objetivo:

```text
HTTP Request
    │
    ▼
Presentation Request
    │
    ▼
Validation en Presentation
    │
    ▼
Application DTO / Command / Query
    │
    ▼
Handler
    │
    ▼
Domain
```

---

## Consecuencias

### Positivas

* `Application` queda libre de dependencias del framework.
* La validación se concentra en el borde HTTP.
* Se mejora la testabilidad de Commands, Queries y Handlers.
* Se alinea mejor con Hexagonal Architecture y Clean Architecture.

### Negativas

* Requiere refactor de DTOs existentes.
* Introduce más clases de transporte en `Presentation`.

---

## Alcance

Esta decisión aplica a todos los módulos funcionales y futuros casos de uso.

---

## Estado actual

La decisión quedó aceptada como dirección de arquitectura.

El MVP puede seguir operando con la deuda técnica existente, pero cualquier módulo nuevo o refactor relevante deberá seguir este criterio.
