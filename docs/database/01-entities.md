# Entidades del Dominio

Este documento describe las entidades persistidas que hoy conforman el backend de PlayerTech.

La documentación está alineada con el código fuente actual y con los mappings XML de Doctrine.
El modelo sigue una estructura multi-tenant basada en `academy_id` y usa `soft delete` e historial de auditoría en los agregados principales.

---

## Academy

Tabla: `academies`

Representa la academia propietaria del tenant.

Campos principales:
- `id`
- `name`
- `contactEmail`
- `phone`
- `country`
- `department`
- `taxIdType`
- `taxIdNumber`
- `taxCheckDigit`
- `taxRegime`
- `billingEmail`
- `registrationSource`
- `address`
- `city`
- `shield`
- `status`
- `auditTrail`
- `deletedAt`
- `deletedBy`

Responsabilidad:
- Aislamiento multi-tenant.
- Configuración institucional y tributaria.
- Origen de casi todos los agregados de negocio.

---

## AccountUser

Tabla: `users`

Representa el usuario autenticable del sistema.

Campos principales:
- `id`
- `academyId`
- `fullName`
- `email`
- `passwordHash`
- `role`
- `status`
- `createdAt`
- `createdBy`
- `updatedAt`
- `updatedBy`
- `deletedAt`
- `deletedBy`
- `activationToken`
- `activationExpiresAt`
- `passwordResetToken`
- `passwordResetExpiresAt`

Responsabilidad:
- Autenticación y autorización.
- Administración de usuarios root y de academia.

Notas:
- Esta entidad aún usa atributos de Doctrine en código, aunque el resto del dominio usa XML mapping.

---

## Venue

Tabla: `venues`

Representa una sede física de la academia.

Campos principales:
- `id`
- `academyId`
- `name`
- `address`
- `city`
- `country`
- `department`
- `phone`
- `notes`
- `isPrimary`
- `status`
- `auditTrail`
- `deletedAt`
- `deletedBy`

Responsabilidad:
- Registrar sedes y ubicación operativa.

---

## Category

Tabla: `categories`

Representa una categoría deportiva o administrativa del tenant.

Campos principales:
- `id`
- `academyId`
- `categoryKey`
- `name`
- `minAge`
- `maxAge`
- `description`
- `status`
- `auditTrail`
- `deletedAt`
- `deletedBy`

Responsabilidad:
- Clasificar jugadores por rango de edad.
- Servir como referencia para equipos y filtros.

---

## OnboardingCategory

Tabla: `onboarding_categories`

Catálogo base de categorías usadas en onboarding y configuración inicial.

Campos principales:
- `id`
- `code`
- `name`
- `minAge`
- `maxAge`
- `description`
- `status`
- `createdAt`
- `updatedAt`

Responsabilidad:
- Suministrar categorías semilla para onboarding.

---

## Player

Tabla: `players`

Representa el jugador de la academia.

Campos principales:
- `id`
- `academyId`
- `documentType`
- `firstName`
- `lastName`
- `birthDate`
- `documentNumber`
- `email`
- `phone`
- `nationality`
- `gender`
- `federationId`
- `dominantFoot`
- `categoryId`
- `photo`
- `status`
- `auditTrail`
- `deletedAt`
- `deletedBy`

Responsabilidad:
- Entidad central del dominio operativo.
- Base para gestión deportiva, administrativa y financiera.

---

## PlayerImportJob

Tabla: `player_import_jobs`

Representa el job asíncrono de importación masiva de jugadores.

Campos principales:
- `id`
- `academyId`
- `createdBy`
- `categoryId`
- `originalFileName`
- `filePath`
- `status`
- `progress`
- `totalRows`
- `processedRows`
- `successRows`
- `errorRows`
- `errors`
- `startedAt`
- `finishedAt`
- `createdAt`
- `updatedAt`
- `deletedAt`
- `deletedBy`

Responsabilidad:
- Persistir el estado del proceso de importación.
- Exponer trazabilidad para polling desde frontend.

---

## PlayerGuardian

Tabla: `player_guardians`

Relaciona jugadores con acudientes.

Campos principales:
- `id`
- `academyId`
- `playerId`
- `guardianId`
- `isPrimary`
- `auditTrail`
- `deletedAt`
- `deletedBy`

Responsabilidad:
- Soportar relaciones N:M entre jugadores y acudientes.

---

## LegalGuardian

Tabla: `legal_guardians`

Representa el acudiente o tutor legal.

