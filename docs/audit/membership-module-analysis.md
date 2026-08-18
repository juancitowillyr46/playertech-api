# Membership Module Analysis

## Fecha

2026-08-18

## Objetivo

Dejar trazabilidad clara del estado real del módulo `Membership` comparando:

- la épica `EP-009-membership-management`;
- las historias de usuario bajo `docs/backlog/stories/EP-009-membership-management/`;
- el spec `specs/009-membership-management/`;
- el código actualmente implementado.

## Lectura Ejecutiva

`Membership` ya existe como módulo funcional en backend y no debe tratarse como un slice pendiente.
Hoy cubre el ciclo administrativo base de matrícula: crear, consultar matrícula activa, consultar historial, suspender y retirar.

Lo que todavía no está completamente alineado es la narrativa del backlog, porque la épica sigue hablando de cargos iniciales, pagos y evidencia, mientras el código actual del módulo `Membership` se concentra sobre todo en la matrícula y su ciclo de estado.

## Estado Real Del Código

### Endpoints implementados

- `POST /api/v1/academy/memberships`
- `GET /api/v1/academy/memberships/{playerId}/active`
- `GET /api/v1/academy/memberships/{playerId}/history`
- `PATCH /api/v1/academy/memberships/{playerId}/suspend`
- `PATCH /api/v1/academy/memberships/{playerId}/withdraw`

### Flujo soportado por código

- crear matrícula con `playerId` y `primaryGuardianId`;
- bloquear la creación si ya existe una matrícula activa por jugador en la academia;
- consultar matrícula activa;
- consultar historial de matrículas;
- suspender matrícula;
- retirar matrícula.

### Lo que el código no muestra todavía dentro del módulo

- generación explícita de cargos iniciales dentro de `Membership`;
- consulta de saldo/deuda dentro del mismo módulo;
- evidencia de pago dentro del mismo módulo;
- una entidad operativa de cargo o pago dentro del namespace `Membership`.

## Mapa De Dependencias Y Funcionalidades

### Dependencias Funcionales

| Dependencia | Por qué existe | Relación con Membership |
| --- | --- | --- |
| `Player` | La matrícula siempre pertenece a un jugador concreto. | `Membership` requiere `playerId` y no puede existir sin jugador válido en el tenant. |
| `Guardian` | La matrícula exige un acudiente principal responsable. | `Membership` recibe `primaryGuardianId` y la validez del acudiente es parte del alta. |
| `Academy` | La matrícula debe quedar aislada por academia. | Todo el ciclo de matrícula está scoped por `academyId` desde `TenantContext`. |
| `Charge` | Los cargos iniciales y la deuda viven en el bloque financiero. | `Membership` no gestiona cargos directamente en el código actual, pero la épica los relaciona. |
| `Payment` | El recaudo y la deuda visible dependen de pagos asociados. | El historial financiero y el saldo se entienden junto a `Payment`, no sólo desde `Membership`. |
| `PaymentConcept` | Define el motivo o categoría del cobro. | La épica de matrícula y cargos lo considera parte del arranque financiero. |

### Dependencias Técnicas

| Dependencia | Rol técnico |
| --- | --- |
| `TenantContext` | Aísla la matrícula al tenant autenticado. |
| `Security` | Resuelve el actor autenticado que crea/suspende/retira. |
| `Validator` | Valida `CreateMembershipRequest` en Presentation. |
| `MembershipFinder` | Centraliza la búsqueda de matrícula activa y evita duplicación. |
| `MembershipRepository` | Persiste y consulta el lifecycle de `Membership`. |

### Funcionalidades Por Endpoint

| Endpoint | Funcionalidad | Lectura de negocio |
| --- | --- | --- |
| `POST /api/v1/academy/memberships` | Crear matrícula | Registra la vinculación administrativa del jugador con su acudiente principal. |
| `GET /api/v1/academy/memberships/{playerId}/active` | Consultar matrícula activa | Permite saber si el jugador pertenece actualmente a la academia. |
| `GET /api/v1/academy/memberships/{playerId}/history` | Consultar historial | Devuelve las matrículas históricas del jugador. |
| `PATCH /api/v1/academy/memberships/{playerId}/suspend` | Suspender matrícula | Pausa temporalmente la vinculación administrativa. |
| `PATCH /api/v1/academy/memberships/{playerId}/withdraw` | Retirar matrícula | Cierra definitivamente la matrícula sin borrar trazabilidad. |

### Funcionalidades Por HU

| HU | Qué cubre | Estado real frente al código |
| --- | --- | --- |
| `HU-001-create-membership.md` | Crear matrícula con acudiente principal | Cubierta por `POST /api/v1/academy/memberships`. |
| `HU-002-generate-initial-charges.md` | Generar cargos iniciales al crear matrícula | Relacionada con la épica, pero no materializada dentro del módulo `Membership` actual. |
| `HU-003-view-active-membership.md` | Consultar matrícula activa | Cubierta por `GET /api/v1/academy/memberships/{playerId}/active`. |
| `HU-004-register-payment.md` | Registrar pago sobre matrícula | Pertenece al bloque financiero y debe leerse junto a `Payment`. |
| `HU-005-view-membership-history.md` | Consultar historial | Cubierta por `GET /api/v1/academy/memberships/{playerId}/history`. |
| `HU-006-suspend-membership.md` | Suspender matrícula | Cubierta por `PATCH /api/v1/academy/memberships/{playerId}/suspend`. |
| `HU-007-view-balance.md` | Consultar saldo o deuda pendiente | Pertenece al bloque financiero y requiere `Charge` / `Payment`. |
| `HU-008-withdraw-membership.md` | Retirar matrícula | Cubierta por `PATCH /api/v1/academy/memberships/{playerId}/withdraw`. |

