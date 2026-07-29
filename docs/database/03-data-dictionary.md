# Diccionario de Datos

Este documento resume los campos persistidos de las entidades principales del backend.

Convenciones:
- Las columnas en base de datos usan `snake_case`.
- Las propiedades de dominio usan `camelCase`.
- Todos los agregados principales usan `auditTrail`, `deletedAt` y `deletedBy` cuando corresponde.

---

## academies

| Campo | Tipo | Nulo | Descripción |
|---|---|---:|---|
| id | UUID | No | Identificador único |
| name | VARCHAR | No | Nombre de la academia |
| contact_email | VARCHAR | No | Correo principal |
| phone | VARCHAR | Sí | Teléfono |
| country | VARCHAR | Sí | País |
| department | VARCHAR | Sí | Departamento |
| tax_id_type | VARCHAR | Sí | Tipo de identificación tributaria |
| tax_id_number | VARCHAR | Sí | Número de identificación tributaria |
| tax_check_digit | VARCHAR | Sí | Dígito de verificación |
| tax_regime | VARCHAR | Sí | Régimen tributario |
| billing_email | VARCHAR | Sí | Correo de facturación |
| registration_source | VARCHAR | No | Fuente de registro |
| address | VARCHAR | Sí | Dirección |
| city | VARCHAR | Sí | Ciudad |
| shield | JSON / media | Sí | Imagen del escudo |
| status | VARCHAR | No | Estado |
| created_at | DATETIME | No | Auditoría |
| updated_at | DATETIME | Sí | Auditoría |
| deleted_at | DATETIME | Sí | Borrado lógico |
| deleted_by | UUID | Sí | Usuario que eliminó |

---

## users

| Campo | Tipo | Nulo | Descripción |
|---|---|---:|---|
| id | UUID | No | Identificador |
| academy_id | UUID | Sí | Tenant |
| full_name | VARCHAR | Sí | Nombre completo |
| email | VARCHAR | No | Correo |
| password_hash | VARCHAR | No | Hash de contraseña |
| role | VARCHAR | No | Rol funcional |
| status | VARCHAR | No | Estado |
| created_at | DATETIME | No | Creación |
| created_by | UUID | Sí | Creador |
| updated_at | DATETIME | Sí | Actualización |
| updated_by | UUID | Sí | Actualizador |
| deleted_at | DATETIME | Sí | Eliminación lógica |
| deleted_by | UUID | Sí | Eliminador |
| activation_token | VARCHAR | Sí | Token de activación |
| activation_expires_at | DATETIME | Sí | Expiración de activación |
| password_reset_token | VARCHAR | Sí | Token de recuperación |
| password_reset_expires_at | DATETIME | Sí | Expiración de recuperación |

---

## venues

| Campo | Tipo | Nulo | Descripción |
|---|---|---:|---|
| id | UUID | No | Identificador |
| academy_id | UUID | No | Tenant |
| name | VARCHAR | No | Nombre |
| address | VARCHAR | Sí | Dirección |
| city | VARCHAR | Sí | Ciudad |
| country | VARCHAR | Sí | País |
| department | VARCHAR | Sí | Departamento |
| phone | VARCHAR | Sí | Teléfono |
| notes | TEXT | Sí | Observaciones |
| is_primary | BOOLEAN | No | Sede principal |
| status | VARCHAR | No | Estado |
| created_at | DATETIME | No | Auditoría |
| updated_at | DATETIME | Sí | Auditoría |
| deleted_at | DATETIME | Sí | Borrado lógico |
| deleted_by | UUID | Sí | Usuario que eliminó |

---

## categories

| Campo | Tipo | Nulo | Descripción |
|---|---|---:|---|
| id | UUID | No | Identificador |
| academy_id | UUID | No | Tenant |
| category_key | VARCHAR | No | Llave técnica de categoría |
| name | VARCHAR | No | Nombre visible |
| min_age | SMALLINT | No | Edad mínima |
| max_age | SMALLINT | No | Edad máxima |
| description | VARCHAR | Sí | Descripción |
| status | VARCHAR | No | Estado |
| created_at | DATETIME | No | Auditoría |
| updated_at | DATETIME | Sí | Auditoría |
| deleted_at | DATETIME | Sí | Borrado lógico |
| deleted_by | UUID | Sí | Usuario que eliminó |

