# ADR-004 - Introduce Application Finder Services

## Status

Accepted

---

## Context

Durante la implementación de los casos de uso de actualización, activación e inactivación de entidades se identificó una duplicación significativa de lógica en los Application Handlers.

Cada handler realizaba las siguientes responsabilidades:

* Validación de identificadores.
* Construcción de Value Objects.
* Consulta al repositorio.
* Verificación de existencia de la entidad.
* Lanzamiento de excepciones cuando la entidad no existía.

Por ejemplo, los handlers de `Category` repetían la misma secuencia para obtener una categoría perteneciente a una academia.

Esta duplicación aumentaba el costo de mantenimiento y dificultaba la aplicación consistente de las reglas de acceso a las entidades.

---

## Decision

Se introduce el concepto de **Application Finder Services**.

Cada Aggregate Root podrá disponer de un servicio encargado exclusivamente de recuperar entidades del repositorio y garantizar que existan.

Ejemplo:

```
Categories
└── Application
    └── Services
        └── CategoryFinder
```

La responsabilidad del Finder es:

* Recuperar la entidad.
* Centralizar la lógica de búsqueda.
* Centralizar las excepciones de "Not Found".
* Evitar duplicación entre múltiples casos de uso.

Los Application Handlers delegarán la recuperación de entidades al Finder.

Ejemplo:

```php
$category = $this->categoryFinder->findOrFail(
    $academyId,
    $categoryId
);
```

---

## Consequences

### Positivas

* Reduce duplicación de código.
* Mantiene los Application Handlers enfocados únicamente en la orquestación del caso de uso.
* Centraliza la lógica de recuperación de entidades.
* Facilita modificaciones futuras en la estrategia de búsqueda.
* Proporciona un patrón reutilizable para todos los módulos del sistema.

Ejemplos futuros:

* PlayerFinder
* TeamFinder
* VenueFinder
* MembershipFinder
* LegalGuardianFinder

---

### Negativas

Se introduce una clase adicional por Aggregate Root.

Sin embargo, el beneficio en mantenibilidad y reutilización supera ampliamente este costo.

---

## Alternatives Considered

### Repetir la lógica en cada Handler

Descartado por generar duplicación y mayor riesgo de inconsistencias.

### Métodos privados dentro de cada Handler

Descartado porque la lógica continuaría duplicándose entre distintos casos de uso.

### Traits

Descartado por mezclar comportamiento reutilizable mediante herencia horizontal, reduciendo la claridad y dificultando la evolución del diseño.

---

## Scope

Esta decisión aplica a todos los Aggregate Roots que requieran recuperar entidades desde la capa de aplicación.

---

## Notes

Los Finder Services pertenecen a la capa **Application**, ya que representan una responsabilidad de orquestación de casos de uso.

No forman parte del Dominio porque no contienen reglas de negocio propias de la entidad, sino lógica de acceso y recuperación utilizada por múltiples Application Handlers.

Los Finder Services deberán depender de las interfaces de repositorio definidas en el Dominio y nunca de implementaciones concretas de la capa Infrastructure.
