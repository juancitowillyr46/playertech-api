# Domain Relationships

## Academy -> Venue

Cardinalidad:

1:N

Una academia puede tener múltiples sedes.

---

## Academy -> User

Cardinalidad:

1:N

Una academia puede tener múltiples usuarios.

---

## Academy -> Category

Cardinalidad:

1:N

Una academia puede tener múltiples categorías.

---

## Academy -> Team

Cardinalidad:

1:N

Una academia puede tener múltiples equipos.

---

## Academy -> Player

Cardinalidad:

1:N

Una academia puede tener múltiples jugadores.

---

## Academy -> LegalGuardian

Cardinalidad:

1:N

Una academia puede tener múltiples acudientes.

---

## Category -> Team

Cardinalidad:

1:N

Una categoría puede contener múltiples equipos.

---

## Player -> Membership

Cardinalidad:

1:N

Un jugador puede tener múltiples matrículas históricas.

---

## Player -> TeamAssignment

Cardinalidad:

1:N

Un jugador puede pertenecer a múltiples equipos.

---

## Team -> TeamAssignment

Cardinalidad:

1:N

Un equipo puede contener múltiples jugadores.

---

## Player -> PlayerGuardian

Cardinalidad:

1:N

---

## LegalGuardian -> PlayerGuardian

Cardinalidad:

1:N

---

## Membership -> Payment

Cardinalidad:

1:N

Una matrícula puede tener múltiples pagos.

---

## PaymentConcept -> Payment

Cardinalidad:

1:N

Un concepto puede asociarse a múltiples pagos.

---

## Payment -> PaymentEvidence

Cardinalidad:

1:N

Un pago puede contener múltiples evidencias.

---

## User -> Role

Cardinalidad:

N:M

Implementada mediante UserRole.

---

## Role -> Permission

Cardinalidad:

N:M

Implementada mediante RolePermission.