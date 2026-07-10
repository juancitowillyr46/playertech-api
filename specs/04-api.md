# 04-api.md

# API Overview

La API de PlayerTech será una REST API versionada, consistente y segura, diseñada para operar sobre un modelo multi-tenant con JWT.

---

# Base URL

```text
/api/v1
```

---

# API Principles

* REST puro.
* Recursos centrados en dominio.
* Tenant no expuesto en la URL.
* Respuestas consistentes.
* Errores estandarizados.
* Versionado desde el inicio.

---

# General Response Contract

## Success

Todas las respuestas exitosas usarán este formato:

```json
{
  "data": {},
  "meta": {}
}
```

## List Example

```json
{
  "data": [],
  "meta": {
    "page": 1,
    "per_page": 20,
    "total": 120,
    "total_pages": 6,
    "has_next": true,
    "has_prev": false
  }
}
```

---

# Error Contract

Los errores se devolverán con un formato tipo ProblemDetails.

## Example

```json
{
  "type": "https://api.playertech/errors/validation",
  "title": "Validation Error",
  "status": 400,
  "detail": "Invalid request payload",
  "instance": "/api/v1/players"
}
```

## Recommended Extensions

Cuando aplique, se puede incluir un bloque `errors` con detalles de validación por campo.

---

# Common HTTP Headers

## Required

```http
Authorization: Bearer {jwt}
Content-Type: application/json
Accept: application/json
```

---

# Authentication

## Login

```http
POST /api/v1/auth/login
```

### Request

```json
{
  "email": "user@academy.com",
  "password": "secret"
}
```

### Response

```json
{
  "data": {
    "token": "jwt_token_here"
  },
  "meta": {}
}
```

---

# Pagination

Los listados deben aceptar paginación estándar:

```http
GET /api/v1/players?page=1&limit=20
```

### Pagination Metadata

```json
{
  "page": 1,
  "limit": 20,
  "total": 120
}
```

---

# Filtering and Sorting

Se soportará filtrado básico por query string.

## Example

```http
GET /api/v1/players?category_id=uuid&status=ACTIVE
```

## Rules

* Los filtros permitidos deben estar documentados por recurso.
* El ordenamiento debe ser explícito y controlado.
* No se aceptan filtros arbitrarios sin contrato.

## Payload Naming

* Los query params usan `snake_case`.
* Los JSON bodies usan `camelCase`.
* Las respuestas JSON usan `camelCase`.
* La compatibilidad con respuestas históricas en `snake_case` ya no es el comportamiento esperado para contratos nuevos.

---

# Resource Conventions

## Naming

* Nombres en plural.
* Recursos en minúscula.
* IDs como UUID.

## Tenant Behavior

El tenant se resuelve desde el JWT.

No se permite enviar `academy_id` en la URL para operar el tenant.

## Platform vs Tenant Users

Las rutas bajo `/api/v1/platform/*` son exclusivas de `ROLE_ROOT`.

Las rutas bajo `/api/v1/academy/*` son exclusivas de usuarios autenticados con contexto tenant.

La creación de usuarios se separa así:

* `POST /api/v1/platform/users` para usuarios de plataforma y creación administrativa global.
* `POST /api/v1/academy/users` para creación de usuarios dentro del tenant actual.

Un usuario tenant no debe acceder a `/api/v1/platform/users`.

---

# Foundation Resources

Los recursos base que deben quedar claros desde el inicio son:

## Auth

* `POST /auth/login`

## Academies

* `POST /academies`
* `GET /academies/{id}`
* `GET /academies`

## Users

* `POST /users`
* `GET /users`
* `GET /users/{id}`

Ver referencia detallada en `specs/16-api-reference.md`.

## Venues

* `POST /venues`
* `PUT /venues/{id}`
* `GET /venues`

## Categories

* `POST /categories`
* `PUT /categories/{id}`
* `GET /categories`

## Teams

* `POST /teams`
* `PUT /teams/{id}`
* `GET /teams`

## Guardians

* `POST /guardians`
* `PUT /guardians/{id}`
* `GET /guardians/{id}`

## Players

* `POST /players`
* `PUT /players/{id}`
* `GET /players/{id}`
* `GET /players`
* `PATCH /players/{id}/photo`

## Memberships

* `POST /memberships`
* `GET /memberships/{id}`
* `GET /memberships`

## Payments

* `POST /payments`
* `GET /payments/{id}`
* `GET /payments`

---

# Media Responses

Los recursos que representen archivos o imagenes no deben devolver binarios dentro del JSON.

## Standard Shape

```json
{
  "path": "var/storage/media/local/academies/01J.../shield/original/01K....png",
  "url": "https://api.playertech.test/media/academies/01J.../shield/01K....png",
  "mimeType": "image/png",
  "size": 184233,
  "checksum": "sha256:..."
}
```

## Rules

* `path` representa la referencia interna del archivo.
* `url` representa el enlace de consumo.
* `mimeType`, `size` y `checksum` son opcionales pero recomendados.
* Si un recurso no tiene media asociada, el campo debe responder `null`.
* `Academy` usara el campo `shield` con este contrato.
* `Player` usara el campo `photo` con este contrato.

---

# Status Codes

| Code | Meaning |
| ---- | ------- |
| 200 | OK |
| 201 | Created |
| 400 | Validation Error |
| 401 | Unauthorized |
| 403 | Forbidden |
| 404 | Not Found |

---

# CQRS Mapping

La API no expone CQRS públicamente, pero internamente:

* `POST`, `PUT`, `PATCH`, `DELETE` se tratarán como comandos.
* `GET` se tratará como consulta.

---

# Versioning Strategy

Cambios incompatibles generarán una nueva versión:

```text
/api/v2
```

No se romperá el contrato de `/api/v1` sin versionar correctamente.
