# 06-database.md

# Database Engine

* MySQL 8+

---

# Database Standards

## Identifiers

Todas las tablas usarán UUID como identificador principal.

Representación física:

```sql
id BINARY(16) NOT NULL
```

## Foreign Keys

Las relaciones usarán UUID.

Ejemplo:

```sql
academy_id BINARY(16) NOT NULL
player_id BINARY(16) NOT NULL
guardian_id BINARY(16) NOT NULL
```

## Tenant Column

Todas las entidades tenant-aware deberán incluir:

```sql
academy_id BINARY(16) NOT NULL
```

Excepción técnica: tablas globales de referencia como `permissions` pueden no llevar `academy_id`.

---

# Audit Standard

Todas las tablas de negocio incluirán:

```sql
created_at DATETIME NOT NULL
created_by BINARY(16) NULL
updated_at DATETIME NULL
updated_by BINARY(16) NULL
deleted_at DATETIME NULL
deleted_by BINARY(16) NULL
```

## Audit Rule

Los campos de auditoría deben llenarse con el usuario autenticado que ejecuta la operación o con un actor de sistema claramente identificado.

---

# Soft Delete Policy

No se permitirán eliminaciones físicas para entidades de negocio.

Toda baja lógica se realizará mediante:

```sql
deleted_at
deleted_by
```

Las consultas operativas deben excluir registros eliminados.

---

# Core Tables

## academies

```sql
id
name
status
created_at
created_by
updated_at
updated_by
deleted_at
deleted_by
```

## users

```sql
id
academy_id
email
password_hash
status
created_at
created_by
updated_at
updated_by
deleted_at
deleted_by
```

## roles

Tabla global de referencia.

```sql
id
code
name
created_at
created_by
updated_at
updated_by
deleted_at
deleted_by
```

## permissions

Tabla global de referencia.

```sql
id
code
name
created_at
created_by
updated_at
updated_by
deleted_at
deleted_by
```

## user_roles

Relación entre usuarios y roles.

```sql
id
academy_id
user_id
role_id
created_at
created_by
updated_at
updated_by
deleted_at
deleted_by
```

## role_permissions

Relación entre roles y permisos.

```sql
id
role_id
permission_id
created_at
created_by
updated_at
updated_by
deleted_at
deleted_by
```

## venues

```sql
id
academy_id
name
address
status
created_at
created_by
updated_at
updated_by
deleted_at
deleted_by
```

## categories

```sql
id
academy_id
name
min_age
max_age
status
created_at
created_by
updated_at
updated_by
deleted_at
deleted_by
```

## teams

```sql
id
academy_id
category_id
name
status
created_at
created_by
updated_at
updated_by
deleted_at
deleted_by
```

## legal_guardians

```sql
id
academy_id
first_name
last_name
phone
email
status
created_at
created_by
updated_at
updated_by
deleted_at
deleted_by
```

## players

```sql
id
academy_id
first_name
last_name
birth_date
document_number
status
created_at
created_by
updated_at
updated_by
deleted_at
deleted_by
```

## player_guardians

```sql
id
academy_id
player_id
guardian_id
is_primary
created_at
created_by
updated_at
updated_by
deleted_at
deleted_by
```

## memberships

```sql
id
academy_id
player_id
start_date
end_date
status
created_at
created_by
updated_at
updated_by
deleted_at
deleted_by
```

## team_assignments

```sql
id
academy_id
player_id
team_id
start_date
end_date
created_at
created_by
updated_at
updated_by
deleted_at
deleted_by
```

## payment_concepts

```sql
id
academy_id
name
status
created_at
created_by
updated_at
updated_by
deleted_at
deleted_by
```

## payments

```sql
id
academy_id
membership_id
player_id
guardian_id
payment_concept_id
amount
payment_date
notes
status
created_at
created_by
updated_at
updated_by
deleted_at
deleted_by
```

## payment_evidences

```sql
id
academy_id
payment_id
file_name
file_path
mime_type
created_at
created_by
updated_at
updated_by
deleted_at
deleted_by
```

---

# Data Rules

## Category

No pueden existir categorías duplicadas dentro de una academia.

## Membership

Solo puede existir una matrícula activa por jugador dentro de una academia.

## PlayerGuardian

Un jugador activo debe tener exactamente un acudiente principal.

Un mismo acudiente puede ser principal de múltiples jugadores.

## Payment

Todo pago debe pertenecer a una matrícula válida.

`guardian_id` representa al responsable principal del comprobante y `player_id` se mantiene como apoyo de consulta e historial.

---

# Referential Integrity

Se utilizarán claves foráneas para relaciones críticas.

No se permitirán cascadas destructivas.

La integridad del tenant se debe preservar en todas las relaciones.

---

# Indexing Strategy

Todas las tablas deberán indexar al menos:

```sql
academy_id
deleted_at
```

Índices adicionales recomendados:

```sql
academy_id, status
academy_id, category_id
academy_id, player_id
academy_id, team_id
academy_id, payment_concept_id
```

---

# Persistence Notes

## Aggregate Roots

Persistencia propia recomendada para:

* Academy
* Player
* LegalGuardian
* Membership
* Payment

## Relational Structures

* PlayerGuardian
* TeamAssignment

Estas estructuras modelan relaciones entre agregados y no deben tratarse como aggregate roots independientes.
