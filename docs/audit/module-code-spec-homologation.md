# Module Code / Spec Homologation

## Objetivo

Homologar lo que ya existe en código con su huella documental en `specs/` y
`docs/backlog/`, para que cada módulo tenga una trazabilidad clara entre:

- implementación real;
- feature spec canónica;
- épicas e historias del backlog;
- estado actual del proyecto.

## Criterios De Evaluación

- **Aligned**: el módulo ya tiene spec canónica, backlog razonablemente coherente
  y el código refleja el contrato principal.
- **Partially aligned**: el módulo existe en código y spec, pero aún hay detalles
  por cerrar en contratos, trazabilidad o naming.
- **Needs final pass**: el módulo está implementado, pero todavía conviene
  revisar alguna zona funcional o documental para evitar divergencias.

## Mapa Transversal

| Module | Spec | Backlog | Code | Status | Notes |
| --- | --- | --- | --- | --- | --- |
| Academy | `specs/001-academy/` | `EP-001` | `app/src/Modules/Academy/` | Aligned | El módulo raíz ya está bastante maduro y sirve como referencia técnica base del proyecto; la trazabilidad histórica sigue siendo amplia pero consistente. |
| Venue | `specs/002-venue/` | `EP-002` | `app/src/Modules/Venue/` | Aligned | La épica y la implementación están alineadas; queda como catálogo operativo de sedes con lifecycle claro. |
| Identity | `specs/003-identity/` | `EP-003` | `app/src/Modules/Identity/` | Partially aligned | Ya quedó normalizado documentalmente, pero sigue siendo el módulo con más variantes de auth/admin/root/tenant y merece mantener una revisión fina. |
| Category | `specs/004-category/` | `EP-004` | `app/src/Modules/Category/` | Aligned | Contrato, category key, listados y options están bien homologados. |
| Team | `specs/005-team/` | `EP-005` | `app/src/Modules/Team/` | Aligned | CRUD tenant-scoped estable con enriquecimiento de categoría resuelto. |
| Guardian | `specs/006-legal-guardian-management/` | `EP-006` | `app/src/Modules/Guardian/` | Aligned | Dominio de acudientes bien acotado. |
| Player | `specs/007-player/` | `EP-007` | `app/src/Modules/Player/` | Partially aligned | El módulo ya cubre el ciclo base, la foto, el estado y el import, pero sigue siendo el foco más cambiante y merece auditoría continua de contrato. |
| PlayerGuardian | `specs/008-player-guardian/` | `EP-008` | `app/src/Modules/Player/` | Aligned | La relación jugador-acudiente está correctamente segmentada. |
| Membership | `specs/009-membership-management/` | `EP-009-membership-management` | `app/src/Modules/Membership/` | Partially aligned | Ya quedó ordenado a nivel backlog, pero depende fuertemente del bloque financiero y del cumplimiento de los estados administrativos. |
| TeamAssignment | `specs/010-team-assignment/` | `EP-010` | `app/src/Modules/TeamAssignment/` | Aligned | Historial y asignación deportiva están bien alineados. |
| PaymentConcept | `specs/011-payment-concept/` | `EP-011` | `app/src/Modules/PaymentConcept/` | Aligned | Catálogo financiero aislado, bien documentado y con código automático. |
| Charge | `specs/012-charge-payment/` | `EP-012` | `app/src/Modules/Charge/` | Partially aligned | El dominio está bien reflejado, pero conviene vigilar que la frontera con `Membership` y `Payment` siga clara. |
| Payment | `specs/012-charge-payment/` + `specs/023-fiscal/` | `EP-012` + `EP-023` | `app/src/Modules/Payment/` | Partially aligned | El módulo ya existe y funciona, pero su documentación está repartida entre operación financiera y comprobantes fiscales; eso exige mantenerlo explicitado. |
| Dashboard | `specs/013-dashboard/` | `EP-013` | `app/src/Modules/Dashboard/` | Partially aligned | Es un agregador dependiente de los dominios base; documentalmente está bien, pero su calidad depende del resto. |
| Tenant Onboarding | `specs/014-tenant-onboarding/` | `EP-014` | `app/src/Modules/Academy/`, `app/src/Modules/Identity/` | Partially aligned | Está implementado como flujo distribuido entre Academy e Identity; la frontera ya es entendible, pero sigue siendo un slice cross-module. |
| Staff | `specs/021-staff/` | `EP-021` | `app/src/Modules/Staff/` | Aligned | Selector, roles y asignación de staff ya se ven consistentes. |
| Sport Mode | `specs/022-sport-mode/` | `EP-022` | No module folder explícito aún | Needs final pass | La épica está definida, pero aún no está consolidada como feature productiva equivalente a los demás módulos. |
| Fiscal | `specs/023-fiscal/` | `EP-023` | `app/src/Modules/Payment/` + perfil fiscal en Academy | Partially aligned | El caso fiscal está funcionando, pero todavía conviene decidir si se materializa como módulo propio o como extensión explícita de finanzas/academy. |

## Estado MVP Por Módulo

Esta tabla resume el estado operativo actual del MVP y sirve como referencia
rápida para saber qué dominios ya cuentan como base del producto.

