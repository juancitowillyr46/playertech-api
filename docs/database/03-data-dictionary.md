# 03-data-dictionary.md

# Diccionario de Datos

Este documento describe los atributos principales de las entidades del dominio de PlayerTech.

Los campos de auditoría (`created_at`, `updated_at`, `deleted_at`, etc.) son comunes a todas las entidades de negocio y se documentan en `06-database.md`.

---

# Academy

Representa una academia registrada en la plataforma.

| Campo | Tipo | Nulo | Descripción |
|---------|---------|---------|---------|
| id | UUID | No | Identificador único |
| name | VARCHAR(150) | No | Nombre de la academia |
| email | VARCHAR(255) | No | Correo principal |
| phone | VARCHAR(50) | Sí | Teléfono |
| address | VARCHAR(255) | Sí | Dirección |
| city | VARCHAR(100) | Sí | Ciudad |
| status | VARCHAR(20) | No | Estado |

---

# Venue

Representa una sede física.

| Campo | Tipo | Nulo | Descripción |
|---------|---------|---------|---------|
| id | UUID | No | Identificador |
| academy_id | UUID | No | Academia propietaria |
| name | VARCHAR(150) | No | Nombre |
| address | VARCHAR(255) | Sí | Dirección |
| city | VARCHAR(100) | Sí | Ciudad |
| phone | VARCHAR(50) | Sí | Teléfono |
| notes | TEXT | Sí | Observaciones |
| status | VARCHAR(20) | No | Estado |

---

# User

Representa un usuario del sistema.

| Campo | Tipo | Nulo | Descripción |
|---------|---------|---------|---------|
| id | UUID | No | Identificador |
| academy_id | UUID | No | Academia |
| first_name | VARCHAR(100) | No | Nombres |
| last_name | VARCHAR(100) | No | Apellidos |
| email | VARCHAR(255) | No | Correo |
| password_hash | VARCHAR(255) | No | Contraseña cifrada |
| last_login_at | DATETIME | Sí | Último acceso |
| status | VARCHAR(20) | No | Estado |

---

# Role

Representa un rol del sistema.

| Campo | Tipo | Nulo | Descripción |
|---------|---------|---------|---------|
| id | UUID | No | Identificador |
| academy_id | UUID | Sí | Nulo para roles globales |
| code | VARCHAR(50) | No | Código |
| name | VARCHAR(100) | No | Nombre |
| description | VARCHAR(255) | Sí | Descripción |
| is_system | BOOLEAN | No | Rol del sistema |

---

# Permission

Representa un permiso.

| Campo | Tipo | Nulo | Descripción |
|---------|---------|---------|---------|
| id | UUID | No | Identificador |
| code | VARCHAR(100) | No | Código |
| name | VARCHAR(150) | No | Nombre |
| description | VARCHAR(255) | Sí | Descripción |

---

# Category

Representa una categoría deportiva.

| Campo | Tipo | Nulo | Descripción |
|---------|---------|---------|---------|
| id | UUID | No | Identificador |
| academy_id | UUID | No | Academia |
| name | VARCHAR(100) | No | Nombre |
| min_age | SMALLINT | No | Edad mínima |
| max_age | SMALLINT | No | Edad máxima |
| description | VARCHAR(250) | Sí | Descripción |
| status | VARCHAR(20) | No | Estado |

---

# Player

Representa un jugador.

| Campo | Tipo | Nulo | Descripción |
|---------|---------|---------|---------|
| id | UUID | No | Identificador |
| academy_id | UUID | No | Academia |
| category_id | UUID | No | Categoría administrativa |
| document_type | VARCHAR(50) | No | Tipo de documento |
| first_name | VARCHAR(100) | No | Nombres |
| last_name | VARCHAR(100) | No | Apellidos |
| birth_date | DATE | No | Fecha de nacimiento |
| document_number | VARCHAR(50) | No | Documento |
| email | VARCHAR(255) | Sí | Correo electrónico |
| phone | VARCHAR(50) | Sí | Teléfono celular |
| nationality | VARCHAR(100) | Sí | Nacionalidad |
| gender | VARCHAR(20) | Sí | Género |
| federation_id | VARCHAR(80) | Sí | Identificador de federación |
| dominant_foot | VARCHAR(20) | Sí | Pie dominante |
| status | VARCHAR(20) | No | Estado |

