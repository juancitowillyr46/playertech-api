# Financial Domain Model

Este documento consolida el entendimiento del dominio financiero de PlayerTech para que backend y frontend hablen el mismo idioma.

---

# 1. Objetos del dominio

## PaymentConcept

Catálogo reusable de cobros.

Ejemplos:

* Matrícula
* Mensualidad
* Uniforme
* Torneo
* Transporte
* Examen médico

### Propósito

Definir qué se cobra.

### Reglas

* No representa una deuda.
* El `code` se genera en backend.
* El `code` es inmutable luego de creado.

## Charge

Deuda concreta generada para un jugador.

### Propósito

Representar una obligación financiera operativa.

### Identificadores y datos mínimos

* `academyId`
* `playerId`
* `membershipId`
* `paymentConceptId`
* `amount`
* `dueDate`
* `status`
* `source`

### Reglas

* Un cargo nace desde un concepto.
* Un cargo pertenece a un jugador.
* Un cargo puede originarse desde matrícula o carga manual.
* Un cargo debe tener saldo calculable.

## Payment

Recaudo del acudiente sobre una deuda del jugador.

### Propósito

Registrar el dinero recibido y su trazabilidad hacia cargos específicos.

### Identificadores y datos mínimos

* `academyId`
* `guardianId`
* `playerId`
* `membershipId`
* `paymentConceptId`
* `paymentDate`
* `amount`
* `method`
* `status`
* `allocations[]`

### Reglas

* El pago no debe existir sin trazabilidad.
* El acudiente es el pagador principal.
* El jugador es el sujeto de la deuda.
* El pago puede aplicarse a uno o varios cargos.
* El historial financiero debe poder consultarse por `playerId` y por `guardianId`.

## PaymentAllocation

Distribución de un pago sobre uno o varios cargos.

### Propósito

Registrar cuánto del pago cubre cada cargo.

### Reglas

* Todo pago aplicado debe dejar una o más allocations cuando se distribuye a cargos.
* Cada allocation debe referenciar un cargo.

---

# 2. Relación entre IDs

## `membershipId`

Identifica la matrícula activa del jugador.

Uso:

* contexto administrativo
* generación de cargos automáticos
* referencia operativa para pagos

## `playerId`

Identifica al jugador cuya deuda se está administrando.

Uso:

* consulta de deuda
* historial financiero por jugador
* origen de cargos operativos o automáticos

## `guardianId`

Identifica al acudiente que realiza el pago.

Uso:

* pagador principal
* trazabilidad del recaudo
* historial financiero del responsable

## `paymentConceptId`

Identifica el concepto reusable que motiva el cobro.

Uso:

* carga manual de cargos
* generación de cargos automáticos
* clasificación de pagos

## `chargeId`

Identifica una deuda concreta.

Uso:

* aplicar pagos
* consultar saldo
* trazar deuda histórica

---

# 3. Flujo funcional recomendado

1. Crear concepto de cobro.
2. Generar cargo para un jugador.
3. Consultar cargos pendientes del jugador.
4. Registrar pago del acudiente.
5. Aplicar el pago a uno o varios cargos.
6. Recalcular saldo y estado de cada cargo.
7. Consultar deuda e historial.

---

# 4. Contratos de API recomendados

## 4.1 Crear concepto

`POST /api/v1/academy/payment-concepts`

### Request

```json
{
  "name": "Matrícula",
  "description": "Cobro inicial"
}
```

### Response

```json
{
  "data": {
    "id": "uuid",
    "academyId": "uuid",
    "code": "MATRICULA",
    "name": "Matrícula",
    "description": "Cobro inicial",
    "status": "ACTIVE"
  },
  "meta": {}
}
```

## 4.2 Crear cargo manual

Contrato recomendado:

`POST /api/v1/academy/charges`

### Request

