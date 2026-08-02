# Backlog Spec Kit Classification

Este documento clasifica el backlog actual para ubicar cada épica en la estructura
de GitHub Spec Kit y reducir ambigüedad entre backlog, features y specs.

## Regla General

- `docs/backlog/` conserva intención y priorización.
- `specs/[###-feature]/` conserva la especificación canónica de cada feature.
- Una épica puede derivar en una o varias features si mezcla dominios o flujos.
- Las HUs duplicadas o renombradas deben consolidarse antes de crear nuevas specs.

## Clasificación Por Épica

### EP-001 - Gestión de Academias

- Tipo: feature raíz de dominio.
- Carpeta sugerida: `specs/001-academy/` o equivalente canónico de academy.
- Subfeatures:
  - `academy-management`
  - `academy-shield`
  - `tenant-provisioning`
  - `tenant-onboarding-source`
  - `academy-profile`

### EP-002 - Gestión de Sedes

- Tipo: feature raíz de dominio.
- Carpeta sugerida: `specs/002-venue/`.

### EP-003 - Gestión de Usuarios y Accesos

- Tipo: feature raíz de identidad.
- Carpeta sugerida: `specs/003-identity/`.
- Subfeatures:
  - `auth-login`
  - `auth-me`
  - `admin-users`
  - `tenant-owner-bootstrap`
  - `password-reset`

### EP-004 - Gestión de Categorías

- Tipo: feature raíz de dominio.
- Carpeta sugerida: `specs/004-category/`.
- Nota: la clave de negocio de categoría debe tratarse como parte de esta feature,
  no como feature separada, salvo que cambie el contrato.

### EP-005 - Gestión de Equipos

- Tipo: feature raíz de dominio.
- Carpeta sugerida: `specs/005-team/`.

### EP-006 - Gestión de Acudientes

- Tipo: feature raíz de dominio.
- Carpeta sugerida: `specs/006-legal-guardian-management/`.

### EP-007 - Gestión de Jugadores

- Tipo: feature compuesta con varios flujos.
- Carpeta sugerida: `specs/007-player/`.
- Subfeatures:
  - `player-registration`
  - `player-listing`
  - `player-detail`
  - `player-update`
  - `player-status`
  - `player-photo`
  - `player-import`
  - `player-contract-enrichment`

### EP-008 - Relaciones Jugador-Acudiente

- Tipo: feature relacional.
- Carpeta sugerida: `specs/008-player-guardian/`.

### EP-009 - Matrículas y Cargos Iniciales

- Tipo: feature compuesta.
- Carpeta sugerida: `specs/009-membership/`.
- Subfeatures:
  - `membership-create`
  - `membership-active-view`
  - `membership-history`
  - `membership-status`
  - `initial-charges`

### EP-010 - Asignaciones Deportivas

- Tipo: feature relacional.
- Carpeta sugerida: `specs/010-team-assignment/`.

### EP-011 - Conceptos de Pago

- Tipo: feature de catálogo financiero.
- Carpeta sugerida: `specs/011-payment-concept/`.

### EP-012 - Cargos y Pagos

- Tipo: feature financiera compuesta.
- Carpeta sugerida: `specs/012-charge-payment/`.

### EP-013 - Dashboard Operativo

- Tipo: feature de agregación/consulta.
- Carpeta sugerida: `specs/013-dashboard/`.

### EP-014 - Alta de Tenant

- Tipo: feature de onboarding/plataforma.
- Carpeta sugerida: `specs/014-tenant-onboarding/`.

### EP-021 - Staff y Cuerpo Técnico

- Tipo: feature compuesta.
- Carpeta sugerida: `specs/021-staff/`.

### EP-022 - Modalidad Deportiva

- Tipo: feature de configuración futura.
- Carpeta sugerida: `specs/022-sport-mode/`.

### EP-023 - Información Fiscal y Soporte Fiscal

- Tipo: feature fiscal compuesta.
- Carpeta sugerida: `specs/023-fiscal/`.
- Subfeatures:
  - `tax-profile`
  - `receipt-generation`
  - `external-fiscal-document-link`

## Duplicados Y Conflictos En HUs

### Duplicados claros

- `EP-009/HU-004-attach-payment-evidence.md`
- `EP-012/HU-004-attach-payment-evidence.md`

Acción sugerida:

- decidir si pertenece a `membership/payment` o a `charge/payment`;
- dejar una sola HU canónica y archivar la otra como referencia histórica.

### HUs con naming inconsistente

- `EP-003` tiene varias historias duplicadas de usuario administrativo.
- `EP-004/HU-001-create-category.md` está mal nombrada y parece una HU de sede.
- `EP-009` mezcla “membresía”, “matrícula” y “cargos iniciales” en dos épicas diferentes.

## Lectura SDD Recomendada

1. El backlog sigue siendo la capa de intención.
2. Cada feature canónica debe nacer en `specs/[###-feature]/spec.md`.
3. Las HUs del backlog deben mapearse a una feature única.
4. Si una épica mezcla más de un flujo estable, debe dividirse.
5. Si una HU ya está implementada, debe reflejarse en `specs/14-current-state.md`.
