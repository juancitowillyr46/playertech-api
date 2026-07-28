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
| Guardian | `specs/006-guardian/` | `EP-006` | `app/src/Modules/Guardian/` | Aligned | Dominio de acudientes bien acotado. |
| Player | `specs/007-player/` | `EP-007` | `app/src/Modules/Player/` | Partially aligned | El módulo ya cubre el ciclo base, la foto, el estado y el import, pero sigue siendo el foco más cambiante y merece auditoría continua de contrato. |
| PlayerGuardian | `specs/008-player-guardian/` | `EP-008` | `app/src/Modules/Player/` | Aligned | La relación jugador-acudiente está correctamente segmentada. |
| Membership | `specs/009-membership/` | `EP-009` | `app/src/Modules/Membership/` | Partially aligned | Ya quedó ordenado a nivel backlog, pero depende fuertemente del bloque financiero y del cumplimiento de los estados administrativos. |
| TeamAssignment | `specs/010-team-assignment/` | `EP-010` | `app/src/Modules/TeamAssignment/` | Aligned | Historial y asignación deportiva están bien alineados. |
| PaymentConcept | `specs/011-payment-concept/` | `EP-011` | `app/src/Modules/PaymentConcept/` | Aligned | Catálogo financiero aislado, bien documentado y con código automático. |
| Charge | `specs/012-charge-payment/` | `EP-012` | `app/src/Modules/Charge/` | Partially aligned | El dominio está bien reflejado, pero conviene vigilar que la frontera con `Membership` y `Payment` siga clara. |
| Payment | `specs/012-charge-payment/` + `specs/023-fiscal/` | `EP-012` + `EP-023` | `app/src/Modules/Payment/` | Partially aligned | El módulo ya existe y funciona, pero su documentación está repartida entre operación financiera y comprobantes fiscales; eso exige mantenerlo explicitado. |
| Dashboard | `specs/013-dashboard/` | `EP-013` | `app/src/Modules/Dashboard/` | Partially aligned | Es un agregador dependiente de los dominios base; documentalmente está bien, pero su calidad depende del resto. |
| Tenant Onboarding | `specs/014-tenant-onboarding/` | `EP-014` | `app/src/Modules/Academy/`, `app/src/Modules/Identity/` | Partially aligned | Está implementado como flujo distribuido entre Academy e Identity; la frontera ya es entendible, pero sigue siendo un slice cross-module. |
| Staff | `specs/021-staff/` | `EP-021` | `app/src/Modules/Staff/` | Aligned | Selector, roles y asignación de staff ya se ven consistentes. |
| Sport Mode | `specs/022-sport-mode/` | `EP-022` | No module folder explícito aún | Needs final pass | La épica está definida, pero aún no está consolidada como feature productiva equivalente a los demás módulos. |
| Fiscal | `specs/023-fiscal/` | `EP-023` | `app/src/Modules/Payment/` + perfil fiscal en Academy | Partially aligned | El caso fiscal está funcionando, pero todavía conviene decidir si se materializa como módulo propio o como extensión explícita de finanzas/academy. |

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
