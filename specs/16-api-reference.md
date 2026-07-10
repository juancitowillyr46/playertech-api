# 16-api-reference.md

# API Reference

Este documento sirve como referencia operativa de la API HTTP de PlayerTech mientras no exista Swagger/OpenAPI interactivo.

La convención general de respuestas y errores está definida en `specs/04-api.md`.

## Response Strategy

La implementación actual expone la API mediante `Response DTOs` serializables:

* Cada caso de uso devuelve un objeto de respuesta inmutable.
* Las respuestas públicas se materializan con `toArray()`.
* Los anidados se modelan como DTOs compuestos, no como entidades Doctrine.

Si un contrato crece en complejidad o reutilización, el estándar preferido es:

* `Response DTO` para salidas simples.
* `ResponseTransformer` para salidas con composición o filtrado de relaciones.
* `Presenter` si se necesita formalizar el paso entre Application y Presentation.

## Naming Convention

* Query params: `snake_case`.
* Request bodies: `camelCase`.
* Respuestas JSON: `camelCase`.

## List Pagination

Los endpoints `GET` de listados que alimentan tablas o grids exponen paginación uniforme:

* `page`
* `per_page`
* `sort`
* `direction`

La respuesta usa el contrato:

```json
{
  "data": [],
  "meta": {
    "page": 1,
    "per_page": 20,
    "total": 0,
    "total_pages": 1,
    "has_next": false,
    "has_prev": false
  }
}
```

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
  "contactEmail": "tenant.demo@example.com",
  "contactName": "Juan Perez",
  "password": "secret12345",
  "phone": "+51 987 654 321",
  "address": "Jr. Secundario 789",
  "city": "Arequipa",
  "categoryId": "uuid",
  "teamName": "Sub 12 A"
}
```

### Rules

* `categoryId` es obligatorio y debe existir.
* La categoría debe estar activa.
* `teamName` es obligatorio y su longitud máxima es 80 caracteres.
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
  "fullName": "Juan Perez",
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
    "fullName": "Juan Perez",
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
* `http/guardians.http`
* `http/players.http`
* `http/player-guardians.http`
* `http/memberships.http`
* `http/users.http`
* `http/venue.http`

La colección `postman/` complementa estos ejemplos para validación manual.

---

# Guardians API

## Create Guardian

```http
POST /api/v1/academy/guardians
```

### Access

* Usuario autenticado con tenant context.

### Purpose

Registrar un acudiente dentro de la academia actual.

---

# Player Guardians API

## Associate Guardian

```http
POST /api/v1/academy/players/{playerId}/guardians
```

## Change Primary Guardian

```http
PATCH /api/v1/academy/players/{playerId}/guardians/{guardianId}/primary
```

## Remove Guardian Association

```http
DELETE /api/v1/academy/players/{playerId}/guardians/{guardianId}
```

Para pruebas manuales sin Swagger:

* `http/auth.http`
* `http/academy.http`
* `http/users.http`

---

# Membership API

## Create Membership

```http
POST /api/v1/academy/memberships
```

### Access

* Usuario autenticado con tenant context.

### Purpose

Crear la matrícula activa de un jugador asociando un acudiente principal responsable.

### Request DTO

`CreateMembershipCommand`

```json
{
  "playerId": "uuid",
  "primaryGuardianId": "uuid"
}
```

### Rules

* `playerId` es obligatorio y debe existir dentro del tenant actual.
* `primaryGuardianId` es obligatorio y debe existir dentro del tenant actual.
* Solo puede existir una matrícula activa por jugador dentro de una academia.

### Success

`201 Created`

```json
{
  "data": {
    "id": "uuid",
    "academy_id": "uuid",
    "playerId": "uuid",
    "primaryGuardianId": "uuid",
    "status": "ACTIVE",
    "started_at": "2026-07-07T00:00:00+00:00",
    "ended_at": null
  },
  "meta": {}
}
```

### Errors

* `409 Conflict` si el jugador ya tiene una matrícula activa.
* `422 Unprocessable Entity` si el payload no pasa validación.

## Show Active Membership

```http
GET /api/v1/academy/memberships/{playerId}/active
```

### Access

* Usuario autenticado con tenant context.

### Purpose

Consultar la matrícula activa de un jugador y su acudiente principal.

### Success

`200 OK`

```json
{
  "data": {
    "id": "uuid",
    "academy_id": "uuid",
    "playerId": "uuid",
    "primaryGuardianId": "uuid",
    "status": "ACTIVE",
    "started_at": "2026-07-07T00:00:00+00:00",
    "ended_at": null
  },
  "meta": {}
}
```

### Errors

* `404 Not Found` si no existe matrícula activa.

---

# Staff API

## Register Staff Member

```http
POST /api/v1/academy/staff
```

### Access

* Usuario autenticado con tenant context.

### Purpose

Registrar un usuario administrativo de academia como miembro del staff.

### Request DTO

`RegisterStaffMemberRequest`

```json
{
  "userId": "uuid"
}
```

### Success

`201 Created`

```json
{
  "data": {
    "id": "uuid",
    "academy_id": "uuid",
    "userId": "uuid",
    "status": "ACTIVE"
  },
  "meta": {}
}
```

## Assign Staff To Team

```http
POST /api/v1/academy/staff/assignments
```

### Purpose

Asignar un miembro del staff a un equipo con un rol técnico.

### Request DTO

`AssignStaffToTeamRequest`

```json
{
  "staffId": "uuid",
  "teamId": "uuid",
  "role": "HEAD_COACH"
}
```

### Success

`201 Created`

```json
{
  "data": {
    "id": "uuid",
    "teamId": "uuid",
    "staffId": "uuid",
    "role": "HEAD_COACH"
  },
  "meta": {}
}
```

## Change Staff Role

```http
PATCH /api/v1/academy/staff/assignments/{assignmentId}/role
```

### Purpose

Cambiar el rol técnico de una asignación de staff.

## Remove Staff From Team

```http
PATCH /api/v1/academy/staff/assignments/{assignmentId}/remove
```

### Purpose

Retirar una asignación técnica sin borrar el historial.

### Success

`204 No Content`

## View Team Staff

```http
GET /api/v1/academy/staff/teams/{teamId}
```

### Purpose

Consultar el staff activo asignado a un equipo.

### Success

`200 OK`

```json
{
  "data": [
    {
      "assignment_id": "uuid",
      "staffId": "uuid",
      "userId": "uuid",
      "teamId": "uuid",
      "role": "HEAD_COACH"
    }
  ],
  "meta": {}
}

