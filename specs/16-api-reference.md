# 16-api-reference.md

# API Reference

Este documento sirve como referencia operativa de la API HTTP de PlayerTech mientras no exista Swagger/OpenAPI interactivo.

La convención general de respuestas y errores está definida en `specs/04-api.md`.

---

# Access Model

## ROLE_ROOT

* Usuario de plataforma.
* Vive en `users`.
* `academy_id = null`.
* Puede operar rutas `/api/v1/platform/*`.
* Puede crear academias y administrar usuarios de plataforma o usuarios tenant desde plataforma.

## Tenant User

* Usuario de academia.
* Vive en `users`.
* `academy_id` obligatorio.
* Opera rutas `/api/v1/academy/*`.
* No puede usar rutas `/api/v1/platform/*`.

---

# Public Tenant Signup

## Tenant Signup

```http
POST /api/v1/public/tenants/signup
```

### Access

* Público.

### Purpose

Registrar una nueva academia, crear el usuario administrador inicial y crear el primer equipo asociado a una categoría existente.

### Request DTO

`TenantSignupInput`

```json
{
  "name": "Academia PlayerTech Demo",
  "contact_email": "tenant.demo@example.com",
  "contact_name": "Juan Perez",
  "password": "secret12345",
  "phone": "+51 987 654 321",
  "address": "Jr. Secundario 789",
  "city": "Arequipa",
  "category_id": "uuid",
  "team_name": "Sub 12 A"
}
```

### Rules

* `category_id` es obligatorio y debe existir.
* La categoría debe estar activa.
* `team_name` es obligatorio y su longitud máxima es 80 caracteres.
* No puede existir otro equipo con el mismo nombre dentro de la misma categoría de la academia.

### Success

`201 Created`

```json
{
  "data": {
    "academy": {},
    "user": {},
    "team": {}
  },
  "meta": {}
}
```

### Errors

* `404 Not Found` si la categoría no existe.
* `409 Conflict` si la categoría está inactiva o el equipo ya existe.
* `422 Unprocessable Entity` si el payload no pasa validación.

---

# Users API

## Create User

```http
POST /api/v1/platform/users
```

### Access

* Sólo `ROLE_ROOT`.

### Purpose

Crear usuarios de plataforma o usuarios administrativos de tenant desde contexto plataforma.

### Request DTO

`CreateUserInput`

```json
{
  "full_name": "Juan Perez",
  "email": "juan@playertech.com",
  "password": "secret123",
  "role": "ROLE_ROOT",
  "academy_id": null
}
```

### Rules

* Si `role = ROLE_ROOT`, `academy_id` debe ser `null`.
* Si `role != ROLE_ROOT`, `academy_id` es obligatorio.
* Un usuario tenant no puede invocar este endpoint.

### Success

`201 Created`

```json
{
  "data": {
    "id": "uuid",
    "full_name": "Juan Perez",
    "email": "juan@playertech.com",
    "academy_id": null,
    "role": "ROLE_ROOT",
    "status": "ACTIVE"
  },
  "meta": {}
}
```

### Errors

* `403 Forbidden` si el usuario autenticado no es `ROLE_ROOT`.
* `409 Conflict` si el correo ya existe.
* `422 Unprocessable Entity` si el payload no pasa validación.

---

## List Users

```http
GET /api/v1/platform/users
```

### Access

* Sólo `ROLE_ROOT`.

### Purpose

Listar usuarios visibles desde plataforma.

---

## Show User

```http
GET /api/v1/platform/users/{userId}
```

### Access

* Sólo `ROLE_ROOT`.

### Purpose

Ver el detalle de un usuario desde plataforma.

---

## Update User

```http
PUT /api/v1/platform/users/{userId}
```

### Access

* Sólo `ROLE_ROOT`.

### Purpose

Actualizar usuarios de plataforma o usuarios tenant desde contexto plataforma.

---

## Disable User

```http
POST /api/v1/platform/users/{userId}/disable
```

### Access

* Sólo `ROLE_ROOT`.

### Purpose

Desactivar un usuario desde plataforma.

---

## Enable User

```http
POST /api/v1/platform/users/{userId}/enable
```

### Access

* Sólo `ROLE_ROOT`.

### Purpose

Reactivar un usuario desde plataforma.

---

## Tenant Users

```http
GET /api/v1/academy/users
POST /api/v1/academy/users
GET /api/v1/academy/users/{userId}
PUT /api/v1/academy/users/{userId}
POST /api/v1/academy/users/{userId}/disable
POST /api/v1/academy/users/{userId}/enable
```

### Access

* Sólo usuario autenticado con tenant context.

### Rules

* `academy_id` se toma del JWT.
* No se acepta `academy_id` manual en el request.
* Todas las operaciones quedan restringidas al tenant actual.

---

# Example HTTP Files

La carpeta `http/` agrupa ejemplos de consumo por módulo:

* `http/auth.http`
* `http/academy.http`
* `http/category.http`
* `http/players.http`
* `http/users.http`
* `http/venue.http`

La colección `postman/` complementa estos ejemplos para validación manual.

Para pruebas manuales sin Swagger:

* `http/auth.http`
* `http/academy.http`
* `http/users.http`
