# Project Memory

Este documento consolida el contexto tecnico persistente de PlayerTech para que nuevas sesiones no dependan del historial del chat.

## Snapshot

- Producto: SaaS multi-tenant para academias de futbol.
- Backend: Symfony 7.4 en PHP 8.4.
- Persistencia: Doctrine ORM + MySQL 8+.
- Autenticacion: Symfony Security + JWT stateless.
- Arquitectura: monolito modular con capas `Domain`, `Application`, `Infrastructure` y `Presentation`.
- Base operativa HTTP: `/api/v1`.

## Fuentes Canonicas

- [`README.md`](../../README.md)
- [`AGENTS.md`](../../AGENTS.md)
- [`specs/14-current-state.md`](../../specs/14-current-state.md)
- [`specs/12-execution-order.md`](../../specs/12-execution-order.md)
- [`specs/01-arquitecture.md`](../../specs/01-arquitecture.md)
- [`specs/03-security.md`](../../specs/03-security.md)
- [`specs/04-api.md`](../../specs/04-api.md)
- [`specs/06-database.md`](../../specs/06-database.md)
- [`specs/11-testing-strategy.md`](../../specs/11-testing-strategy.md)
- [`specs/15-module-creation-guide.md`](../../specs/15-module-creation-guide.md)
- [`specs/16-api-reference.md`](../../specs/16-api-reference.md)
- [`specs/17-environment-guide.md`](../../specs/17-environment-guide.md)
- [`docs/architecture/*`](./)
- [`docs/domain/02-domains.md`](../domain/02-domains.md)
- [`docs/database/*`](../database/)
- [`docs/product/*`](../product/)
- [`docs/backlog/*`](../backlog/)

## Architectural Decisions

- El sistema es un monolito modular y no microservicios.
- La separacion entre `Domain`, `Application`, `Infrastructure` y `Presentation` es obligatoria.
- El dominio no debe depender de Symfony ni Doctrine.
- La validacion de entrada debe vivir en `Presentation`, no en `Application`.
- La validacion de entrada de `Application` se formaliza en `Presentation` mediante Request Objects y Symfony Validator.
- Los handlers de `Application` deben delegar recuperacion de entidades a Finder Services cuando exista repeticion.
- Los Finder Services pertenecen a `Application` y centralizan recuperacion, existencia y excepciones de no encontrado.
- Los errores HTTP deben traducirse a Problem Details.
- Las excepciones de dominio comunes heredan de una jerarquia compartida en `Shared` para reducir acoplamiento entre infraestructura y modulos.
- El aislamiento multi-tenant se resuelve por `academy_id`, JWT y filtro global de Doctrine.
- `ROLE_ROOT` opera en contexto de plataforma y no debe tratarse como tenant.
- Las entidades de negocio usan soft delete y auditoria.
- Doctrine usa XML mapping para los módulos funcionales.
- Los IDs de agregado usan UUID con custom types.
- Los listados deben converger hacia un contrato paginado uniforme con `data` + `meta`.
- Los endpoints de listado deben exponer `page`, `per_page`, `sort` y `direction` como contrato estable de API.
- En `Player`, el contrato de listado debe exponer `photo`, `categoryName`, `genderName`, `age` y `createdAt`; para filtros operativos se usan `gender`, `categoryId`, `createdAtFrom`, `createdAtTo`, `birthDateFrom` y `birthDateTo`, donde el rango de nacimiento es el filtro canónico para criterios de edad y `age` solo es derivado de salida.

## Estado Real Del Codigo

### Infraestructura base

- Existe `Kernel.php` con carga de `config/packages` y rutas por atributos.
- `services.yaml` registra repositorios por contrato y subscribers globales.
- `security.yaml` usa JWT, `json_login` y un proveedor Doctrine para `AccountUser`.
- Hay subscribers para auditoria, CORS, problema HTTP y contexto de tenant.
- Hay soporte para almacenamiento local de media.
- El runtime operativo validado depende de Docker; la base de trabajo debe considerarse el contenedor `app`, no PHP local del host.
- El stack de desarrollo validado incluye `app`, `mysql` y `mailpit`.
- El health check HTTP responde correctamente en `/api/v1/health`.
- El catalogo publico de onboarding puede repararse con `app:category:seed-onboarding`.

### Modulos presentes

