# Auditoría de Spec-Driven Development - Backend

## Alcance

Auditoría documental y técnica del backend de PlayerTech para evaluar su preparación para un flujo de Spec-Driven Development (SDD) sin modificar código ni contratos.

Fuentes revisadas:

- [`AGENTS.md`](../../AGENTS.md)
- [`README.md`](../../README.md)
- [`specs/01-arquitecture.md`](../../specs/01-arquitecture.md)
- [`specs/03-security.md`](../../specs/03-security.md)
- [`specs/04-api.md`](../../specs/04-api.md)
- [`specs/10-project-setup.md`](../../specs/10-project-setup.md)
- [`specs/11-testing-strategy.md`](../../specs/11-testing-strategy.md)
- [`specs/12-execution-order.md`](../../specs/12-execution-order.md)
- [`specs/14-current-state.md`](../../specs/14-current-state.md)
- [`specs/15-module-creation-guide.md`](../../specs/15-module-creation-guide.md)
- [`specs/16-api-reference.md`](../../specs/16-api-reference.md)
- [`specs/18-financial-domain-model.md`](../../specs/18-financial-domain-model.md)
- [`docs/product/03-roadmap.md`](../../docs/product/03-roadmap.md)
- [`docs/backlog/future-epics.md`](../../docs/backlog/future-epics.md)
- [`docs/backlog/epics/*`](../../docs/backlog/epics)
- [`docs/backlog/stories/*`](../../docs/backlog/stories)
- [`app/config/packages/nelmio_api_doc.yaml`](../../app/config/packages/nelmio_api_doc.yaml)
- [`app/config/packages/security.yaml`](../../app/config/packages/security.yaml)
- [`app/config/packages/doctrine.yaml`](../../app/config/packages/doctrine.yaml)
- [`app/src/Shared/Infrastructure/Http/ProblemDetailsExceptionSubscriber.php`](../../app/src/Shared/Infrastructure/Http/ProblemDetailsExceptionSubscriber.php)
- [`app/src/Shared/Infrastructure/Persistence/Doctrine/Filter/TenantFilter.php`](../../app/src/Shared/Infrastructure/Persistence/Doctrine/Filter/TenantFilter.php)
- [`app/src/Shared/Infrastructure/EventSubscriber/DoctrineAuditSubscriber.php`](../../app/src/Shared/Infrastructure/EventSubscriber/DoctrineAuditSubscriber.php)
- [`app/src/Shared/Infrastructure/Persistence/Doctrine/Filter/SoftDeleteFilter.php`](../../app/src/Shared/Infrastructure/Persistence/Doctrine/Filter/SoftDeleteFilter.php)
- `app/tests/*`

## 1. Resumen ejecutivo

El repositorio tiene una base documental fuerte para operar bajo SDD, pero todavía no tiene una estructura canónica única que elimine duplicidades entre specs, backlog y referencia HTTP.

Lo mejor resuelto hoy es:

- arquitectura base documentada;
- separación plataforma/tenant;
- contrato HTTP operativo bastante avanzado;
- reglas multi-tenant y de auditoría ya explícitas;
- cobertura de pruebas razonable para varios dominios.

Lo más débil es:

- la coexistencia de varias fuentes de verdad parciales para la API;
- algunas diferencias de naming y contrato entre documentos históricos y la referencia operativa;
- la trazabilidad todavía está fragmentada entre `specs/`, `docs/backlog/`, `http/` y `postman/`.

**Madurez SDD actual estimada: 7/10.**

## 2. Nivel de preparación

