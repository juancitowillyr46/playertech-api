# Domain Entities

## Academy

Representa una academia registrada dentro de PlayerTech.

### Tipo

Aggregate Root

### Responsabilidades

- Configuración general de la academia.
- Aislamiento multi-tenant.
- Administración de sedes.
- Administración de usuarios de la academia.

### Estado

- ACTIVE
- SUSPENDED
- INACTIVE

---

## Venue

Representa una sede física donde la academia realiza actividades.

### Tipo

Entidad

### Responsabilidades

- Identificar ubicaciones físicas.
- Organizar futuras actividades deportivas.

### Estado

- ACTIVE
- INACTIVE

---

## User

Representa una persona autorizada para acceder a la plataforma.

### Tipo

Aggregate Root

### Responsabilidades

- Autenticación.
- Autorización.
- Operación administrativa.

### Estado

- ACTIVE
- INACTIVE

---

## Role

Representa un conjunto de permisos asignables a usuarios.

### Tipo

Aggregate Root

### Responsabilidades

- Agrupar permisos.
- Simplificar administración de accesos.

---

## Permission

Representa una capacidad específica dentro del sistema.

### Tipo

Entidad de referencia

### Responsabilidades

- Control granular de acceso.

---

## Category

Representa una agrupación deportiva basada en edad.

### Tipo

Aggregate Root

### Responsabilidades

- Clasificación deportiva.

### Estado

- ACTIVE
- INACTIVE

---

## Team

Representa un equipo deportivo.

### Tipo

Aggregate Root

### Responsabilidades

- Organización deportiva.
- Participación en competencias.

### Estado

- ACTIVE
- INACTIVE

---

## Player

Representa un jugador registrado.

### Tipo

Aggregate Root

### Responsabilidades

- Información deportiva.
- Participación en equipos.
- Relación con acudientes.
- Matrículas.

### Estado

- ACTIVE
- INACTIVE

---

## LegalGuardian

Representa un acudiente o tutor legal.

### Tipo

Aggregate Root

### Responsabilidades

- Contacto administrativo.
- Responsable financiero.

### Estado

- ACTIVE
- INACTIVE

---

## PlayerGuardian

Representa la relación entre jugadores y acudientes.

### Tipo

Entidad Relacional

### Responsabilidades

- Gestionar relaciones N:M.

---

## Membership

Representa la matrícula de un jugador.

### Tipo

Aggregate Root

### Responsabilidades

- Control de permanencia.
- Relación financiera.

### Estado

- ACTIVE
- SUSPENDED
- WITHDRAWN
- GRADUATED

---

## TeamAssignment

Representa la asignación deportiva de un jugador a un equipo.

### Tipo

Entidad Relacional

### Responsabilidades

- Gestión deportiva.

---

## PaymentConcept

Representa el motivo o concepto del pago.

### Tipo

Aggregate Root

### Estado

- ACTIVE
- INACTIVE

---

## Payment

Representa una transacción financiera.

### Tipo

Aggregate Root

### Responsabilidades

- Registro de pagos.
- Control financiero.

### Estado

- REGISTERED
- VOIDED

---

## PaymentEvidence

Representa una evidencia asociada a un pago.

### Tipo

Entidad

### Responsabilidades

- Soporte documental.