# TeamAssignment API

## Assign Player To Team

```http
POST /api/v1/academy/team-assignments
```

### Purpose

Asignar un jugador a un equipo dentro de la academia actual.

### Request DTO

`AssignPlayerToTeamRequest`

```json
{
  "playerId": "uuid",
  "teamId": "uuid",
  "startDate": "2026-07-08"
}
```

### Success

`201 Created`

```json
{
  "data": {
    "id": "uuid",
    "playerId": "uuid",
    "teamId": "uuid",
    "startDate": "2026-07-08",
    "endDate": null,
    "isPrimary": false
  },
  "meta": {}
}
```

## Mark Team Assignment Primary

```http
PATCH /api/v1/academy/team-assignments/{assignmentId}/primary
```

### Purpose

Marcar una asignación activa como principal.

## Finalize Team Assignment

```http
PATCH /api/v1/academy/team-assignments/{assignmentId}/finalize
```

### Purpose

Finalizar una asignación sin borrar el historial.

## View Player Team Assignments

```http
GET /api/v1/academy/team-assignments/players/{playerId}
```

### Purpose

Consultar las asignaciones deportivas de un jugador.

# Dashboard API

## Show Dashboard

```http
GET /api/v1/academy/dashboard
```

### Purpose

Obtener una vista operativa resumida con jugadores activos, matrículas vigentes y cartera pendiente.
```

## Show Membership History

```http
GET /api/v1/academy/memberships/{playerId}/history
```

### Access

* Usuario autenticado con tenant context.

### Purpose

Consultar el historial de matrículas de un jugador.

### Success

`200 OK`

```json
{
  "data": [
    {
      "id": "uuid",
      "status": "ACTIVE",
      "started_at": "2026-07-07T00:00:00+00:00",
      "ended_at": null,
      "primaryGuardianId": "uuid"
    }
  ],
  "meta": {}
}
```

## Suspend Membership

```http
PATCH /api/v1/academy/memberships/{playerId}/suspend
```

### Access

* Usuario autenticado con tenant context.

### Purpose

Suspender temporalmente la matrícula de un jugador.

### Success

`204 No Content`

### Errors

* `404 Not Found` si no existe matrícula activa.

## Withdraw Membership

```http
PATCH /api/v1/academy/memberships/{playerId}/withdraw
```

### Access

* Usuario autenticado con tenant context.

### Purpose

Retirar definitivamente la matrícula de un jugador.

### Success

`204 No Content`

### Errors

* `404 Not Found` si no existe matrícula activa.