| Área | Puntuación | Evidencia |
| --- | ---: | --- |
| Documentación de producto | 8/10 | [`docs/product/03-roadmap.md`](../../docs/product/03-roadmap.md), [`docs/backlog/future-epics.md`](../../docs/backlog/future-epics.md) |
| Especificaciones funcionales | 8/10 | [`docs/backlog/epics/*`](../../docs/backlog/epics), [`docs/backlog/stories/*`](../../docs/backlog/stories) |
| Reglas de negocio | 7/10 | [`specs/16-api-reference.md`](../../specs/16-api-reference.md), [`specs/18-financial-domain-model.md`](../../specs/18-financial-domain-model.md), reglas visibles en handlers y requests |
| Arquitectura documentada | 9/10 | [`specs/01-arquitecture.md`](../../specs/01-arquitecture.md), [`specs/12-execution-order.md`](../../specs/12-execution-order.md), [`specs/15-module-creation-guide.md`](../../specs/15-module-creation-guide.md) |
| Contratos API | 8/10 | [`specs/04-api.md`](../../specs/04-api.md), [`specs/16-api-reference.md`](../../specs/16-api-reference.md), [`app/config/packages/nelmio_api_doc.yaml`](../../app/config/packages/nelmio_api_doc.yaml) |
| Trazabilidad | 6/10 | [`specs/14-current-state.md`](../../specs/14-current-state.md), historial de commits en el propio documento, backlog con historias por epic |
| Pruebas | 8/10 | `app/tests/Unit`, `app/tests/Integration`, `app/tests/Functional` |
| Multi-tenancy | 9/10 | [`app/src/Shared/Infrastructure/Persistence/Doctrine/Filter/TenantFilter.php`](../../app/src/Shared/Infrastructure/Persistence/Doctrine/Filter/TenantFilter.php), [`app/src/Modules/Identity/Infrastructure/Tenant/TenantContextSubscriber.php`](../../app/src/Modules/Identity/Infrastructure/Tenant/TenantContextSubscriber.php) |
| Autorización | 8/10 | [`app/config/packages/security.yaml`](../../app/config/packages/security.yaml), [`app/src/Modules/Identity/Domain/Policy/UserAdministrationPolicy.php`](../../app/src/Modules/Identity/Domain/Policy/UserAdministrationPolicy.php) |
| Preparación para Codex | 8/10 | [`AGENTS.md`](../../AGENTS.md), [`specs/14-current-state.md`](../../specs/14-current-state.md), [`specs/12-execution-order.md`](../../specs/12-execution-order.md) |

## 3. Fuentes de verdad actuales

| Dominio / módulo | Fuente principal | Fuente secundaria | Observación |
| --- | --- | --- | --- |
| Arquitectura base | `specs/01-arquitecture.md` | `specs/12-execution-order.md` | Bien alineada con el diseño modular |
| API HTTP | `specs/16-api-reference.md` | `specs/04-api.md`, `app/config/packages/nelmio_api_doc.yaml` | La referencia operativa está más madura que la especificación base |
| Seguridad | `specs/03-security.md` | `app/config/packages/security.yaml`, `app/src/Modules/Identity/Infrastructure/Tenant/TenantContextSubscriber.php` | La regla `ROLE_ROOT` vs tenant está bien implementada |
| Tenant isolation | `specs/01-arquitecture.md` | `app/src/Shared/Infrastructure/Persistence/Doctrine/Filter/TenantFilter.php` | Regla explícita y testeada |
| Academy | `specs/14-current-state.md` | `docs/backlog/epics/EP-001.md` | Hay trazabilidad funcional y técnica |
| Identity | `docs/backlog/epics/EP-003.md` | `app/tests/Functional/Modules/Identity/*` | Muy bien cubierto en backlog y tests |
| Player / Team / Membership / Payment | `docs/backlog/stories/*` | `app/tests/*` | La especificación de historias ya existe, pero todavía está distribuida |

## 4. Especificaciones existentes

Utilizables:

- [`specs/01-arquitecture.md`](../../specs/01-arquitecture.md)
- [`specs/03-security.md`](../../specs/03-security.md)
- [`specs/04-api.md`](../../specs/04-api.md)
- [`specs/12-execution-order.md`](../../specs/12-execution-order.md)
- [`specs/14-current-state.md`](../../specs/14-current-state.md)
- [`specs/15-module-creation-guide.md`](../../specs/15-module-creation-guide.md)
- [`specs/16-api-reference.md`](../../specs/16-api-reference.md)

Incompletas o mixtas:

