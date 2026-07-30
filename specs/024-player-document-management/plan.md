# Plan de implementación: Gestión de documentos del jugador

**Rama**: `024-player-document-management` | **Fecha**: 2026-07-30 | **Spec**: [spec.md](./spec.md)

**Entrada**: Especificación de feature desde `/specs/024-player-document-management/spec.md`

**Estándares aplicables**: [ADR-004](../../docs/architecture/ADR-004-paginated-list-endpoints.md), [referencia API](../../docs/contracts/api-reference.md), [estándares de migración](../../docs/database/migration-standards.md), [estrategia de pruebas](../../docs/architecture/guides/testing-strategy.md) y la constitución del proyecto.

## Resumen

Implementar la gestión documental de jugadores con alcance tenant para usuarios Owner/Admin: listar documentos activos con paginación, cargar archivos con validación y almacenamiento privado, ver en línea, descargar como adjunto, reemplazar conservando el identificador y aplicar soft delete a la metadata eliminando el archivo físico. La implementación debe reutilizar el modelo tenant de Player, auditoría, soft delete, Problem Details y envelope de respuesta existente.

## Contexto técnico

**Lenguaje/Versión**: PHP 8.4

**Dependencias principales**: Symfony 7.4, Doctrine ORM, seguridad JWT, infraestructura compartida de API/Problem Details y patrones existentes del módulo Player

**Almacenamiento**: MySQL 8+ para metadata y filesystem privado para los archivos documentales

**Pruebas**: PHPUnit con pruebas unitarias, de integración, funcionales y de contrato según `docs/architecture/guides/testing-strategy.md`

**Plataforma objetivo**: Servicio backend Linux ejecutado en Docker

**Tipo de proyecto**: API de servicio web / monolito modular

**Restricciones**: Aislamiento multi-tenant mediante `academy_id` autenticado; archivos fuera del web root público; formatos limitados a PDF/JPG/JPEG/PNG; carga máxima de 3 MB; conservación de nombres originales sanitizados; auditoría y soft delete consistentes con el dominio; metadata introducida mediante migración Doctrine y validada en la base de pruebas

**Escala/Alcance**: Un jugador puede tener múltiples documentos, incluso del mismo tipo; el MVP se enfoca en metadata y entrega de archivos para un tenant a la vez

## Verificación de constitución

*PUERTA: Debe aprobarse antes de la investigación de Fase 0 y revisarse después del diseño de Fase 1.*

- Se respeta el flujo spec-first: existe y está enlazada la spec de la feature.
- La trazabilidad es explícita: la épica y las HUs están documentadas en `docs/backlog/stories/EP-024-player-document-management/`.
- Los requisitos son verificables y acotados: no se incluyen OCR, autenticidad, virus, expiración, recordatorios ni cargas de acudientes.
- La seguridad y el aislamiento son explícitos: el tenant proviene de autenticación y no del cliente.
- Se conserva la arquitectura: monolito modular, Application orientada a CQRS, dominio puro, XML mapping, auditoría, soft delete y Problem Details.
- Las pruebas son explícitas: unitarias para reglas, integración para persistencia y archivos, y funcionales para contratos HTTP.
- Los cambios persistentes son explícitos: el schema `player_documents` se introduce mediante migración Doctrine y se verifica con mapping, constraints, índices y base de pruebas.

## Estructura del proyecto

### Documentación de esta feature

```text
specs/024-player-document-management/
├── plan.md
├── research.md
├── data-model.md
├── quickstart.md
└── contracts/
    ├── document-listing.md
    ├── document-upload.md
    ├── document-view.md
    ├── document-download.md
    ├── document-replace.md
    └── document-delete.md
```

### Código fuente (raíz del repositorio)

```text
app/src/Modules/Player/
├── Domain/
│   └── Document/
├── Application/
│   └── Document/
├── Infrastructure/
│   └── Persistence/
└── Presentation/
    └── Http/

docs/backlog/stories/EP-024-player-document-management/
specs/024-player-document-management/
```

**Decisión estructural**: La feature pertenece al contexto delimitado existente de `Player` y debe implementarse como un subdominio documental dentro de `app/src/Modules/Player/`, siguiendo la estructura por capas del monolito. Su documentación permanece aislada en `specs/024-player-document-management/` y el historial funcional en la carpeta de historias de EP-024.

Las respuestas públicas de listados usan el envelope canónico `data` + `meta`. Los nombres de query params usan `snake_case`; los campos JSON de request y response usan `camelCase`, mientras la metadata de paginación usa los nombres `snake_case` establecidos por ADR-004.

Los cambios de base de datos usan el flujo existente de migraciones Doctrine y los [estándares de migración](../../docs/database/migration-standards.md). La migración crea únicamente la tabla de metadata; los archivos permanecen en almacenamiento privado y son gestionados por el ciclo de vida de la aplicación, no por SQL.

## Seguimiento de complejidad

No existen violaciones de la constitución que requieran justificación especial para el alcance actual del MVP.