---

## onboarding_categories

| Campo | Tipo | Nulo | Descripción |
|---|---|---:|---|
| id | UUID | No | Identificador |
| code | VARCHAR | No | Código semilla |
| name | VARCHAR | No | Nombre |
| min_age | SMALLINT | No | Edad mínima |
| max_age | SMALLINT | No | Edad máxima |
| description | VARCHAR | Sí | Descripción |
| status | VARCHAR | No | Estado |
| created_at | DATETIME | Sí | Creación |
| updated_at | DATETIME | Sí | Actualización |

---

## players

| Campo | Tipo | Nulo | Descripción |
|---|---|---:|---|
| id | UUID | No | Identificador |
| academy_id | UUID | No | Tenant |
| document_type | VARCHAR | No | Tipo de documento |
| first_name | VARCHAR | No | Nombres |
| last_name | VARCHAR | No | Apellidos |
| birth_date | DATE | No | Fecha de nacimiento |
| document_number | VARCHAR | No | Número de documento |
| email | VARCHAR | Sí | Correo |
| phone | VARCHAR | Sí | Teléfono |
| nationality | VARCHAR | Sí | Nacionalidad |
| gender | VARCHAR | Sí | Género |
| federation_id | VARCHAR | Sí | Identificador federativo |
| dominant_foot | VARCHAR | Sí | Pie dominante |
| category_id | UUID | Sí | Categoría administrativa |
| photo | JSON / media | Sí | Imagen del jugador |
| status | VARCHAR | No | Estado |
| created_at | DATETIME | No | Auditoría |
| updated_at | DATETIME | Sí | Auditoría |
| deleted_at | DATETIME | Sí | Borrado lógico |
| deleted_by | UUID | Sí | Usuario que eliminó |

---

## player_import_jobs

| Campo | Tipo | Nulo | Descripción |
|---|---|---:|---|
| id | UUID | No | Identificador |
| academy_id | UUID | No | Tenant |
| created_by | UUID | No | Usuario que crea el job |
| category_id | UUID | No | Categoría aplicada al import |
| original_file_name | VARCHAR | No | Nombre original del archivo |
| file_path | VARCHAR | No | Ruta del archivo |
| status | VARCHAR | No | Estado del job |
| progress | INT | No | Progreso 0-100 |
| total_rows | INT | No | Total de filas |
| processed_rows | INT | No | Filas procesadas |
| success_rows | INT | No | Filas exitosas |
| error_rows | INT | No | Filas con error |
| errors | JSON | No | Errores estructurados |
| started_at | DATETIME | Sí | Inicio del proceso |
| finished_at | DATETIME | Sí | Fin del proceso |
| created_at | DATETIME | No | Creación |
| updated_at | DATETIME | Sí | Actualización |
| deleted_at | DATETIME | Sí | Borrado lógico |
| deleted_by | UUID | Sí | Usuario que eliminó |

---

## legal_guardians

| Campo | Tipo | Nulo | Descripción |
|---|---|---:|---|
| id | UUID | No | Identificador |
| academy_id | UUID | No | Tenant |
| first_name | VARCHAR | No | Nombres |
| last_name | VARCHAR | No | Apellidos |
| phone | VARCHAR | Sí | Teléfono |
| email | VARCHAR | Sí | Correo |
| document_type | VARCHAR | Sí | Tipo de documento |
| document_number | VARCHAR | Sí | Número de documento |
| address | VARCHAR | Sí | Dirección |
| relationship | VARCHAR | No | Parentesco |
| status | VARCHAR | No | Estado |
| created_at | DATETIME | No | Auditoría |
| updated_at | DATETIME | Sí | Auditoría |
| deleted_at | DATETIME | Sí | Borrado lógico |
| deleted_by | UUID | Sí | Usuario que eliminó |

