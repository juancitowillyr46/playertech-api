# Data Dictionary

## Academy

| Campo | Tipo | Null | Descripción |
|---------|---------|---------|---------|
| id | UUID | No | Identificador |
| name | VARCHAR(150) | No | Nombre |
| email | VARCHAR(255) | No | Correo |
| phone | VARCHAR(50) | Sí | Teléfono |
| address | VARCHAR(255) | Sí | Dirección |
| city | VARCHAR(100) | Sí | Ciudad |
| status | VARCHAR(20) | No | Estado |

---

## Venue

| Campo | Tipo |
|---------|---------|
| id | UUID |
| academy_id | UUID |
| name | VARCHAR(150) |
| address | VARCHAR(255) |
| city | VARCHAR(100) |
| phone | VARCHAR(50) |
| notes | TEXT |
| status | VARCHAR(20) |

---

## User

| Campo | Tipo |
|---------|---------|
| id | UUID |
| academy_id | UUID |
| first_name | VARCHAR(100) |
| last_name | VARCHAR(100) |
| email | VARCHAR(255) |
| password | VARCHAR(255) |
| status | VARCHAR(20) |
| last_login_at | DATETIME |

---

## Role

| Campo | Tipo |
|---------|---------|
| id | UUID |
| academy_id | UUID NULL |
| name | VARCHAR(100) |
| description | VARCHAR(255) |
| is_system | BOOLEAN |

---

## Permission

| Campo | Tipo |
|---------|---------|
| id | UUID |
| code | VARCHAR(150) |
| name | VARCHAR(150) |
| description | VARCHAR(255) |

---

## Category

| Campo | Tipo |
|---------|---------|
| id | UUID |
| academy_id | UUID |
| name | VARCHAR(100) |
| min_age | SMALLINT |
| max_age | SMALLINT |
| description | VARCHAR(250) |
| status | VARCHAR(20) |

---

## Team

| Campo | Tipo |
|---------|---------|
| id | UUID |
| academy_id | UUID |
| category_id | UUID |
| name | VARCHAR(150) |
| status | VARCHAR(20) |

---

## Player

| Campo | Tipo |
|---------|---------|
| id | UUID |
| academy_id | UUID |
| first_name | VARCHAR(100) |
| last_name | VARCHAR(100) |
| birth_date | DATE |
| document_number | VARCHAR(50) |
| status | VARCHAR(20) |

---

## LegalGuardian

| Campo | Tipo |
|---------|---------|
| id | UUID |
| academy_id | UUID |
| first_name | VARCHAR(100) |
| last_name | VARCHAR(100) |
| phone | VARCHAR(50) |
| email | VARCHAR(255) |
| status | VARCHAR(20) |

---

## Membership

| Campo | Tipo |
|---------|---------|
| id | UUID |
| academy_id | UUID |
| player_id | UUID |
| start_date | DATE |
| end_date | DATE NULL |
| status | VARCHAR(20) |

---

## PaymentConcept

| Campo | Tipo |
|---------|---------|
| id | UUID |
| academy_id | UUID |
| code | VARCHAR(50) |
| name | VARCHAR(150) |
| status | VARCHAR(20) |

---

## Payment

| Campo | Tipo |
|---------|---------|
| id | UUID |
| academy_id | UUID |
| membership_id | UUID |
| player_id | UUID |
| guardian_id | UUID |
| payment_concept_id | UUID |
| payment_date | DATE |
| amount | DECIMAL(12,2) |
| status | VARCHAR(20) |

---

## PaymentEvidence

| Campo | Tipo |
|---------|---------|
| id | UUID |
| payment_id | UUID |
| file_name | VARCHAR(255) |
| file_path | VARCHAR(500) |
| file_type | VARCHAR(50) |