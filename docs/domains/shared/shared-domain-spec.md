# Shared Domain Spec

Esta referencia documenta los Value Objects, tipos Doctrine y contratos compartidos usados por múltiples módulos del backend.

La intención es que esta capa sirva como verdad técnica transversal para todo el proyecto.

---

## Value Objects Compartidos

### Name

- Propósito: representar nombres no vacíos.
- Regla: no permite cadena vacía.
- Límite: hasta 150 caracteres.
- Representación: `string`.

### Email

- Propósito: representar correos válidos.
- Regla: valida formato de email.
- Normalización: se expone en minúsculas.
- Representación: `string`.

### PhoneNumber

- Propósito: representar teléfonos opcionales.
- Regla: acepta `null` o texto normalizado.
- Normalización: elimina espacios, guiones y paréntesis.
- Límite: hasta 30 caracteres.

### Address

- Propósito: representar dirección postal opcional.
- Límite: hasta 255 caracteres.

### City

- Propósito: representar ciudad opcional.
- Límite: hasta 120 caracteres.

### Media

- Propósito: representar un recurso binario persistido como metadata.
- Atributos:
  - `path`
  - `url`
  - `mimeType`
  - `size`
  - `checksum`
- MIME permitidos:
  - `image/jpeg`
  - `image/png`
  - `image/svg+xml`
- Regla: `size` debe ser positivo.
- Regla: `checksum` debe seguir el formato `sha256:<hash>`.

### AuditTrail

- Propósito: encapsular trazabilidad de creación y actualización.
- Componentes:
  - `createdAt`
  - `createdBy`
  - `updatedAt`
  - `updatedBy`
- Uso:
  - se crea al persistir el agregado
  - se actualiza con `touch(userId)`

### CreatedAt

- Propósito: envolver la fecha de creación.

### UpdatedAt

- Propósito: envolver la fecha de actualización.

### Description

- Propósito: texto descriptivo opcional.

### Notes

- Propósito: observaciones opcionales.

### MinimumAge / MaximumAge

- Propósito: representar rangos de edad de categorías.

---

## Tipos Doctrine UUID

Todos los tipos UUID específicos del dominio heredan de `AbstractUuidType`.

### Comportamiento Base

- Entrada a base: acepta `string` o `Stringable`.
- Salida a PHP: reconstruye el value object del tipo declarado.
- Declaración SQL: usa tipo GUID del motor.

### Tipos Registrados

- `academy_id` → `AcademyId`
- `category_id` → `CategoryId`
- `charge_id` → `ChargeId`
- `legal_guardian_id` → `LegalGuardianId`
- `membership_id` → `MembershipId`
- `payment_id` → `PaymentId`
- `payment_allocation_id` → `PaymentAllocationId`
- `payment_evidence_id` → `PaymentEvidenceId`
- `payment_concept_id` → `PaymentConceptId`
- `player_id` → `PlayerId`
- `player_guardian_id` → `PlayerGuardianId`
- `player_import_job_id` → `PlayerImportJobId`
- `staff_id` → `StaffId`
- `team_id` → `TeamId`
- `team_assignment_id` → `TeamAssignmentId`
- `team_staff_assignment_id` → `TeamStaffAssignmentId`
- `venue_id` → `VenueId`

---

## Reglas de Homologación

- Los VOs compartidos viven aquí, no en specs por feature.
- Los IDs específicos del dominio deben mapearse con tipos Doctrine explícitos.
- La documentación de una entidad de negocio debe referenciar este documento cuando use `Name`, `Email`, `Media`, `AuditTrail` o un ID tipado.