- [`specs/10-project-setup.md`](../../specs/10-project-setup.md): mezcla base técnica con decisiones ya estabilizadas.
- [`specs/18-financial-domain-model.md`](../../specs/18-financial-domain-model.md): es útil, pero solo cubre un subdominio.
- [`docs/backlog/future-epics.md`](../../docs/backlog/future-epics.md): sirve como visión, no como contrato canónico.

Contradictorias o con riesgo de desalineación:

- [`specs/04-api.md`](../../specs/04-api.md) usa `limit` en paginación, mientras [`specs/16-api-reference.md`](../../specs/16-api-reference.md) estandariza `per_page`.
- [`specs/16-api-reference.md`](../../specs/16-api-reference.md) actúa como contrato operativo, pero todavía convive con la idea de que OpenAPI interactivo no es la referencia principal.
- `README.md` menciona `http/*.http` como ejemplos operativos, mientras [`specs/16-api-reference.md`](../../specs/16-api-reference.md) eleva Postman como referencia de contrato HTTP.

## 5. Reglas implícitas encontradas

Reglas que hoy ya están bastante claras por código y tests, aunque no todas aparecen como especificación canónica aislada:

- `ROLE_ROOT` debe operar sin `academy_id` y con acceso a `/api/v1/platform/*`.
  - Evidencia: [`app/src/Modules/Identity/Infrastructure/Tenant/TenantContextSubscriber.php`](../../app/src/Modules/Identity/Infrastructure/Tenant/TenantContextSubscriber.php), [`app/config/packages/security.yaml`](../../app/config/packages/security.yaml).
  - Riesgo: bajo.
- Todo usuario tenant debe tener `academy_id`.
  - Evidencia: [`app/src/Modules/Identity/Domain/Policy/UserAdministrationPolicy.php`](../../app/src/Modules/Identity/Domain/Policy/UserAdministrationPolicy.php).
  - Riesgo: bajo.
- El tenant activo se propaga desde JWT a `TenantContext`.
  - Evidencia: [`app/src/Modules/Identity/Infrastructure/Tenant/TenantContextSubscriber.php`](../../app/src/Modules/Identity/Infrastructure/Tenant/TenantContextSubscriber.php).
  - Riesgo: bajo.
- El filtro Doctrine excluye otros tenants por defecto.
  - Evidencia: [`app/src/Shared/Infrastructure/Persistence/Doctrine/Filter/TenantFilter.php`](../../app/src/Shared/Infrastructure/Persistence/Doctrine/Filter/TenantFilter.php), [`app/tests/Integration/Shared/Infrastructure/Persistence/Doctrine/TenantFilterTest.php`](../../app/tests/Integration/Shared/Infrastructure/Persistence/Doctrine/TenantFilterTest.php).
  - Riesgo: bajo.
- Las entidades con soft delete no deben borrarse físicamente.
  - Evidencia: [`app/src/Shared/Infrastructure/Persistence/Doctrine/Filter/SoftDeleteFilter.php`](../../app/src/Shared/Infrastructure/Persistence/Doctrine/Filter/SoftDeleteFilter.php), [`app/tests/Unit/Modules/Team/Domain/Team/TeamTest.php`](../../app/tests/Unit/Modules/Team/Domain/Team/TeamTest.php).
  - Riesgo: bajo.
- Una matrícula activa por jugador y academia.
  - Evidencia: [`specs/16-api-reference.md`](../../specs/16-api-reference.md), tests de `Membership`.
  - Riesgo: medio, porque es una regla de negocio sensible.
- Un equipo o categoría activa debe ser única dentro del tenant según el flujo documentado.
  - Evidencia: [`docs/backlog/stories/EP-001/HU-015-provision-tenant-from-platform.md`](../../docs/backlog/stories/EP-001/HU-015-provision-tenant-from-platform.md), [`specs/16-api-reference.md`](../../specs/16-api-reference.md).
  - Riesgo: medio.

## 6. Vacíos críticos

Crítico:

- No existe todavía una carpeta canónica por dominio para specs vivas.
- La trazabilidad entre requisito, historia, contrato y test sigue fragmentada.

