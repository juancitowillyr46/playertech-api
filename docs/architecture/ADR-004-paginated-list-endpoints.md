# ADR-004: Standardize paginated list endpoints

## Estado

Propuesto

---

## Contexto

El frontend del MVP consumirá tablas y grids sobre varias entidades de negocio. Los listados actuales del backend devuelven colecciones completas sin contrato uniforme de paginación, orden ni metadatos.

Ese enfoque funciona para catálogos pequeños, pero no escala bien para módulos con crecimiento de datos ni para una UI con tablas reutilizables.

Además, el proyecto ya tiene una convención clara de `snake_case` para la API. La decisión de paginación debe ser consistente con ese contrato y no depender de un paginator de framework como contrato público.

---

## Decisión

Los endpoints de listado del API deberán usar un contrato de paginación propio, estable y uniforme:

- parámetros de consulta en `snake_case`
- respuesta con `data` y `meta`
- paginación explícita por `page` y `per_page`
- orden explícito por `sort` y `direction`
- filtros de negocio solo cuando el módulo los requiera

Formato objetivo de respuesta:

```json
{
  "data": [],
  "meta": {
    "page": 1,
    "per_page": 20,
    "total": 134,
    "total_pages": 7,
    "has_next": true,
    "has_prev": false
  }
}
```

La implementación interna podrá apoyarse en Doctrine o en consultas SQL nativas, pero el contrato público no expondrá tipos específicos del framework.

---

## Consecuencias

### Positivas

- El frontend puede construir tablas y grids con un contrato uniforme.
- Se evita cargar colecciones completas innecesariamente.
- Se simplifica la evolución de listados con muchos registros.
- Se reduce la duplicación de contratos entre módulos.

### Negativas

- Requiere migrar gradualmente los listados existentes.
- Introduce una capa común de paginación y metadatos.
- Puede requerir adaptación temporal de consumidores existentes.

---

## Alcance

Esta decisión aplica a todos los módulos que expongan listados de colección.

La migración debe hacerse de forma progresiva, empezando por los módulos más visibles para el frontend y evitando cambios disruptivos en historias ya cerradas.

---

## Estado actual

La decisión queda propuesta como estándar transversal para el API.

Si se acepta, se convierte en la convención obligatoria para nuevos listados y para refactors de módulos existentes.
