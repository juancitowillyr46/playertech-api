# Domain Documents

Este directorio concentra los documentos centrales por dominio puro.

## Structure

- `academy/` contratos y flujos de Academy.
- `identity/` contratos y flujos de Identity.
- `category/` contratos y flujos de Category.
- `venue/` contratos y flujos de Venue.
- `player/` contrato central del dominio Player.
- `guardian/` contratos y flujos de Guardian.
- `team/` contratos y flujos de Team.
- `billing/` contratos y flujos de Membership, Payments, Charges, Dashboard y Fiscal Profiles.
- `staff/` contratos y flujos de Staff.
- `shared/` value objects compartidos y tipos Doctrine UUID.

## Usage Rule

- Cada dominio debe tener un documento central.
- Si existe un subflujo importante dentro del dominio, debe preferir `docs/flows/` antes que mezclarse con el dominio.
- Si un documento describe UX o frontend de un flujo, debe referenciar el documento central del flujo, no redefinir contrato.

## Indexed Central Domains

- `academy/academy-domain-spec.md`
- `identity/identity-domain-spec.md`
- `category/category-domain-spec.md`
- `venue/venue-domain-spec.md`
- `player/player-domain-spec.md`
- `guardian/guardian-domain-spec.md`
- `team/team-domain-spec.md`
- `billing/billing-domain-spec.md`
- `staff/staff-domain-spec.md`
- `shared/shared-domain-spec.md`
