# Data Model: Fiscal Feature

**Feature Branch**: `023-fiscal`

## Entities

### FiscalProfile

- Holds the academy tax y billing identity.
- Tenant-scoped to one academy.

### Receipt

- Represents a generard payment receipt.
- References the payment y fiscal profile used.

### FiscalDocument

- Represents an external PDF linked to the fiscal recod.
- Almacena a traceable attachment reference.

