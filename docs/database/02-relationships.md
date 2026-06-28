# 02-relationships.md

# Relaciones del Dominio

Este documento describe las relaciones existentes entre las entidades principales del dominio de PlayerTech.

Las relaciones están organizadas desde la perspectiva del modelo Player-Centric, donde el jugador es la entidad central del sistema.

---

# Academy → Venue

## Cardinalidad

1:N

## Descripción

Una academia puede tener múltiples sedes.

Cada sede pertenece a una única academia.

---

# Academy → User

## Cardinalidad

1:N

## Descripción

Una academia puede tener múltiples usuarios administrativos.

---

# Academy → Category

## Cardinalidad

1:N

## Descripción

Una academia puede definir múltiples categorías deportivas.

---

# Academy → Team

## Cardinalidad

1:N

## Descripción

Una academia puede administrar múltiples equipos competitivos.

---

# Academy → Player

## Cardinalidad

1:N

## Descripción

Una academia puede registrar múltiples jugadores.

---

# Academy → LegalGuardian

## Cardinalidad

1:N

## Descripción

Una academia puede registrar múltiples acudientes.

---

# Category → Player

## Cardinalidad

1:N

## Descripción

Una categoría puede contener múltiples jugadores.

Todo jugador debe pertenecer a una única categoría administrativa activa.

La categoría determina la clasificación administrativa del jugador.

---

# Category → Team

## Cardinalidad

1:N

## Descripción

Una categoría puede contener múltiples equipos competitivos.

Los equipos representan agrupaciones deportivas para competencias o torneos.

---

# Player → Membership

## Cardinalidad

1:N

## Descripción

Un jugador puede tener múltiples matrículas históricas.

Solo una matrícula puede estar activa simultáneamente dentro de una academia.

---

# Player → TeamAssignment

## Cardinalidad

1:N

## Descripción

Un jugador puede participar simultáneamente en múltiples equipos.

La participación deportiva es independiente de la matrícula.

---

# Team → TeamAssignment

## Cardinalidad

1:N

## Descripción

Un equipo puede tener múltiples jugadores asignados.

Las asignaciones mantienen el historial deportivo mediante fechas de inicio y finalización.

---

# Player → PlayerGuardian

## Cardinalidad

1:N

## Descripción

Un jugador puede estar relacionado con múltiples acudientes.

La relación se gestiona mediante PlayerGuardian.

---

# LegalGuardian → PlayerGuardian

## Cardinalidad

1:N

## Descripción

Un acudiente puede estar asociado a múltiples jugadores.

---

# Membership → Payment

## Cardinalidad

1:N

## Descripción

Una matrícula puede registrar múltiples pagos.

Los pagos representan la permanencia administrativa del jugador.

---

# PaymentConcept → Payment

## Cardinalidad

1:N

## Descripción

Un concepto de pago puede utilizarse en múltiples pagos.

---

# Payment → PaymentEvidence

## Cardinalidad

1:N

## Descripción

Un pago puede tener múltiples evidencias o soportes documentales.

---

# User → Role

## Cardinalidad

N:M

## Descripción

Un usuario puede tener múltiples roles.

La relación se implementa mediante UserRole.

---

# Role → Permission

## Cardinalidad

N:M

## Descripción

Un rol puede contener múltiples permisos.

La relación se implementa mediante RolePermission.

---

# Resumen del Modelo Relacional

Academy
├── Venue
├── User
├── Category
│   ├── Player
│   │   ├── Membership
│   │   │   └── Payment
│   │   │       └── PaymentEvidence
│   │   ├── PlayerGuardian
│   │   └── TeamAssignment
│   └── Team
│       └── TeamAssignment
└── LegalGuardian
    └── PlayerGuardian