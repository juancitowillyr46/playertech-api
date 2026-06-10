# 02-domains.md

# Domain Overview

El dominio de PlayerTech está orientado a la gestión operativa y administrativa de academias de fútbol.

El sistema deberá soportar múltiples academias bajo un modelo SaaS Multi-Tenant utilizando una base de datos compartida.

Todas las entidades de negocio deberán pertenecer a una academia mediante el campo:

* academy_id

---

# Academy

Representa una academia registrada en la plataforma.

## Responsibilities

* Configuración general.
* Aislamiento multi-tenant.
* Administración de datos operativos.

## Status

* ACTIVE
* SUSPENDED
* INACTIVE

---

# Venue

Representa una sede física donde se desarrollan entrenamientos o actividades deportivas.

## Rules

* Pertenece a una academia.
* Puede ser utilizada por múltiples equipos.
* Puede utilizarse en futuros módulos de horarios.

## Status

* ACTIVE
* INACTIVE

---

# Category

Representa una agrupación deportiva basada en rangos de edad.

## Attributes

* Name
* Min Age
* Max Age

## Rules

* La edad mínima debe ser menor que la edad máxima.
* No puede existir una categoría duplicada dentro de una misma academia.

## Examples

* Sub 6
* Sub 8
* Sub 10
* Sub 12

## Status

* ACTIVE
* INACTIVE

---

# Team

Representa un equipo deportivo.

## Rules

* Debe pertenecer a una categoría.
* Una categoría puede contener múltiples equipos.
* Un equipo puede tener múltiples jugadores asignados.

## Examples

* Sub 12 A
* Sub 12 B
* Sub 12 Competitivo

## Status

* ACTIVE
* INACTIVE

---

# Legal Guardian

Representa un acudiente o tutor legal.

## Rules

* Puede existir independientemente de jugadores.
* Puede estar asociado a múltiples jugadores.
* Debe contener información de contacto.

## Status

* ACTIVE
* INACTIVE

---

## Legal Guardian Relationships

La relación entre jugadores y tutores legales es de tipo N:M.

Un jugador puede tener:

* Padre.
* Madre.
* Acudiente.
* Responsable autorizado.

simultáneamente.

Un tutor legal puede estar asociado a múltiples jugadores.

Las responsabilidades administrativas se gestionan mediante la entidad PlayerGuardian.

---

# Player

Representa un jugador registrado en la academia.

## Rules

* Puede estar asociado a múltiples tutores legales mediante PlayerGuardian.
* Puede tener múltiples asignaciones deportivas mediante TeamAssignment.
* Puede tener múltiples matrículas históricas.

## Status

* ACTIVE
* INACTIVE

## MVP Attributes

* First Name
* Last Name
* Birth Date
* Document Number

## Future Attributes (V1.1)

* Photo
* Email
* Phone
* Nationality
* Federation Player Id
* Preferred Position
* Dominant Foot

---

# Player Guardian

Representa la relación entre jugadores y tutores legales.

## Rules

* Relación N:M entre Player y LegalGuardian.
* Debe existir exactamente un tutor principal por jugador activo.
* Permite definir responsabilidades administrativas específicas.

## Responsibilities

* Tutor principal.
* Responsable de pagos.
* Responsable de autorizaciones.
* Contacto de emergencia.

## Attributes

* player_id
* guardian_id
* is_primary

---

# Membership

Representa la matrícula de un jugador dentro de una academia.

Es la entidad principal para controlar la permanencia administrativa y financiera del jugador.

## Rules

* Una matrícula pertenece a un único jugador.
* Un jugador puede tener múltiples matrículas históricas.
* Solo puede existir una matrícula activa por academia.
* Una matrícula activa indica que el jugador pertenece actualmente a la academia.
* Los pagos se asocian a una matrícula.

## Attributes

* Start Date
* End Date
* Status

## Status

* ACTIVE
* SUSPENDED
* WITHDRAWN
* GRADUATED

---

# Team Assignment

Representa la relación entre Player y Team.

La relación entre jugadores y equipos es de tipo N:M.

Representa la participación deportiva de un jugador dentro de un equipo.

## Rules

* Un jugador puede pertenecer simultáneamente a múltiples equipos.
* Una asignación tiene fecha de inicio.
* Una asignación puede tener fecha de finalización.
* No genera obligaciones financieras.
* No representa una matrícula.

## Examples

Jugador:

* Sub 12 A
* Sub 13 Competitivo

---

# Aggregate Boundaries

## Academy Aggregate

Raíz del contexto multi-tenant.

## Player Aggregate

Raíz del contexto deportivo del jugador.

## LegalGuardian Aggregate

Raíz del contexto administrativo del tutor legal.

## Membership Aggregate

Raíz del contexto de permanencia del jugador dentro de la academia.

## Payment Aggregate

Raíz del contexto financiero.

## Relationship Entities

Las siguientes entidades no son Aggregate Roots:

* PlayerGuardian
* TeamAssignment

---

# Payment Concept

Representa el concepto o motivo del pago.

## Initial Concepts

* REGISTRATION
* MONTHLY_FEE
* OTHER

## Future Concepts

* TOURNAMENT_REGISTRATION
* REFEREE_FEE
* UNIFORM
* TRANSPORT
* EVENT

## Status

* ACTIVE
* INACTIVE

---

# Payment

Representa un pago realizado por un tutor legal.

## Rules

* Asociado a una matrícula.
* Asociado a un jugador.
* Asociado a un tutor legal.
* Asociado a un concepto de pago.
* Debe registrar fecha y valor.
* No puede existir sin una matrícula válida.

## Status

* REGISTERED
* VOIDED

---

# Payment Evidence

Representa la evidencia o comprobante asociado a un pago.

## Supported Types

* Image
* PDF

## Rules

* Pertenece a un pago.
* Puede existir más de una evidencia por pago.

---

# Open Questions

## Primary Team

Algunas academias manejan el concepto de equipo principal para un jugador.

Actualmente esta necesidad queda cubierta mediante TeamAssignment.

Se validará en futuras entrevistas si existe una necesidad real de modelar:

* Equipo principal.
* Equipo secundario.
* Equipo invitado.

---

## Future Modules

Fuera del alcance del MVP V1.

* Coaches
* Training Schedules
* Training Sessions
* Attendance
* Competitions
* Tournament Registrations
* Matches
* Referee Fees
* Statistics
* Parent Portal
* Mobile Applications


## Domain Evolution Notes

Las siguientes observaciones fueron identificadas durante el diseño del dominio y quedan registradas para futuras iteraciones.

### Membership Active Constraint

Actualmente se define:

"Solo puede existir una matrícula activa por academia."

En futuras versiones deberá validarse si la regla correcta es:

"Solo puede existir una matrícula activa por jugador dentro de una academia."

La definición actual se mantiene para el MVP hasta validar el comportamiento real con academias usuarias.

---

### PlayerGuardian Responsibilities

Actualmente la relación PlayerGuardian utiliza el atributo:

* is_primary

En futuras versiones podría evolucionar hacia un modelo más flexible basado en responsabilidades.

Ejemplos:

* PRIMARY_GUARDIAN
* PAYMENT_RESPONSIBLE
* AUTHORIZATION_RESPONSIBLE
* EMERGENCY_CONTACT

Esta evolución permitiría representar escenarios familiares más complejos sin modificar los Aggregate Roots existentes.

---

### Primary Team Concept

Actualmente TeamAssignment cubre la participación de un jugador en uno o varios equipos.

En futuras versiones podría requerirse distinguir:

* Equipo principal
* Equipo secundario
* Equipo invitado

La necesidad será validada con academias durante la evolución del producto.