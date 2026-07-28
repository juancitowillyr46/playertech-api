# Data Model: Fiscal Feature

**Feature Branch**: `023-fiscal`

## Entities

### FiscalProfile

- Holds the academy tax and billing identity.
- Tenant-scoped to one academy.

### Receipt

- Represents a generated payment receipt.
- References the payment and fiscal profile used.

### FiscalDocument

- Represents an external PDF linked to the fiscal record.
- Stores a traceable attachment reference.