Campos principales:
- `id`
- `academyId`
- `firstName`
- `lastName`
- `phone`
- `email`
- `documentType`
- `documentNumber`
- `address`
- `relationship`
- `status`
- `auditTrail`
- `deletedAt`
- `deletedBy`

Responsabilidad:
- Contacto administrativo y responsable legal.

---

## Team

Tabla: `teams`

Representa un equipo competitivo de la academia.

Campos principales:
- `id`
- `academyId`
- `categoryId`
- `name`
- `status`
- `auditTrail`
- `deletedAt`
- `deletedBy`

Responsabilidad:
- Agrupar jugadores por categoría o competencia.

---

## TeamAssignment

Tabla: `team_assignments`

Relaciona jugadores con equipos.

Campos principales:
- `id`
- `academyId`
- `playerId`
- `teamId`
- `startDate`
- `endDate`
- `isPrimary`
- `auditTrail`
- `deletedAt`
- `deletedBy`

Responsabilidad:
- Mantener historial deportivo de pertenencia a equipos.

---

## Membership

Tabla: `memberships`

Representa la matrícula administrativa del jugador.

Campos principales:
- `id`
- `academyId`
- `playerId`
- `primaryGuardianId`
- `status`
- `startedAt`
- `endedAt`
- `auditTrail`
- `deletedAt`
- `deletedBy`

Responsabilidad:
- Controlar vigencia administrativa del jugador.

---

## Charge

Tabla: `charges`

Representa un cargo por cobrar asociado a un jugador y una matrícula.

Campos principales:
- `id`
- `academyId`
- `playerId`
- `membershipId`
- `paymentConceptId`
- `description`
- `amount`
- `allocatedAmount`
- `dueDate`
- `source`
- `status`
- `auditTrail`
- `deletedAt`
- `deletedBy`

Responsabilidad:
- Registrar obligaciones de cobro.

---

## PaymentConcept

Tabla: `payment_concepts`

Catálogo de conceptos de pago.

Campos principales:
- `id`
- `academyId`
- `code`
- `name`
- `description`
- `status`
- `auditTrail`
- `deletedAt`
- `deletedBy`

Responsabilidad:
- Estandarizar conceptos financieros.

---

## Payment

Tabla: `payments`

Representa un pago registrado en el sistema.

Campos principales:
- `id`
- `academyId`
- `membershipId`
- `playerId`
- `guardianId`
- `paymentConceptId`
- `paymentDate`
- `amount`
- `method`
- `notes`
- `allocations`
- `status`
- `auditTrail`
- `deletedAt`
- `deletedBy`

Responsabilidad:
- Registrar pagos aplicados a la matrícula o al jugador.

---

## PaymentAllocation

Tabla: `payment_allocations`

Relaciona pagos con cargos.

Campos principales:
- `id`
- `academyId`
- `paymentId`
- `chargeId`
- `amount`
- `auditTrail`
- `deletedAt`
- `deletedBy`

Responsabilidad:
- Distribuir un pago entre cargos específicos.

---

## PaymentEvidence

Tabla: `payment_evidences`

Evidencias documentales asociadas a un pago.

Campos principales:
- `id`
- `academyId`
- `paymentId`
- `fileName`
- `filePath`
- `mimeType`
- `auditTrail`
- `deletedAt`
- `deletedBy`

Responsabilidad:
- Guardar soportes de pago.

---

## FiscalAttachment

Tabla: `fiscal_attachments`

Soporte fiscal o documental asociado al pago.

Campos principales:
- `id`
- `academyId`
- `paymentId`
- `providerName`
- `documentNumber`
- `documentUrl`
- `status`
- `auditTrail`
- `deletedAt`
- `deletedBy`

Responsabilidad:
- Registrar trazabilidad fiscal.

---

## Staff

Tabla: `staff`

Representa una persona del staff operativo o técnico.

Campos principales:
- `id`
- `academyId`
- `userId`
- `status`
- `auditTrail`
- `deletedAt`
- `deletedBy`

Responsabilidad:
- Vincular un usuario con un perfil operativo de staff.

---

## TeamStaffAssignment

Tabla: `team_staff_assignments`

Relaciona staff con equipos.

Campos principales:
- `id`
- `academyId`
- `staffId`
- `teamId`
- `role`
- `status`
- `auditTrail`
- `deletedAt`
- `deletedBy`

Responsabilidad:
- Asignar técnicos, entrenadores o asistentes a equipos.

