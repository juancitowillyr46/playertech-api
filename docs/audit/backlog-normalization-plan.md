# Backlog Normalization Plan

## Objetivo

Normalizar el backlog de PlayerTech para que cada épica tenga una representación
única, clara y trazable entre `docs/backlog/`, `specs/` y `docs/architecture/`.

## Principios

- Una HU canónica por intención de negocio.
- Un solo dueño por contrato o responsabilidad.
- Lo duplicado se archiva, no se borra de forma agresiva.
- Las épicas mezcladas se consolidan antes de dividirse en features nuevas.
- La verdad canónica vive en `specs/`.

## Acciones Prioritarias

### 1. EP-003 Usuarios y accesos

- `keep`
  - `HU-001-login.md`
  - `HU-002-logout.md`
  - `HU-004-resend-access-invitation.md`
  - `HU-005-activate-account-and-set-password.md`
  - `HU-006-update-administrative-user.md`
  - `HU-007-deactivate-administrative-user.md`
  - `HU-007-list-users.md`
  - `HU-008-reactivate-administrative-user.md`
  - `HU-009-view-administrative-users.md`
  - `HU-011-get-auth-me.md`
  - `HU-012-update-own-user-name.md`
  - `HU-013-password-reset.md`
- `merge`
  - `HU-003-create-user-administrative-by-invitation.md`
  - `HU-004-update-user.md`
  - `HU-005-disable-user.md`
  - `HU-006-enable-user.md`
  - `HU-008-create-tenant-owner.md`
  - `HU-009-sign-up-initial-team.md`
  - `HU-010-create-tenant-owner-admin.md`
- `archive`
  - `HU-003-create-user.md`

### 2. EP-007 Jugadores

- `keep`
  - `HU-001-register-player.md`
  - `HU-002-list-players.md`
  - `HU-003-view-player.md`
  - `HU-004-update-player.md`
  - `HU-005-player-status-management.md`
  - `HU-007-import-players-bulk.md`
  - `HU-008-category-business-key.md`
  - `HU-009-upload-player-photo.md`
- `merge`
  - `HU-006-activate-player.md`
  - `HU-005-disable-player.md`
- `archive`
  - `HU-005-disable-player.md` una vez absorbida por status management

### 3. EP-009 Matrículas y cargos iniciales

- `keep`
  - `HU-001-create-membership.md`
  - `HU-002-generate-initial-charges.md`
  - `HU-002-view-active-membership.md`
  - `HU-003-register-payment.md`
  - `HU-004-view-membership-history.md`
  - `HU-005-suspend-membership.md`
  - `HU-005-view-balance.md`
  - `HU-006-withdraw-membership.md`
- `merge`
  - `HU-004-attach-payment-evidence.md` con dueño único a definir frente a EP-012
- `archive`
  - `HU-003-view-active-membership.md`
  - `HU-006-view-membership-history.md`
  - `HU-007-suspend-membership.md`
  - `HU-008-withdraw-membership.md`

### 4. EP-012 Cargos y pagos

- `keep`
  - `HU-001-consult-pending-charges.md`
  - `HU-002-register-payment.md`
  - `HU-003-register-payment-method.md`
  - `HU-005-consult-payments.md`
  - `HU-006-cancel-payment.md`
  - `HU-007-consult-player-debt.md`
- `merge`
  - `HU-004-attach-payment-evidence.md` con EP-009 o como HU canónica única del bloque financiero

### 5. EP-002 Sedes

- `keep`
  - `HU-013-create-venue.md`
  - `HU-014-list-venues.md`
  - `HU-016-update-venue.md`
  - `HU-017-suspend-venue.md`
  - `HU-018-reactivate-venue.md`
  - `HU-019-extend-venue-contact-data.md`
- `rename`
  - `HU-015-view-academy-details.md` -> nombre correcto para detalle de sede

## Reglas de Ejecución

1. Primero decidir el dueño canónico de cada intención duplicada.
2. Luego renombrar archivos mal ubicados.
3. Después archivar duplicados históricos.
4. Finalmente reflejar el cierre en `specs/14-current-state.md` y `docs/architecture/memory/project-memory.md`.

## Resultado Esperado

- backlog sin duplicidad semántica relevante;
- HUs alineadas una a una con features canónicas;
- trazabilidad clara entre épica, HU, spec y estado actual.

