# Spec Kit Specs Folder Map

Este documento define la estructura exacta recomendada para `specs/` en PlayerTech
bajo GitHub Spec Kit.

## Objetivo

- organizar cada feature como una unidad SDD independiente;
- evitar que backlog, specs y código vuelvan a mezclarse;
- facilitar que cualquier agente entienda dónde vive cada contrato;
- mantener nombres estables, legibles y cercanos al dominio.

## Convención General

Cada feature vive en:

```text
specs/[###-feature-slug]/
```

Contenido estándar:

```text
specs/[###-feature-slug]/
├── spec.md
├── plan.md
├── research.md
├── data-model.md
├── quickstart.md
├── contracts/
└── tasks.md
```

## Estructura Recomendada Para PlayerTech

### Base canónica transversal

Mantener en la raíz de `specs/`:

- `00-product.md`
- `01-arquitecture.md`
- `02-domains.md`
- `03-security.md`
- `04-api.md`
- `06-database.md`
- `08-dev-standards.md`
- `09-roadmap.md`
- `10-project-setup.md`
- `11-testing-strategy.md`
- `12-execution-order.md`
- `13-user-story-rebuild-guide.md`
- `14-current-state.md`
- `15-module-creation-guide.md`
- `16-api-reference.md`
- `17-environment-guide.md`
- `18-financial-domain-model.md`
- `19-observability-local.md`

### Carpetas de feature propuestas

```text
specs/001-academy/
specs/002-venue/
specs/003-identity/
specs/004-category/
specs/005-team/
specs/006-legal-guardian-management/
specs/007-player/
specs/008-player-guardian/
specs/009-membership/
specs/010-team-assignment/
specs/011-payment-concept/
specs/012-charge-payment/
specs/013-dashboard/
specs/014-tenant-onboarding/
specs/021-staff/
specs/022-sport-mode/
specs/023-fiscal/
```

## Mapeo Por Épica

### EP-001 Gestión de Academias

Carpeta:

- `specs/001-academy/`

Subfeatures sugeridas:

- `academy-management`
- `academy-shield`
- `academy-profile`
- `tenant-provisioning`
- `tenant-registration-source`

### EP-002 Gestión de Sedes

Carpeta:

- `specs/002-venue/`

### EP-003 Gestión de Usuarios y Accesos

Carpeta:

- `specs/003-identity/`

Subfeatures sugeridas:

- `auth-login`
- `auth-me`
- `admin-users`
- `tenant-owner-bootstrap`
- `password-reset`

### EP-004 Gestión de Categorías

Carpeta:

- `specs/004-category/`

### EP-005 Gestión de Equipos

Carpeta:

- `specs/005-team/`

### EP-006 Gestión de Acudientes

Carpeta:

- `specs/006-legal-guardian-management/`

### EP-007 Gestión de Jugadores

Carpeta:

- `specs/007-player/`

Subfeatures sugeridas:

- `player-registration`
- `player-listing`
- `player-detail`
- `player-update`
- `player-status`
- `player-photo`
- `player-import`
- `player-refs`

### EP-008 Relaciones Jugador-Acudiente

Carpeta:

- `specs/008-player-guardian/`

### EP-009 Matrículas y Cargos Iniciales

Carpeta:

- `specs/009-membership/`

Subfeatures sugeridas:

- `membership-create`
- `membership-active`
- `membership-history`
- `membership-status`
- `initial-charges`

### EP-010 Asignaciones Deportivas

Carpeta:

- `specs/010-team-assignment/`

### EP-011 Conceptos de Pago

Carpeta:

- `specs/011-payment-concept/`

### EP-012 Cargos y Pagos

Carpeta:

- `specs/012-charge-payment/`

### EP-013 Dashboard Operativo

Carpeta:

- `specs/013-dashboard/`

### EP-014 Alta de Tenant

Carpeta:

- `specs/014-tenant-onboarding/`

### EP-021 Staff y Cuerpo Técnico

Carpeta:

- `specs/021-staff/`

### EP-022 Modalidad Deportiva de la Academia

Carpeta:

- `specs/022-sport-mode/`

### EP-023 Información Fiscal y Soporte Fiscal

Carpeta:

- `specs/023-fiscal/`

Subfeatures sugeridas:

- `tax-profile`
- `receipt-generation`
- `external-fiscal-document`

## Reglas De Clasificación

- Una carpeta de feature no debe contener múltiples features no relacionadas.
- Si una épica mezcla dos contratos estables, se divide en subfeatures.
- Si una HU ya corresponde a un contrato vigente, debe mapearse a una sola feature.
- Si un documento describe solo UX o apoyo visual, no debe vivir como feature canónica.
- Si un documento actual compite con otro, uno debe quedar como canónico y el otro como histórico o índice.

## Mapa De Prioridad De Migración

1. `specs/003-identity/`
2. `specs/001-academy/`
3. `specs/007-player/`
4. `specs/005-team/`
5. `specs/009-membership/`
6. `specs/012-charge-payment/`
7. `specs/004-category/`
8. `specs/006-legal-guardian-management/`
9. `specs/008-player-guardian/`
10. `specs/010-team-assignment/`
11. `specs/011-payment-concept/`
12. `specs/013-dashboard/`
13. `specs/014-tenant-onboarding/`
14. `specs/021-staff/`
15. `specs/022-sport-mode/`
16. `specs/023-fiscal/`

## Nota Importante

Esta estructura no obliga a mover todo inmediatamente.
Sirve como destino objetivo para migrar sin perder trazabilidad.