Alto:

- La referencia de API se reparte entre `specs/04-api.md`, `specs/16-api-reference.md`, `http/` y `postman/`.
- Falta una convención formal de identificadores de cambio y requisito para SDD.
- No hay una política explícita de sincronización con frontend fuera de contratos y ejemplos.

Medio:

- Algunas reglas de negocio siguen expresadas como inferencia de código y no como requisitos canónicos.
- La documentación histórica convive con estados actuales sin una capa de archivado o evolución claramente separada.

Bajo:

- OpenAPI está configurado, pero aún no es la fuente operativa principal.
- Hay cobertura de pruebas suficiente para caracterización en varios módulos, aunque no uniforme.

## 7. Contradicciones

1. **Paginación**
   - [`specs/04-api.md`](../../specs/04-api.md) usa `limit`.
   - [`specs/16-api-reference.md`](../../specs/16-api-reference.md) usa `per_page`.
   - Riesgo: contratos distintos para frontend y QA.

2. **Fuente de contrato HTTP**
   - [`README.md`](../../README.md) y la carpeta `http/` sugieren ejemplos operativos.
   - [`specs/16-api-reference.md`](../../specs/16-api-reference.md) eleva Postman como referencia de contrato.
   - Riesgo: duplicación documental y drift.

3. **OpenAPI**
   - [`app/config/packages/nelmio_api_doc.yaml`](../../app/config/packages/nelmio_api_doc.yaml) muestra que existe configuración de documentación OpenAPI.
   - [`specs/16-api-reference.md`](../../specs/16-api-reference.md) indica que todavía no hay Swagger/OpenAPI interactivo como fuente principal.
   - Riesgo: expectativa diferente entre implementación y documentación operativa.

4. **Formato de respuesta**
   - [`specs/04-api.md`](../../specs/04-api.md) deja ejemplos con `camelCase`.
   - Parte de los ejemplos históricos del API reference todavía mezclan `snake_case` y `camelCase` en algunos payloads.
   - Riesgo: inconsistencia para integraciones.

## 8. Propuesta de estructura SDD

La estructura propuesta por el prompt es válida, pero para este repo conviene una versión más compacta y compatible con el estado actual:

```text
/
├── AGENTS.md
├── docs/
│   ├── product/
│   ├── architecture/
│   ├── backlog/
│   └── contracts/
├── specs/
│   ├── shared/
│   ├── academy/
│   ├── identity/
│   ├── player/
│   ├── team/
│   ├── membership/
│   └── payment/
├── changes/
│   ├── active/
│   └── archived/
└── skills/
```

### Criterio por carpeta

- `docs/product/`
  - Propósito: visión, roadmap y descubrimiento.
  - Fuente: producto y entrevistas.
  - Riesgo: bajo.
- `docs/architecture/`
  - Propósito: ADR, principios y decisiones transversales.
  - Fuente: arquitectura aprobada.
  - Riesgo: medio.
- `docs/backlog/`
  - Propósito: épicas e historias de usuario.
  - Fuente: análisis funcional.
  - Riesgo: medio, si se deja desalineado con el código.
- `docs/contracts/`
  - Propósito: referencia HTTP operativa compartida con frontend.
  - Fuente: API actual, Postman, ejemplos.
  - Riesgo: alto si duplica specs.
- `specs/<dominio>/`
  - Propósito: especificación canónica por dominio.
  - Fuente: backlog, código, pruebas y contratos.
  - Riesgo: bajo si se limita a una sola verdad por dominio.
- `changes/active/`
  - Propósito: cambios grandes con ciclo de vida explícito.
  - Fuente: iniciativa concreta.
  - Riesgo: medio, pero útil para trazabilidad.
- `changes/archived/`
  - Propósito: historial de cambios cerrados.
  - Fuente: cambios finalizados.
  - Riesgo: bajo.

## 9. Documentos iniciales recomendados

1. `specs/shared/sdd-principles.md`
   - Para fijar la forma de escribir requisitos y evitar duplicidad.
