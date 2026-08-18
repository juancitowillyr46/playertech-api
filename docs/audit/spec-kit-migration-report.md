# Spec Kit Migration Report

## 1. Resumen Ejecutivo

El repositorio ya tiene una base SDD sólida, pero no está todavía completamente normalizado para GitHub Spec Kit.

Fortalezas:

- existe una capa canónica en `specs/`;
- hay memoria persistente en `docs/architecture/memory/project-memory.md`;
- hay backlog funcional por épicas e historias;
- existe contrato HTTP operativo en `docs/contracts/api-reference.md`;
- el backend tiene trazabilidad relevante en `specs/14-current-state.md`.

Debilidades:

- todavía hay duplicidades históricas entre capas;
- algunas referencias de documentación compiten entre sí;
- la taxonomía documental aún no está completamente cerrada;
- la trazabilidad no siempre conecta épica, HU, spec, contrato, pruebas y estado actual.

Conclusión:

- el proyecto es compatible con SDD;
- no cumple 100% todavía;
- la migración debe hacerse por etapas, sin borrar información y sin mover masivamente antes de validar.

## 2. Inventario Documental

Inventario resumido por capa:

| Capa | Estado | Observación |
| --- | --- | --- |
| `specs/` | Vigente | Es la fuente canónica más fuerte del proyecto. |
| `docs/backlog/` | Vigente | Buen nivel de épicas e historias, aunque con duplicados históricos. |
| `docs/architecture/` | Vigente | Contiene política SDD, auditorías y memoria persistente. |
| `docs/contracts/` | Vigente | Debe seguir como índice, no como segunda fuente del contrato. |
| `docs/domains/` | Vigente | Adecuado para dominio puro. |
| `docs/flows/` | Vigente | Adecuado para flujos funcionales específicos. |
| `docs/domain/` | Histórico | Debe tratarse como legado conceptual; la ruta activa es `docs/domains/`. |
| `docs/database/` | Vigente | Soporta el modelo de datos y ERD. |
| `docs/product/` | Vigente | Visión y roadmap. |
| `docs/operations/` | Vigente | Procedimientos operativos del entorno. |

Inventario cuantitativo actual:

- `specs/`: 20 archivos
- `docs/architecture/`: 15 archivos
- `docs/backlog/`: 134 archivos
- `docs/product/`: 4 archivos
- `docs/database/`: 4 archivos
- `docs/domains/`: 9 archivos
- `docs/flows/`: 4 archivos
- `docs/contracts/`: 1 archivo
- `docs/traceability/`: 2 archivos
- `docs/audit/`: 2 archivos
- `docs/domain/`: 1 archivo legado
- `.specify/`: 6 archivos
- `.agents/skills/`: 10 archivos

## 3. Estructura Actual

La estructura actual ya se acerca a SDD:

```text
specs/
docs/product/
docs/backlog/
docs/architecture/
docs/contracts/
docs/database/
docs/domains/
docs/flows/
docs/operations/
docs/domain/ (legado)
```

## 4. Problemas Encontrados

- coexistencia de documentos canónicos y legacy para el mismo concepto;
- referencias antiguas a rutas que ya cambiaron;
- documentación de contrato HTTP repartida entre índice, referencia y ejemplos;
- ausencia de una capa formal de trazabilidad por cambio;
- algunos documentos de flujo y UX vivieron temporalmente en carpetas de dominio, lo que generó confusión semántica.

## 5. Documentos Duplicados

Duplicados o semi-duplicados detectados:

- `docs/domains/domain-overview.md` y `docs/_archive/domain/02-domains.md`
- `docs/contracts/api-reference.md` y `docs/contracts/api-reference.md`
- `docs/contracts/api-principles.md` y `docs/contracts/api-reference.md` en parte del contrato HTTP
- `docs/product/product-vision.md` y `docs/_archive/product/00-product.md`
- `docs/backlog/stories/EP-012/HU-004-attach-payment-evidence.md` y `docs/backlog/stories/EP-009-membership-management/HU-004-attach-payment-evidence.md`
- múltiples `README.md` por carpeta, válidos como índices, no como duplicados de contenido

Decisión de canonicidad:

- `docs/product/product-vision.md` es la fuente canónica de producto.
- `docs/domains/domain-overview.md` es la fuente canónica del modelo de dominio.
- `docs/contracts/api-reference.md` es la fuente canónica del contrato HTTP.
- `docs/_archive/product/00-product.md`, `docs/_archive/domain/02-domains.md` y `docs/contracts/api-reference.md`
  quedan como espejos/índices históricos u operativos, no como canon paralelo.

## 6. Contradicciones

- paginación: `limit` en `docs/contracts/api-principles.md` frente a `per_page` en `docs/contracts/api-reference.md`;
- fuente operativa HTTP: Postman, índice de contratos y referencia canónica conviven;
- algunos documentos históricos mezclan `snake_case` y `camelCase` en ejemplos;
- el modelo de dominio conceptual aún tiene legado que debe quedar explícitamente marcado como tal.

## 7. Documentos Huérfanos

Documentos que requieren revisión de ubicación o estatus:

- `docs/_archive/domain/02-domains.md`
- cualquier referencia antigua a `docs/academy-frontend-contract.md`
- cualquier referencia antigua a `docs/ubuntu-setup.md`
- referencias antiguas a rutas de import de player movidas a `docs/flows/player/`

## 8. Épicas Incompletas

Se observa backlog amplio, pero con validación desigual entre épicas:

- algunas épicas tienen historias duplicadas o renombradas;
- otras historias reflejan contrato vigente pero necesitan consolidación de naming;
- la épica de `Player` y sus flujos asociados tienen mayor densidad de cambios recientes.

### Clasificación SDD del backlog