---

## player_guardians

| Campo | Tipo | Nulo | Descripción |
|---|---|---:|---|
| id | UUID | No | Identificador |
| academy_id | UUID | No | Tenant |
| player_id | UUID | No | Jugador |
| guardian_id | UUID | No | Acudiente |
| is_primary | BOOLEAN | No | Acudiente principal |
| created_at | DATETIME | No | Auditoría |
| updated_at | DATETIME | Sí | Auditoría |
| deleted_at | DATETIME | Sí | Borrado lógico |
| deleted_by | UUID | Sí | Usuario que eliminó |

---

## teams

| Campo | Tipo | Nulo | Descripción |
|---|---|---:|---|
| id | UUID | No | Identificador |
| academy_id | UUID | No | Tenant |
| category_id | UUID | No | Categoría |
| name | VARCHAR | No | Nombre |
| status | VARCHAR | No | Estado |
| created_at | DATETIME | No | Auditoría |
| updated_at | DATETIME | Sí | Auditoría |
| deleted_at | DATETIME | Sí | Borrado lógico |
| deleted_by | UUID | Sí | Usuario que eliminó |

---

## team_assignments

| Campo | Tipo | Nulo | Descripción |
|---|---|---:|---|
| id | UUID | No | Identificador |
| academy_id | UUID | No | Tenant |
| player_id | UUID | No | Jugador |
| team_id | UUID | No | Equipo |
| start_date | DATE | No | Inicio |
| end_date | DATE | Sí | Fin |
| is_primary | BOOLEAN | No | Relación principal |
| created_at | DATETIME | No | Auditoría |
| updated_at | DATETIME | Sí | Auditoría |
| deleted_at | DATETIME | Sí | Borrado lógico |
| deleted_by | UUID | Sí | Usuario que eliminó |

---

## memberships

| Campo | Tipo | Nulo | Descripción |
|---|---|---:|---|
| id | UUID | No | Identificador |
| academy_id | UUID | No | Tenant |
| player_id | UUID | No | Jugador |
| primary_guardian_id | UUID | Sí | Acudiente principal |
| status | VARCHAR | No | Estado |
| started_at | DATETIME | No | Inicio |
| ended_at | DATETIME | Sí | Fin |
| created_at | DATETIME | No | Auditoría |
| updated_at | DATETIME | Sí | Auditoría |
| deleted_at | DATETIME | Sí | Borrado lógico |
| deleted_by | UUID | Sí | Usuario que eliminó |

---

## charges

| Campo | Tipo | Nulo | Descripción |
|---|---|---:|---|
| id | UUID | No | Identificador |
| academy_id | UUID | No | Tenant |
| player_id | UUID | No | Jugador |
| membership_id | UUID | No | Matrícula |
| payment_concept_id | UUID | No | Concepto |
| description | VARCHAR | No | Descripción |
| amount | DECIMAL(12,2) | No | Valor |
| allocated_amount | DECIMAL(12,2) | No | Valor aplicado |
| due_date | DATE | No | Vencimiento |
| source | VARCHAR | No | Origen |
| status | VARCHAR | No | Estado |
| created_at | DATETIME | No | Auditoría |
| updated_at | DATETIME | Sí | Auditoría |
| deleted_at | DATETIME | Sí | Borrado lógico |
| deleted_by | UUID | Sí | Usuario que eliminó |

---

## payment_concepts

| Campo | Tipo | Nulo | Descripción |
|---|---|---:|---|
| id | UUID | No | Identificador |
| academy_id | UUID | No | Tenant |
| code | VARCHAR | No | Código |
| name | VARCHAR | No | Nombre |
| description | VARCHAR | Sí | Descripción |
| status | VARCHAR | No | Estado |
| created_at | DATETIME | No | Auditoría |
| updated_at | DATETIME | Sí | Auditoría |
| deleted_at | DATETIME | Sí | Borrado lógico |
| deleted_by | UUID | Sí | Usuario que eliminó |