- Academy
- Category
- Charge
- Dashboard
- Guardian
- Identity
- Membership
- Payment
- PaymentConcept
- Player
- Staff
- Team
- TeamAssignment
- Venue

### Modulos mas maduros

- `Academy` sirve como referencia de CQRS, XML mapping, VO tipados y flujo tenant/platform.
- `Identity` concentra autenticion, usuarios, `ROLE_ROOT`, contexto tenant y reglas de acceso.
- `Player`, `Team`, `Category`, `Venue`, `Membership`, `PaymentConcept`, `Charge`, `Payment`, `Staff` y `TeamAssignment` ya tienen estructura funcional y pruebas.

### Capas y patrones observados

- Controllers delgados que delegan a handlers.
- Commands y Queries en Application.
- Repositorios como contratos en Domain e implementaciones en Infrastructure.
- XML mappings por modulo.
- DTOs de request en Presentation para validacion.
- DTOs de respuesta compuestos para serializacion.
- Custom Doctrine Types para IDs UUID.
- Finder Services en Application para centralizar recuperacion de agregados.

## Contratos Funcionales Vigentes

- Login JWT en `/api/v1/auth/login`.
- `/api/v1/auth/me` para identidad autenticada.
- Contexto plataforma en `/api/v1/platform/*`.
- Contexto tenant en `/api/v1/academy/*`.
- Recursos publicos bajo `/api/v1/public/*`.
- Catalogos y listados con pagina, orden y metadatos.
- Flujo de tenant signup con activacion por correo.
- El equipo inicial del onboarding usa un response especifico para el alta y no reutiliza el contrato operativo de `Team`.
- CRUD y estado para Academy, Category, Venue, Team, Player, Staff y otros slices ya implementados.
- La API publica de onboarding incluye `GET /api/v1/public/categories`.
- El flujo de arranque del tenant valida migraciones, seed y health antes de considerar el entorno listo.

## Estado Del Dominio

- El modelo es player-centric: el jugador es el centro operativo.
- `Academy` gobierna el aislamiento multi-tenant.
- `Category` clasifica por rango de edad.
- `Team` organiza competencia, no entrenamiento permanente.
- `PlayerGuardian` modela la relacion jugador-acudiente.
- `Membership` controla permanencia administrativa.
- `TeamAssignment` controla historial deportivo.
- El bloque financiero separa `PaymentConcept`, `Charge`, `Payment` y `PaymentAllocation`.

## Testing

- Unit tests: reglas puras, value objects, entidades y handlers sin infraestructura.
- Integration tests: repositorios, mappings, filtros tenant y constraints.
- Functional tests: HTTP, auth, autorizacion y contratos API.
- La base de test debe ser `*_test`, no la base local.

## Convenciones De Trabajo

- No asumir reglas que no esten documentadas o implementadas.
- No mezclar refactor grande con cambio funcional innecesario.
- Mantener trazabilidad en `specs/14-current-state.md` para cambios relevantes.
- Tomar `Academy` como referencia tecnica para nuevos modulos.
- Priorizar `specs/16-api-reference.md` y `postman/` para contratos HTTP vigentes.

## Riesgos Y Deudas

- Hay desalineacion historica entre algunos documentos y el codigo real; la verdad operativa debe tomarse del estado actual y de los contratos HTTP vigentes.
- El contrato de paginacion uniforme esta documentado como estandar, pero algunos listados pueden seguir en transicion.
- La documentacion de nivel `specs` y `docs` debe seguir sincronizada para evitar duplicidad.
- El historial de migraciones antiguas puede contener deuda tecnica que afecta comandos globales de migracion.
- Algunos archivos de log o cache dentro del volumen Docker pueden requerir correccion de ownership si el contenedor se recrea o si el volumen conserva artefactos con dueño `root`.

## Regla De Lectura Para Futuras Sesiones

1. Leer `README.md`.
2. Leer `specs/14-current-state.md`.
3. Leer `specs/12-execution-order.md`.
4. Leer `specs/01-arquitecture.md`, `specs/03-security.md`, `specs/04-api.md` y `specs/06-database.md`.
5. Usar `docs/architecture/project-memory.md` como resumen persistente.
6. Verificar `specs/16-api-reference.md` y `postman/` antes de tocar contratos HTTP.
7. Confirmar el estado operativo dentro de Docker antes de asumir que el host refleja el runtime real.