Se creó una clasificación operativa en:

- `docs/audit/backlog-spec-kit-classification.md`

Resumen de impacto:

- `EP-001` a `EP-023` pueden mapearse a carpetas `specs/[###-feature]/`.
- `EP-007`, `EP-009`, `EP-012`, `EP-021` y `EP-023` requieren segmentación por subfeatures.
- `EP-004` y `EP-009` contienen señales de naming o alcance que conviene reconciliar antes de crear specs definitivas.

## 9. Historias de Usuario Incompletas

Riesgos detectados:

- historias sin contrato operativo claramente asociado;
- historias duplicadas con intención similar;
- historias que deben marcarse como vigentes, históricas o derivadas.

## 10. Requisitos Sin Trazabilidad

Riesgos detectados:

- reglas de negocio consolidadas en código pero no siempre marcadas en la HU;
- decisiones de contrato HTTP que viven en referencia operativa pero no siempre en el epic correspondiente;
- cambios de UX que no siempre dejan nota en `current-state`.

## 11. Funcionalidades Implementadas Sin Especificación

Hay slices implementados que deben quedar mejor alineados con su huella documental.
Esto no significa ausencia total de documentación, sino necesidad de mejor trazabilidad entre:

- implementación;
- historia;
- contrato;
- estado actual.

## 12. Especificaciones Sin Evidencia Suficiente de Implementación

Quedan áreas donde la intención está bien documentada, pero no siempre hay evidencia homogénea de ejecución o pruebas asociadas.

## 12.1 Clasificación de Alto Nivel

### KEEP

- `specs/`
- `docs/product/`
- `docs/backlog/`
- `docs/architecture/`
- `docs/contracts/`
- `docs/database/`
- `docs/domains/`
- `docs/flows/`
- `docs/operations/`
- `docs/audit/`
- `docs/traceability/`

### MOVE

- `docs/_archive/domain/02-domains.md` hacia legado explícito o referencia archivada
- referencias antiguas a rutas previas de docs movidos

### MERGE

- `docs/product/product-vision.md` con `docs/_archive/product/00-product.md` o viceversa, dejando una sola fuente canónica
- `docs/domains/domain-overview.md` con `docs/_archive/domain/02-domains.md` para mantener una sola versión activa del modelo de dominio
- `docs/contracts/api-reference.md` con `docs/contracts/api-reference.md` manteniendo el índice y la referencia sin duplicar contenido

### ARCHIVE

- `docs/_archive/domain/02-domains.md`
- duplicados históricos que ya no representen la verdad vigente

### RENAME

- rutas históricas que siguen nombrando documentos ya movidos o consolidados

## 13. Propuesta de Estructura Objetivo

```text
.specify/
├── memory/
│   └── constitution.md
├── templates/
└── scripts/

docs/
├── product/
├── backlog/
├── architecture/
├── contracts/
├── database/
├── operations/
├── audit/
└── traceability/

specs/
├── README.md
├── 00-product.md
├── 01-arquitecture.md
├── 02-domains.md
├── 03-security.md
├── 04-api.md
├── 06-database.md
├── 08-dev-standards.md
├── 09-roadmap.md
├── 10-project-setup.md
├── 11-testing-strategy.md
├── 12-execution-order.md
├── 13-user-story-rebuild-guide.md
├── 14-current-state.md
├── 15-module-creation-guide.md
├── 16-api-reference.md
├── 17-environment-guide.md
├── 18-financial-domain-model.md
└── 19-observability-local.md
```

## 14. Mapa Entre Estructura Actual Y Estructura Nueva

| Estructura actual | Estructura objetivo | Acción |
| --- | --- | --- |
| `specs/` | `specs/` | Mantener |
| `docs/product/` | `docs/product/` | Mantener |
| `docs/backlog/` | `docs/backlog/` | Mantener |
| `docs/architecture/` | `docs/architecture/` | Mantener |
| `docs/contracts/` | `docs/contracts/` | Mantener como índice |
| `docs/database/` | `docs/database/` | Mantener |
| `docs/domains/` | `docs/domains/` | Mantener dominio puro |
| `docs/flows/` | `docs/flows/` | Mantener flujos funcionales |
| `docs/domain/` | `docs/_archive/domain/` o legado | Marcar como legado |

## 15. Riesgos de la Migración

- perder trazabilidad si se mueve antes de mapear;
- duplicar de nuevo si no hay una fuente canónica por tipo;
- romper enlaces históricos si no se conservan referencias;
- mezclar flujo con dominio si no se respetan las capas.

## 16. Archivos Que No Deben Modificarse Sin Revisión

- `docs/contracts/api-reference.md`
- `specs/14-current-state.md`
- `docs/domains/domain-overview.md`
- `docs/architecture/memory/project-memory.md`
- `docs/backlog/epics/*`
- `docs/backlog/stories/*`

## 17. Preguntas Abiertas

- ¿Qué documentos históricos deben archivarse y cuáles deben permanecer como referencia?
- ¿Se consolida `docs/contracts/api-reference.md` solo como índice o también como landing principal para frontend?
- ¿Se formaliza una carpeta `docs/audit/` y `docs/traceability/` como parte del estándar?

## 18. Plan de Migración Por Etapas

### Etapa 1

- cerrar la taxonomía documental;
- definir la fuente de verdad por capa;
- registrar la constitución SDD.

### Etapa 2

- clasificar cada markdown;
- detectar duplicados y contradicciones;
- marcar huérfanos y legacy.

### Etapa 3

- mover solo lo seguro y reversible;
- consolidar índices;
- dejar referencias cruzadas.

### Etapa 4

- archivar documentos históricos;
- actualizar memoria persistente y current state;
- validar que no haya rutas rotas.