2. `specs/identity/*.md`
   - Porque identidad, roles y tenant son la base de todo el sistema.
3. `specs/academy/*.md`
   - Porque Academy funciona como módulo de referencia técnica.
4. `docs/contracts/api-reference.md`
   - Para consolidar la referencia HTTP sin mezclarla con diseño funcional.
5. `changes/active/CHG-XXX-...`
   - Solo para cambios grandes que necesiten seguimiento formal.

## 10. Dominios prioritarios

1. Identity
2. Academy
3. Player
4. Team
5. Membership
6. Payment

Motivo: son los dominios que ya concentran más reglas operativas, más contratos HTTP y más dependencia cruzada.

## 11. Estrategia de adopción incremental

### Fase 1: ordenar la documentación actual

- Unificar la fuente de contrato HTTP.
- Definir naming de paginación.
- Separar specs canónicas de backlog e ինտenciones.

### Fase 2: trazabilidad mínima

- Introducir IDs de requisito y de historia.
- Asociar cada historia a pruebas y contrato.
- Registrar la relación en `specs/14-current-state.md`.

### Fase 3: especificación por dominio

- Crear una spec canónica por dominio.
- Mantenerla sincronizada con tests y endpoints.

### Fase 4: automatización ligera

- Generar o validar contratos desde una sola fuente.
- Usar pruebas de caracterización para lo crítico.

### Fase 5: refactorización solo donde haya evidencia

- Recién después de cerrar trazabilidad, ajustar código o contratos.

## 12. Riesgos de adopción

- Duplicación documental.
- Specs desactualizadas.
- Exceso de documentación para cambios pequeños.
- Contradicción entre frontend y backend.
- Refactorización prematura basada en reglas inferidas.
- Pérdida de una sola fuente de verdad por dominio.

## 13. Recomendaciones para AGENTS.md

El archivo actual ya está bien orientado, pero para SDD conviene reforzarlo con:

- orden de lectura obligatorio por dominio;
- regla de no crear specs duplicadas sin declarar la canónica;
- obligación de registrar cambios relevantes en `specs/14-current-state.md`;
- criterio para distinguir `docs/backlog` de `specs` canónicos;
- regla de sincronización con `docs/contracts` o Postman;
- prohibición de inferir reglas de negocio sin validación humana cuando haya ambigüedad.

## 14. Plan de trabajo propuesto

1. Crear una capa canónica de specs por dominio.
   - Dependencia: ninguna.
   - Riesgo: medio.
   - Fin: cada dominio crítico tiene su spec base.
2. Consolidar la referencia HTTP.
   - Dependencia: decisión sobre si Postman, `docs/contracts` o ambos.
   - Riesgo: alto.
   - Fin: una sola referencia operativa clara.
3. Definir trazabilidad mínima.
   - Dependencia: estructura de specs.
   - Riesgo: medio.
   - Fin: requisito, historia, prueba y contrato enlazables.
4. Formalizar el flujo `changes/`.
   - Dependencia: estructura base.
   - Riesgo: medio.
   - Fin: cambios grandes con propuesta, tareas y evidencia.
5. Recién después, refinar automatización.
   - Dependencia: trazabilidad estable.
   - Riesgo: bajo.
   - Fin: menos drift entre documentación y código.

## 15. Decisiones pendientes

1. Si la referencia de API canónica seguirá siendo Postman o pasará a OpenAPI interactivo.
2. Si los contratos históricos en `http/` se mantienen como ejemplos o se archivan.
3. Si el repo frontend consumirá la misma spec canónica por dominio o una vista derivada.
4. Si se formaliza `changes/` desde ya o solo para cambios de alto impacto.
5. Si `specs/04-api.md` se convierte en documento histórico o se fusiona con `specs/16-api-reference.md`.

## Conclusión

PlayerTech ya tiene buena base para SDD, pero hoy el principal problema no es falta de documentación, sino exceso de fuentes parciales. El siguiente paso útil no es reescribir el sistema, sino ordenar la canonicidad: una fuente por dominio, una referencia HTTP operativa, y trazabilidad explícita entre requisitos, tests y código.