> La categoría representa la clasificación administrativa del jugador.

---

# Team

Representa un equipo competitivo.

| Campo | Tipo | Nulo | Descripción |
|---------|---------|---------|---------|
| id | UUID | No | Identificador |
| academy_id | UUID | No | Academia |
| category_id | UUID | No | Categoría |
| name | VARCHAR(150) | No | Nombre del equipo |
| status | VARCHAR(20) | No | Estado |

---

# LegalGuardian

Representa un acudiente.

| Campo | Tipo | Nulo | Descripción |
|---------|---------|---------|---------|
| id | UUID | No | Identificador |
| academy_id | UUID | No | Academia |
| first_name | VARCHAR(100) | No | Nombres |
| last_name | VARCHAR(100) | No | Apellidos |
| phone | VARCHAR(50) | Sí | Teléfono |
| email | VARCHAR(255) | Sí | Correo |
| status | VARCHAR(20) | No | Estado |

---

# PlayerGuardian

Relaciona jugadores y acudientes.

| Campo | Tipo | Nulo | Descripción |
|---------|---------|---------|---------|
| id | UUID | No | Identificador |
| academy_id | UUID | No | Academia |
| player_id | UUID | No | Jugador |
| guardian_id | UUID | No | Acudiente |
| is_primary | BOOLEAN | No | Indica si es el acudiente principal |

---

# Membership

Representa la matrícula del jugador.

| Campo | Tipo | Nulo | Descripción |
|---------|---------|---------|---------|
| id | UUID | No | Identificador |
| academy_id | UUID | No | Academia |
| player_id | UUID | No | Jugador |
| start_date | DATE | No | Inicio |
| end_date | DATE | Sí | Finalización |
| status | VARCHAR(20) | No | Estado |

---

# TeamAssignment

Representa la participación deportiva del jugador.

| Campo | Tipo | Nulo | Descripción |
|---------|---------|---------|---------|
| id | UUID | No | Identificador |
| academy_id | UUID | No | Academia |
| player_id | UUID | No | Jugador |
| team_id | UUID | No | Equipo |
| start_date | DATE | No | Inicio |
| end_date | DATE | Sí | Finalización |

---

# PaymentConcept

Representa un concepto de pago.

| Campo | Tipo | Nulo | Descripción |
|---------|---------|---------|---------|
| id | UUID | No | Identificador |
| academy_id | UUID | No | Academia |
| code | VARCHAR(50) | No | Código |
| name | VARCHAR(150) | No | Nombre |
| status | VARCHAR(20) | No | Estado |

---

# Payment

Representa un pago.

| Campo | Tipo | Nulo | Descripción |
|---------|---------|---------|---------|
| id | UUID | No | Identificador |
| academy_id | UUID | No | Academia |
| membership_id | UUID | No | Matrícula |
| player_id | UUID | No | Jugador |
| guardian_id | UUID | No | Responsable del pago |
| payment_concept_id | UUID | No | Concepto |
| payment_date | DATE | No | Fecha |
| amount | DECIMAL(12,2) | No | Valor |
| notes | TEXT | Sí | Observaciones |
| status | VARCHAR(20) | No | Estado |

---

# PaymentEvidence

Representa una evidencia de pago.

| Campo | Tipo | Nulo | Descripción |
|---------|---------|---------|---------|
| id | UUID | No | Identificador |
| academy_id | UUID | No | Academia |
| payment_id | UUID | No | Pago |
| file_name | VARCHAR(255) | No | Nombre del archivo |
| file_path | VARCHAR(500) | No | Ruta |
| mime_type | VARCHAR(100) | No | Tipo MIME |
