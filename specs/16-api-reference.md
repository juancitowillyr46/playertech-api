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

Para pruebas manuales sin Swagger:

* `http/auth.http`
* `http/academy.http`
* `http/users.http`

