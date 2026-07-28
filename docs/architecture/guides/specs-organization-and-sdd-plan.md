# Specs Organization And SDD Plan

## Objetivo

Definir cómo se organiza la documentación del proyecto y cómo llevar el enfoque de SDD a cobertura completa sin duplicar fuentes de verdad.

## Estado Actual

La documentación está distribuida en tres niveles:

1. `specs/`
2. `docs/`
3. `docs/architecture/`

## Rol De Cada Capa

### `specs/`

Fuente operativa principal para:

- arquitectura general
- seguridad
- API
- base de datos
- testing
- environment
- execution order
- current state
- module creation

Uso:

- decidir cómo se construye
- decidir en qué orden se construye
- dejar contrato estable de trabajo

### `docs/architecture/`

Memoria persistente de decisiones técnicas y evolución real del backend.

Uso:

- ADRs
- SDDs
- memory del proyecto
- auditorías técnicas
- evolución por dominio

### `docs/`

Documentos operativos y contractuales de uso más directo:

- contratos frontend
- specs de flujo de negocio
- guías de setup
- backlog stories
- referencias de API

## Problema Detectado

Hoy existe cierta superposición entre:

- `docs/contracts/api-reference.md`
- `docs/contracts/api-reference.md`
- `docs/flows/player/player-import-flow-spec.md`
- `docs/flows/player/player-import-ux-spec.md`

Esto no es malo por sí mismo, pero requiere una regla clara:

- un documento manda como contrato
- el resto acompaña como guía, memoria o UX

## Regla Propuesta

### Fuente De Verdad

- `specs/` define el estándar base.
- `docs/architecture/memory/project-memory.md` registra estado persistente y decisiones.
- `docs/domains/<dominio>/` contiene el contrato central del dominio puro.
- `docs/flows/<dominio>/` contiene el contrato central de un flujo funcional específico.
- `specs/[###-feature]/` contiene el ciclo de vida de cada feature bajo Spec Kit.

### Documentos Satélite

- cualquier spec visual de frontend debe referenciar el documento central
- cualquier auditoría debe vivir en `docs/architecture/`
- cualquier HU debe referenciar el spec central y no reescribirlo

## SDD Total

La idea de SDD parcial funciona para arrancar, pero si quieres aplicar SDD al 100%, el plan debería ser este:

### Fase 1. Inventario

- listar todos los flujos del producto
- agrupar por épica y por módulo
- identificar qué tiene contrato claro y qué no

### Fase 2. Contrato Base

- por cada flujo importante, definir:
  - objetivo
  - actores
  - entradas
  - salidas
  - reglas de negocio
  - errores
  - trazabilidad

### Fase 3. Normalización

- asegurar que cada flujo tenga un único documento central
- eliminar contradicciones entre `specs` y `docs`
- convertir duplicados en referencias cruzadas

### Fase 4. Arquitectura Persistente

- documentar decisiones en ADRs
- registrar estado real en `project-memory.md`
- dejar auditorías para slices sensibles

### Fase 5. Gobernanza

- antes de tocar código, revisar:
  - current state
  - api reference
  - memory
  - spec del flujo
- después de tocar código, actualizar:
  - current state
  - memory
  - spec del flujo si cambió contrato

## Plan Recomendado

### Corto Plazo

1. Consolidar `docs/flows/*` para flujos funcionales específicos.
2. Mantener `docs/domains/*` solo para dominio puro.
3. Registrar auditorías técnicas en `docs/architecture/`.

### Mediano Plazo

1. Revisar todos los flujos críticos de `Player`, `Team`, `Category`, `Academy` y `Identity`.
2. Crear o ajustar un documento central por flujo importante.
3. Reducir duplicidad entre `specs/` y `docs/`.

### Largo Plazo

1. Tener un SDD completo por módulo o por flujo top-level.
2. Mantener siempre:
   - contrato
   - memoria
   - auditoría
   - trazabilidad

## Criterio Práctico

Si un documento:

- define contrato estable, va en `specs/` o en un spec técnico central del flujo
- conserva decisiones y evolución, va en `docs/architecture/`
- explica UX o implementación visual, va en `docs/`

## Recomendación Final

Sí conviene aplicar SDD al 100%, pero con esta regla:

- no más documentos que hablen de lo mismo sin jerarquía
- un documento central por flujo
- los demás documentos solo complementan
