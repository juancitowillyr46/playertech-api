# Backlog Index

Este indice organiza el backlog por prioridad funcional para facilitar la navegacion cuando se trabaja solo en el proyecto.

## Priorizacion Recomendada

1. [`EP-001`](./epics/EP-001.md) Academy
2. [`EP-003`](./epics/EP-003.md) Identity
3. [`EP-007`](./epics/EP-007.md) Player
4. [`EP-005`](./epics/EP-005.md) Team
5. [`EP-008`](./epics/EP-008.md) PlayerGuardian
6. [`EP-009`](./epics/EP-009.md) Membership
7. [`EP-010`](./epics/EP-010.md) TeamAssignment
8. [`EP-011`](./epics/EP-011.md) PaymentConcept
9. [`EP-012`](./epics/EP-012.md) Charge and Payment
10. [`EP-013`](./epics/EP-013.md) Dashboard
11. [`EP-014`](./epics/EP-014.md) Tenant onboarding
12. [`EP-021`](./epics/EP-021.md) Staff
13. [`EP-023`](./epics/EP-023.md) Fiscal profiles and receipts

## Use Rule

- `docs/backlog/epics/` contiene la visión funcional por dominio.
- `docs/backlog/stories/` contiene las historias específicas.
- Si una historia ya está implementada, debe quedar marcada como tal en `specs/14-current-state.md`.
- Si una historia está duplicada o renombrada, debe conservarse solo la versión que represente el contrato vigente.

## Maintenance Rule

Cuando se agregue un nuevo módulo o una nueva épica:

1. agregarla aquí;
2. enlazarla desde el epic o historia correspondiente;
3. registrar el cambio en `specs/14-current-state.md` si impacta trazabilidad.