```json
{
  "playerId": "uuid",
  "paymentConceptId": "uuid",
  "amount": 90000,
  "dueDate": "2026-07-31",
  "description": "Cobro de uniforme",
  "source": "MANUAL"
}
```

### Response mínima esperada

```json
{
  "data": {
    "id": "uuid",
    "academyId": "uuid",
    "membershipId": "uuid",
    "playerId": "uuid",
    "paymentConcept": {
      "id": "uuid",
      "code": "UNIFORME",
      "name": "Uniforme"
    },
    "description": "Cobro de uniforme",
    "amount": "90000.00",
    "dueDate": "2026-07-31",
    "source": "MANUAL",
    "status": "PENDING",
    "pendingBalance": "90000.00"
  },
  "meta": {}
}
```

## 4.3 Consultar deuda

`GET /api/v1/academy/payments/players/{playerId}/debt`

### Response mínima esperada

```json
{
  "data": {
    "playerId": "uuid",
    "pendingAmount": "150000.00",
    "pendingCharges": 2
  },
  "meta": {}
}
```

## 4.4 Registrar pago

Contrato recomendado:

`POST /api/v1/academy/payments`

### Request recomendado

```json
{
  "guardianId": "uuid",
  "playerId": "uuid",
  "paymentConceptId": "uuid",
  "paymentDate": "2026-07-14",
  "amount": 90000,
  "method": "CASH",
  "notes": "Pago en efectivo",
  "allocations": [
    {
      "chargeId": "uuid",
      "amount": 90000
    }
  ]
}
```

### Response mínima esperada

```json
{
  "data": {
    "id": "uuid",
    "guardianId": "uuid",
    "playerId": "uuid",
    "paymentConceptId": "uuid",
    "paymentDate": "2026-07-14",
    "amount": "90000.00",
    "method": "CASH",
    "status": "REGISTERED",
    "allocations": [
      {
        "chargeId": "uuid",
        "amount": "90000.00"
      }
    ]
  },
  "meta": {}
}
```

---

# 5. Estados sugeridos

## PaymentConcept

* `ACTIVE`
* `INACTIVE`

## Charge

* `PENDING`
* `PAID`
* `PARTIAL` si se decide soportar parcialidad visible

## Payment

* `REGISTERED`
* `APPLIED`
* `CANCELLED`

---

# 6. Brechas actuales

1. `PaymentAllocation` todavía no se expone como respuesta HTTP agregada del pago.
2. La conciliación de pagos parciales sigue fuera del alcance del MVP y debe mantenerse explícitamente bloqueada.
3. La deuda por jugador debe calcularse a partir de sus cargos pendientes reales.

---

# 7. HUs a crear o ajustar

## EP-012

* HU-001 Crear cargo manual.
* HU-002 Consultar cargos pendientes.
* HU-003 Consultar deuda de jugador.
* HU-004 Registrar pago con acudiente principal.
* HU-005 Aplicar pago a uno o varios cargos.
* HU-006 Consultar historial de pagos.
* HU-007 Adjuntar evidencia de pago.
* HU-008 Cancelar pago.
* HU-009 Ver trazabilidad de pagos por jugador.

## EP-023

* Registrar información tributaria de academia.
* Consultar información tributaria de academia.
* Actualizar información tributaria de academia.
* Generar comprobante de pago operativo.
* Consultar comprobante de pago operativo.
* Vincular soporte fiscal PDF externo.

## EP-009

* HU-001 Crear matrícula con acudiente principal.
* HU-002 Generar cargos iniciales.

---

# 8. Decisión técnica recomendada

* Mantener `PaymentConcept` como catálogo reusable.
* Reforzar `Charge` como deuda concreta.
* Tratar `Payment` como recaudo del acudiente sobre cargos.
* Explicitar `allocations[]` cuando un pago cubra varios cargos y validar que la suma coincida con el total registrado.
* Mantener el historial por `playerId` y agregar también el historial por `guardianId`.
