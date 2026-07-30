# Migration Standards

Esta guía define cómo diseñar, ejecutar y validar cambios de esquema y datos en
PlayerTech.

## Canonical Scope

- `docs/database/database-standards.md` define la estructura esperada de tablas,
  UUIDs, relaciones, auditoría y soft delete.
- Esta guía define el ciclo de vida operativo de las migraciones.
- `docs/architecture/guides/testing-strategy.md` define cómo probar mappings,
  persistencia y contratos.

## Core Rules

- Todo cambio persistente debe introducirse mediante una migración Doctrine versionada.
- No se deben modificar migraciones históricas que ya hayan sido ejecutadas en un
  entorno compartido.
- Las migraciones nuevas deben ser incrementales, explícitas y seguras para los datos
  existentes.
- Toda tabla tenant-aware debe incluir `academy_id` y sus foreign keys e índices
  correspondientes.
- Toda entidad de negocio debe respetar auditoría y soft delete según los estándares
  de base de datos.
- Los cambios destructivos requieren análisis de compatibilidad y una estrategia de
  transición o respaldo antes de implementarse.
- La migración SQL gestiona schema y datos relacionales; no debe crear, mover ni
  eliminar archivos físicos del filesystem.
- Los cambios de archivos físicos deben implementarse como procesos de aplicación o
  comandos operativos independientes y trazables.

## Environments

- Las migraciones deben probarse dentro del entorno Docker del proyecto.
- La base `local` se usa para desarrollo interactivo.
- La base `*_test` se usa para validar migraciones, mappings, constraints y pruebas.
- Nunca se debe ejecutar un reset destructivo contra una base que no termine en
  `*_test`.
- La promoción a producción debe ejecutar las migraciones versionadas sin depender de
  generación automática de schema.

## Migration Validation

Antes de cerrar una feature con cambios persistentes, se debe verificar:

1. La migración se ejecuta correctamente sobre una base limpia.
2. La migración se ejecuta correctamente sobre el esquema vigente esperado.
3. Las tablas, columnas, foreign keys e índices coinciden con el modelo y el mapping.
4. Las constraints preservan tenant isolation e invariantes de persistencia.
5. Los tests de integración pasan sobre la base `*_test`.
6. Los cambios de contrato o datos tienen nota de compatibilidad cuando aplica.

## Feature Traceability

El `plan.md` debe indicar si la feature altera persistencia. El `tasks.md` debe
contener una tarea concreta para la migración y tareas de validación. El
`quickstart.md` debe incluir el escenario mínimo para ejecutar y verificar el schema.

Los cambios relevantes deben registrarse en `specs/14-current-state.md`.
