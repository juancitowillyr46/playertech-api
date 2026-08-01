# Implementation Plan: Catálogo compartido de tipos de documento

**Branch**: `025-document-type-catalog` | **Date**: 2026-07-31 | **Spec**: [spec.md](./spec.md)

**Input**: Especificación funcional desde `specs/025-document-type-catalog/spec.md`, respaldada por `EP-025` y `HU-001`.

## Resumen

Centralizar el catálogo global y estático de tipos de documento en `Shared`, exponerlo mediante `GET /api/v1/academy/document-types/options` y retirar la ruta anterior ubicada bajo `players`. Player, LegalGuardian, la importación y la gestión documental deberán consumir la misma fuente de valores y etiquetas.

## Contexto técnico

**Language/Version**: PHP 8.4

**Primary Dependencies**: Symfony 7.4, Doctrine ORM 3.x, JWT stateless, Composer 2.x

**Storage**: N/A para esta feature; el catálogo inicial es estático y no crea persistencia.

**Testing**: PHPUnit; pruebas unitarias para el catálogo y funcionales para el endpoint y autorización.

**Target Platform**: API Symfony ejecutada en Docker.

**Project Type**: Monolito modular web/API multi-tenant.

**Performance Goals**: Responder el catálogo completo en una sola consulta, sin acceso a base de datos ni paginación.

**Constraints**: Mantener el envelope `data`/`meta`, exigir autenticación y contexto válido de academia, no confiar en `academy_id` enviado por el cliente y conservar valores técnicos estables.

**Scale/Scope**: Seis opciones globales en la primera versión; consumidores iniciales: Player, LegalGuardian, importación y gestión documental.

## Verificación de constitución

*GATE: aprobado antes de la investigación.*

- **Spec antes que código**: aprobado. Existen épica, HU y `spec.md`.
- **Trazabilidad**: aprobado. La feature está identificada como `EP-025`/`HU-001` y se actualizarán contrato, pruebas, Postman y current state.
- **API First**: aprobado. La ruta, envelope y compatibilidad están definidos antes de implementar.
- **Seguridad y aislamiento**: aprobado. El endpoint requiere autenticación y contexto de academia; no recibe tenant desde el cliente.
- **Separación de módulos**: aprobado. El catálogo compartido vive en `app/src/Shared`; la gestión documental continúa en sus módulos propietarios.
- **Calidad y pruebas**: aprobado. Se planifican pruebas unitarias y funcionales.
- **Compatibilidad**: aprobado con migración explícita. La ruta anterior se reemplaza y los consumidores deben migrar.
- **Persistencia/migraciones**: no aplica. No se crea ni modifica esquema de base de datos.

## Estructura del proyecto

### Documentación de esta feature

```text
specs/025-document-type-catalog/
├── plan.md
├── research.md
├── data-model.md
├── quickstart.md
├── contracts/
│   └── document-type-options.md
└── tasks.md             # Se generará con /speckit-tasks
```

### Código fuente

```text
app/src/Shared/Domain/Document/
└── DocumentType.php

app/src/Shared/Presentation/Http/Academy/
└── DocumentTypeController.php

app/src/Modules/Player/Application/
└── ... consumidores del catálogo ...

app/src/Modules/Guardian/Application/
└── ... consumidores futuros ...

app/tests/Unit/Shared/Domain/Document/
└── DocumentTypeTest.php

app/tests/Functional/Shared/Document/
└── DocumentTypeControllerTest.php

postman/PlayerTech.postman_collection.json
```

**Decisión de estructura**: El catálogo es un concepto transversal y se ubicará en `Shared`. La presentación HTTP también será compartida porque la ruta oficial no pertenece a Player ni a LegalGuardian. Los módulos consumidores solo reutilizarán el contrato y la validación, sin duplicar valores.

## Investigación y decisiones

La investigación detallada se encuentra en [research.md](./research.md). Las decisiones principales son:

- Mantener el catálogo estático en esta iteración; no hay tabla maestra ni migración.
- Mover el enum desde `app/src/Modules/Player/Domain/Document/DocumentType.php` hacia `app/src/Shared/Domain/Document/DocumentType.php`.
- Publicar la ruta neutral y retirar la ruta anterior después de migrar consumidores.
- Usar el patrón existente de endpoints `options`: respuesta no paginada con `data` y `meta: {}`.
- Resolver el contexto de academia mediante `TenantContext`, sin aceptar `academy_id` del cliente.
- Actualizar `docs/contracts/api-reference.md`, Postman y `specs/14-current-state.md`.

## Complejidad

No se identifican violaciones de la constitución ni complejidad adicional que requiera justificación.