### Funcionalidades Por Spec

El spec `specs/009-membership-management/` define la intención de un feature más amplio de
matrícula y cargos iniciales. El código actual cubre con claridad estos
escenarios:

- creación de matrícula activa;
- consulta de matrícula activa;
- historia de matrículas;
- suspensión;
- retiro.

Y deja como dependencias o extensiones del bloque financiero, manteniendo la
referencia documental para no perder el vínculo de producto:

- generación de cargos iniciales;
- consulta de saldo/deuda;
- registro y lectura del pago;
- evidencia de pago.

## Alineación Con La Épica

### Lo que sí coincide

- una sola matrícula activa por jugador y academia;
- matrícula histórica múltiple;
- relación con acudiente principal;
- historial y transiciones de estado;
- suspensión y retiro sin borrar trazabilidad.

### Lo que está mezclado en la épica

La épica `EP-009-membership-management` agrupa tres capas de negocio:

1. matrícula administrativa;
2. impacto financiero inicial;
3. visibilidad operativa de pagos y saldo.

El código actual del módulo `Membership` cubre con claridad el punto 1 y parte del punto 3 por lectura histórica, pero el bloque financiero todavía vive conceptualmente separado en `PaymentConcept`, `Charge` y `Payment`.

## Estado De Las HUs

### HUs que sí encajan con el código actual

- Crear matrícula con acudiente principal.
- Consultar matrícula activa.
- Consultar historial de matrícula.
- Suspender matrícula.
- Retirar matrícula.

### HUs que hoy quedan conceptualmente fuera del módulo estricto

- Generar cargos iniciales.
- Registrar pago sobre matrícula.
- Consultar saldo o deuda pendiente.
- Cualquier lógica de evidencia o conciliación financiera.

Estas historias siguen siendo válidas para el producto, pero ya no deberían leerse como responsabilidad directa de `Membership` sin referencia a `Charge` y `Payment`.

## Estado Del Spec

El spec `specs/009-membership-management/` todavía mezcla inglés, español y términos que deberían refinarse.
A nivel de intención, la feature sí refleja lo que el código hace para la matrícula administrativa.
A nivel documental, conviene separar con más claridad:

- matrícula administrativa;
- side effects financieros;
- lectura histórica y status transitions.

## Tabla De Cierre

| Estado | Elementos |
| --- | --- |
| Implementado | `POST /api/v1/academy/memberships`, `GET /api/v1/academy/memberships/{playerId}/active`, `GET /api/v1/academy/memberships/{playerId}/history`, `PATCH /api/v1/academy/memberships/{playerId}/suspend`, `PATCH /api/v1/academy/memberships/{playerId}/withdraw` |
| Implementado con tests | `CreateMembershipHandlerTest`, `ShowActiveMembershipHandlerTest`, `ShowMembershipHistoryHandlerTest`, `SuspendMembershipHandlerTest`, `WithdrawMembershipHandlerTest` |
| Documentado y alineado | `specs/009-membership-management/spec.md`, `plan.md`, `research.md`, `data-model.md`, `quickstart.md`, `contracts/README.md` |
| Documentado pero todavía por afinar | Narrativa financiera en backlog, tasks pendientes de homologación, mezcla de idiomas en algunos archivos auxiliares |
| Referencia financiera que debe conservarse | `PaymentConcept`, `Charge`, `Payment`, saldo, evidencia y conciliación |

## Conclusiones

1. `Membership` ya está implementado como módulo funcional.
2. El backlog de `EP-009-membership-management` debe leerse con cuidado porque su narrativa financiera es más amplia que el alcance del código actual de `Membership`.
3. `Charge` y `Payment` deben verse como módulos vecinos del bloque financiero, no como detalles internos de `Membership`.
4. Si se sigue trabajando `Membership`, el siguiente paso correcto es limpiar su spec y backlog para que no compita con `PaymentConcept`, `Charge` y `Payment`.
5. La reorganización ya aplicada deja a `Membership` como core administrativo y mueve la narrativa financiera a una dependencia explícita del bloque financiero.
6. La referencia financiera debe permanecer viva en el backlog y en los contratos, aunque la lógica viva en `PaymentConcept`, `Charge` y `Payment`.

## Recomendación De Trabajo

### Corto plazo

- Homologar `EP-009-membership-management` para que la narrativa de matrícula quede separada del bloque financiero.
- Corregir el orden y la numeración de las HUs, porque la carpeta actual mezcla IDs y títulos.
- Alinear el spec con el contrato HTTP real del módulo.

### Lectura Prioritaria Para Continuar

Si el foco sigue siendo `Membership`, la secuencia lógica es:

1. limpiar la épica para que `Membership` no compita con `Payment`;
2. ordenar las HUs por responsabilidad real;
3. decidir si cargos iniciales y saldo deben vivir dentro de `EP-009-membership-management` o
   documentarse como dependencias financieras vecinas;
4. alinear `specs/009-membership-management/` con los endpoints que realmente existen hoy.

### Mediano plazo

- Si el negocio quiere que `Membership` también sea el punto de entrada a cargos iniciales, entonces documentar esa relación explícitamente con `Charge` y `Payment`.
- Si no, dejar `Membership` sólo como vínculo administrativo y mover la narrativa financiera a los módulos de cobro.

## Trazabilidad

Este análisis debe mantenerse sincronizado con:

- `docs/backlog/epics/EP-009-membership-management.md`
- `docs/backlog/stories/EP-009-membership-management/`
- `specs/009-membership-management/`
- `specs/14-current-state.md`
- `docs/architecture/memory/project-memory.md`
