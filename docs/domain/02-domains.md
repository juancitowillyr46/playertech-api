# Domain Model

## Bounded Contexts

### Identity & Access

Responsable de autenticación y autorización.

#### Aggregate Roots

- User
- Role

#### Entities

- Permission
- UserRole
- RolePermission

---

### Academy Management

Responsable de la administración de academias.

#### Aggregate Roots

- Academy

#### Entities

- Venue

---

### Sports Management

Responsable de la organización deportiva.

#### Aggregate Roots

- Category
- Team
- Player
- LegalGuardian

#### Entities

- TeamAssignment
- PlayerGuardian

---

### Membership Management

Responsable de controlar la permanencia de jugadores.

#### Aggregate Roots

- Membership

---

### Financial Management

Responsable de los pagos.

#### Aggregate Roots

- Payment
- PaymentConcept

#### Entities

- PaymentEvidence

---

# Multi-Tenant Rule

Toda entidad de negocio debe pertenecer a una academia mediante:

academy_id

Excepciones:

- Permission

---

# Aggregate Boundaries

## Academy Aggregate

Incluye:

- Academy

---

## User Aggregate

Incluye:

- User

---

## Category Aggregate

Incluye:

- Category

---

## Team Aggregate

Incluye:

- Team

---

## Player Aggregate

Incluye:

- Player
- TeamAssignment
- PlayerGuardian

---

## LegalGuardian Aggregate

Incluye:

- LegalGuardian

---

## Membership Aggregate

Incluye:

- Membership

---

## Payment Aggregate

Incluye:

- Payment
- PaymentEvidence

---

# Domain Invariants

## Membership

Solo una matrícula activa por jugador dentro de una academia.

---

## Category

No pueden existir categorías duplicadas dentro de una academia.

---

## Team

Todo equipo debe pertenecer a una categoría.

---

## Payment

Todo pago debe pertenecer a una matrícula válida.

---

## PlayerGuardian

Todo jugador activo debe tener exactamente un acudiente principal.

---

## Academy

Toda entidad de negocio debe respetar el aislamiento multi-tenant.