---

## payments

| Campo | Tipo | Nulo | Descripción |
|---|---|---:|---|
| id | UUID | No | Identificador |
| academy_id | UUID | No | Tenant |
| membership_id | UUID | No | Matrícula |
| player_id | UUID | No | Jugador |
| guardian_id | UUID | Sí | Responsable |
| payment_concept_id | UUID | No | Concepto |
| payment_date | DATE | No | Fecha |
| amount | DECIMAL(12,2) | No | Valor |
| method | VARCHAR | No | Método |
| notes | TEXT | Sí | Observaciones |
| allocations | JSON | Sí | Asignaciones internas |
| status | VARCHAR | No | Estado |
| created_at | DATETIME | No | Auditoría |
| updated_at | DATETIME | Sí | Auditoría |
| deleted_at | DATETIME | Sí | Borrado lógico |
| deleted_by | UUID | Sí | Usuario que eliminó |

---

## payment_allocations

| Campo | Tipo | Nulo | Descripción |
|---|---|---:|---|
| id | UUID | No | Identificador |
| academy_id | UUID | No | Tenant |
| payment_id | UUID | No | Pago |
| charge_id | UUID | No | Cargo |
| amount | DECIMAL(12,2) | No | Valor asignado |
| created_at | DATETIME | No | Auditoría |
| updated_at | DATETIME | Sí | Auditoría |
| deleted_at | DATETIME | Sí | Borrado lógico |
| deleted_by | UUID | Sí | Usuario que eliminó |

---

## payment_evidences

| Campo | Tipo | Nulo | Descripción |
|---|---|---:|---|
| id | UUID | No | Identificador |
| academy_id | UUID | No | Tenant |
| payment_id | UUID | No | Pago |
| file_name | VARCHAR | No | Archivo |
| file_path | VARCHAR | No | Ruta |
| mime_type | VARCHAR | No | MIME |
| created_at | DATETIME | No | Auditoría |
| updated_at | DATETIME | Sí | Auditoría |
| deleted_at | DATETIME | Sí | Borrado lógico |
| deleted_by | UUID | Sí | Usuario que eliminó |

---

## fiscal_attachments

| Campo | Tipo | Nulo | Descripción |
|---|---|---:|---|
| id | UUID | No | Identificador |
| academy_id | UUID | No | Tenant |
| payment_id | UUID | No | Pago |
| provider_name | VARCHAR | No | Proveedor |
| document_number | VARCHAR | No | Documento |
| document_url | VARCHAR | Sí | URL |
| status | VARCHAR | Sí | Estado |
| created_at | DATETIME | No | Auditoría |
| updated_at | DATETIME | Sí | Auditoría |
| deleted_at | DATETIME | Sí | Borrado lógico |
| deleted_by | UUID | Sí | Usuario que eliminó |

---

## staff

| Campo | Tipo | Nulo | Descripción |
|---|---|---:|---|
| id | UUID | No | Identificador |
| academy_id | UUID | No | Tenant |
| user_id | UUID | No | Usuario del sistema |
| status | VARCHAR | No | Estado |
| created_at | DATETIME | No | Auditoría |
| updated_at | DATETIME | Sí | Auditoría |
| deleted_at | DATETIME | Sí | Borrado lógico |
| deleted_by | UUID | Sí | Usuario que eliminó |

---

## team_staff_assignments

| Campo | Tipo | Nulo | Descripción |
|---|---|---:|---|
| id | UUID | No | Identificador |
| academy_id | UUID | No | Tenant |
| staff_id | UUID | No | Staff |
| team_id | UUID | No | Equipo |
| role | JSON / embebido | No | Rol del staff |
| created_at | DATETIME | No | Auditoría |
| updated_at | DATETIME | Sí | Auditoría |
| deleted_at | DATETIME | Sí | Borrado lógico |
| deleted_by | UUID | Sí | Usuario que eliminó |

