# Relaciones del Dominio

Este documento resume las relaciones persistidas que hoy existen en el backend de PlayerTech.

El foco sigue siendo `Player` como entidad central, pero el modelo real incluye bloques de identidad, configuración del tenant, staff y finanzas.

---

## Academy → Venue

- Cardinalidad: `1:N`
- Una academia puede tener múltiples sedes.
- Cada sede pertenece a una sola academia.

## Academy → Category

- Cardinalidad: `1:N`
- Una academia puede definir múltiples categorías.
- Cada categoría pertenece a una única academia.

## Academy → Player

- Cardinalidad: `1:N`
- Una academia puede registrar múltiples jugadores.

## Academy → LegalGuardian

- Cardinalidad: `1:N`
- Una academia puede registrar múltiples acudientes.

## Academy → Team

- Cardinalidad: `1:N`
- Una academia puede administrar múltiples equipos.

## Academy → Membership

- Cardinalidad: `1:N`
- Una academia puede administrar múltiples matrículas.

## Academy → Charge

- Cardinalidad: `1:N`
- Una academia puede tener múltiples cargos por cobrar.

## Academy → PaymentConcept

- Cardinalidad: `1:N`
- Una academia puede definir múltiples conceptos de pago.

## Academy → Payment

- Cardinalidad: `1:N`
- Una academia puede registrar múltiples pagos.

## Academy → Staff

- Cardinalidad: `1:N`
- Una academia puede registrar múltiples miembros de staff.

## Academy → AccountUser

- Cardinalidad: `1:N`
- Una academia puede tener múltiples usuarios administrativos.

## Academy → PlayerImportJob

- Cardinalidad: `1:N`
- Una academia puede generar múltiples jobs de importación.

## Category → Player

- Cardinalidad: `1:N`
- Una categoría puede clasificar múltiples jugadores.
- Cada jugador tiene como máximo una categoría administrativa activa.

## Category → Team

- Cardinalidad: `1:N`
- Una categoría puede agrupar múltiples equipos.

## Category → PlayerImportJob

- Cardinalidad: `1:N`
- El job de importación se ejecuta para una categoría seleccionada previamente.

## Player → Membership

- Cardinalidad: `1:N`
- Un jugador puede tener varias matrículas históricas.
- Solo una puede estar activa al mismo tiempo por tenant.

## Player → PlayerGuardian

- Cardinalidad: `1:N`
- Un jugador puede estar relacionado con múltiples acudientes.

## Player → TeamAssignment

- Cardinalidad: `1:N`
- Un jugador puede participar en múltiples equipos a lo largo del tiempo.

## Player → Charge

- Cardinalidad: `1:N`
- Un jugador puede tener múltiples cargos asociados.

## Player → Payment

- Cardinalidad: `1:N`
- Un jugador puede tener múltiples pagos asociados.

## LegalGuardian → PlayerGuardian

- Cardinalidad: `1:N`
- Un acudiente puede relacionarse con múltiples jugadores.

## LegalGuardian → Membership

- Cardinalidad: `1:N`
- Un acudiente puede figurar como responsable principal de varias matrículas.

## Team → TeamAssignment

- Cardinalidad: `1:N`
- Un equipo puede tener múltiples asignaciones de jugadores.

## Team → TeamStaffAssignment

- Cardinalidad: `1:N`
- Un equipo puede tener múltiples asignaciones de staff.

## Staff → TeamStaffAssignment

- Cardinalidad: `1:N`
- Un staff puede ser asignado a múltiples equipos.

## Membership → Payment

- Cardinalidad: `1:N`
- Una matrícula puede registrar múltiples pagos.

## Membership → Charge

- Cardinalidad: `1:N`
- Una matrícula puede originar múltiples cargos.

## PaymentConcept → Charge

- Cardinalidad: `1:N`
- Un concepto de pago puede estar asociado a múltiples cargos.

## PaymentConcept → Payment

- Cardinalidad: `1:N`
- Un concepto de pago puede utilizarse en múltiples pagos.

## Payment → PaymentAllocation

- Cardinalidad: `1:N`
- Un pago puede distribuirse en múltiples asignaciones de cobro.

## Charge → PaymentAllocation

- Cardinalidad: `1:N`
- Un cargo puede recibir múltiples asignaciones parciales.

## Payment → PaymentEvidence

- Cardinalidad: `1:N`
- Un pago puede tener múltiples evidencias documentales.

## Payment → FiscalAttachment

- Cardinalidad: `1:N`
- Un pago puede tener múltiples soportes fiscales.

## Resumen Relacional

```text
Academy
├── Venue
├── Category
├── Player
│   ├── Membership
│   │   ├── Charge
│   │   └── Payment
│   │       ├── PaymentAllocation
│   │       ├── PaymentEvidence
│   │       └── FiscalAttachment
│   ├── PlayerGuardian
│   └── TeamAssignment
├── LegalGuardian
├── Team
│   └── TeamStaffAssignment
├── Staff
├── PaymentConcept
├── PlayerImportJob
└── AccountUser
```

## Observaciones

- `AccountUser` es la entidad de autenticación, no el agregado administrativo del dominio.
- `PlayerImportJob` representa trazabilidad operacional y no debe confundirse con la entidad `Player`.
- `categoryId` es el enlace funcional más importante del flujo de jugadores, equipos e importación.