| Módulo | Estado MVP | Lectura Ejecutiva |
| --- | --- | --- |
| Academy | Base MVP | Núcleo tenant/root y referencia arquitectónica principal. |
| Identity | Base MVP | Autenticación, usuarios, contexto tenant y seguridad central. |
| Category | Base MVP | Catálogo funcional estable y usado por varios flujos. |
| Venue | Base MVP | CRUD operativo de sedes y lifecycle completo. |
| Team | Base MVP | CRUD deportivo base con catálogo de categorías y options. |
| Guardian | Base MVP | Gestión de acudientes, relaciones y selectors de soporte. |
| Player | Base MVP | Núcleo del modelo, CRUD, show, foto, filtros e importación. |
| PlayerGuardian | Base MVP | Relación jugador-acudiente con principal y desvinculación. |
| TeamAssignment | Base MVP | Asignación deportiva, principal, historial y selector contextual. |
| Membership | Base MVP | Matrícula del jugador y permanencia administrativa. |
| PaymentConcept | Base MVP | Catálogo financiero estable para cobros. |
| Charge | Base MVP | Cargos operativos del flujo financiero. |
| Payment | Base MVP | Recaudo y evidencia del bloque financiero. |
| Staff | Base MVP | Selector y asignación de staff funcional. |
| Dashboard | MVP dependiente | Agregador operativo que depende del resto de módulos. |
| Tenant Onboarding | Base MVP | Alta pública de tenant con activación. |
| Sport Mode | Pendiente de consolidación | Sigue como bloque por cerrar si el negocio lo exige. |
| Fiscal | MVP extendido | Parte del bloque financiero, pero aún con frontera de dominio a decidir. |

## Matriz De Prioridad Para Continuar

Esta tabla ordena los módulos del MVP según impacto operativo, riesgo de cambio
y utilidad inmediata para el frontend y el negocio.

| Prioridad | Módulo | Por qué ahora | Riesgo de tocarlo |
| --- | --- | --- | --- |
| 1 | Player | Es el centro del modelo operativo y concentra CRUD, importación, relaciones y contratos enriquecidos. | Alto |
| 2 | Guardian | Es la contraparte directa del jugador y sigue siendo clave para relaciones, filtros y desvinculación. | Alto |
| 3 | TeamAssignment | Sostiene la operación deportiva base y aún puede recibir reglas de negocio nuevas. | Alto |
| 4 | Membership | Define la matrícula y la permanencia administrativa del jugador; impacta el bloque financiero. | Medio |
| 5 | Payment / Charge / PaymentConcept | Cierra el circuito financiero base del MVP y evita divergencias entre catálogo, cargos y recaudos. | Medio-Alto |
| 6 | Team | Ya está sólido, pero puede requerir ajuste fino si cambian reglas de asignación o temporadas. | Medio |
| 7 | Academy / Identity | Son raíz de autenticación y tenant; sólo conviene tocarlos si aparece una brecha estructural. | Alto |
| 8 | Dashboard | Depende del resto y no aporta una nueva capacidad primaria por sí mismo. | Medio |
| 9 | Venue / Category / Staff | Ya están bastante estables; sólo requieren cambios si surge nueva demanda funcional. | Bajo |
| 10 | Sport Mode | Debe esperar a tener una definición de negocio realmente cerrada. | Alto |
| 11 | Fiscal | Puede seguir dentro del bloque financiero, pero antes conviene cerrar su frontera exacta. | Medio |

## Recomendación De Continuidad

Si el objetivo es avanzar por el siguiente frente de mayor valor, la ruta
recomendada es:

1. `Player`
2. `Guardian`
3. `TeamAssignment`

Si el objetivo es cerrar el MVP administrativo/financiero, la secuencia
recomendada es:

1. `Membership`
2. `PaymentConcept`
3. `Charge`
4. `Payment`

Si el objetivo es reducir deuda de plataforma, la ruta es:

1. `Academy`
2. `Identity`

## Observaciones Clave

### 1. Módulos Ya Bien Alineados

- `Category`
- `Team`
- `Guardian`
- `PlayerGuardian`
- `TeamAssignment`
- `PaymentConcept`
- `Staff`

Estos módulos ya tienen una relación bastante estable entre backend, backlog y
spec.

### 2. Módulos Que Necesitan Una Pasada Final

- `Academy`
- `Venue`
- `Identity`
- `Player`
- `Membership`
- `Charge / Payment`
- `Dashboard`
- `Tenant Onboarding`
- `Fiscal`

Aquí la brecha no es ausencia de documentación, sino necesidad de mantener la
frontera exacta entre lo que ya está implementado y lo que quedó como evolución.

### 3. Módulo Que Todavía No Está Totalmente Consolidado Como Feature Independiente

- `Sport Mode`

La épica existe, pero todavía no se traduce en una pieza de código con la misma
fuerza que el resto de módulos productivos.

## Reglas De Homologación

1. Si el código ya existe y el contrato está claro, el spec manda.
2. Si el código existe pero la intención está duplicada, el backlog se archiva o
   se consolida.
3. Si el módulo está distribuido entre varios dominios, la trazabilidad debe
   decirlo explícitamente.
4. Ningún módulo debe tener dos fuentes canónicas compitiendo por el mismo
   comportamiento.

## Siguiente Trabajo Recomendado

1. Revisar `Player` como primer módulo con mayor densidad de cambios recientes.
2. Revisar `Academy` e `Identity` como raíz de tenant/root.
3. Cerrar `Charge / Payment` contra `EP-012` y `EP-023`.
4. Decidir el destino técnico final de `Sport Mode`.
