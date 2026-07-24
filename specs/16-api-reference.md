# 16-api-reference.md

# API Reference

Este documento sirve como referencia operativa de la API HTTP de PlayerTech mientras no exista Swagger/OpenAPI interactivo.

La convención general de respuestas y errores está definida en `specs/04-api.md`.

## Canonical Use

Para trabajo individual y validación manual, este documento debe considerarse la referencia HTTP operativa principal junto con la colección de Postman.

`specs/04-api.md` conserva el marco general de la API, pero las decisiones de contrato ya estabilizadas deben vivir aquí para evitar duplicidad.

## Response Strategy

La implementación actual expone la API mediante `Response DTOs` serializables:

* Cada caso de uso devuelve un objeto de respuesta inmutable.
* Las respuestas públicas se materializan con `toArray()`.
* Los anidados se modelan como DTOs compuestos, no como entidades Doctrine.

Si un contrato crece en complejidad o reutilización, el estándar preferido es:

* `Response DTO` para salidas simples.
* `ResponseTransformer` para salidas con composición o filtrado de relaciones.
* `Presenter` si se necesita formalizar el paso entre Application y Presentation.

## Postman As Reference

Hasta que exista Swagger/OpenAPI interactivo, la colección de Postman se considera la referencia operativa de contrato HTTP para el front y para QA.

La colección vigente está organizada por módulos y agrupa los endpoints por contexto funcional:

- `Auth`
- `Public`
- `Platform`
- `Academy`
- `Membership`
- `Player`
- `Team`
- `Category`
- `Venue`
- `PaymentConcept`
- `Charge`
- `Payments`
- `Staff`
- `TeamAssignment`
- `Dashboard`

Reglas del estándar:

* Cada request debe incluir método, URL, headers y body de ejemplo.
* Cada request relevante debe incluir al menos un response de éxito.
* Los endpoints con errores previsibles deben incluir ejemplos de error cuando el contrato ya esté estabilizado.
* Los ejemplos de response deben reflejar camelCase y la forma real de los DTOs.
* Si un endpoint devuelve `data` y `meta`, ese debe ser el ejemplo mostrado en Postman.
* Si el endpoint devuelve `204 No Content`, la colección debe indicarlo explícitamente.
* La documentación en `specs/` debe mantenerse alineada con la colección de Postman.

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

## List Response Examples

Los listados más visibles para frontend usan `data` como arreglo de DTOs resumidos y `meta` con el estado de la paginación. Los campos del primer elemento de `data` son los que la UI debe usar para construir columnas, badges y acciones.

### Academies

```json
{
  "data": [
    {
      "id": "uuid",
      "name": "Academia PlayerTech",
      "contactEmail": "contacto@academiaplayertech.com",
      "registrationSource": "platform",
      "status": "ACTIVE"
    }
  ],
  "meta": {
    "page": 1,
    "per_page": 20,
    "total": 1,
    "total_pages": 1,
    "has_next": false,
    "has_prev": false
  }
}
```

### Academy Tax Profile

```json
{
  "data": {
    "academyId": "uuid",
    "taxIdType": "NIT",
    "taxIdNumber": "901234567-8",
    "taxCheckDigit": "8",
    "taxRegime": "RESPONSABLE_IVA",
    "billingEmail": "facturacion@academiaplayertech.com"
  },
  "meta": {}
}
```

### Academy Profile

```json
{
  "data": {
    "id": "uuid",
    "name": "Academia PlayerTech",
    "contactEmail": "contacto@academiaplayertech.com",
    "phone": "+573125953354",
    "country": "Colombia",
    "department": "Cundinamarca",
    "taxIdType": "NIT",
    "taxIdNumber": "901234567-8",
    "taxCheckDigit": "8",
    "taxRegime": "RESPONSABLE_IVA",
    "billingEmail": "facturacion@academiaplayertech.com",
    "registrationSource": "platform",
    "address": "Av. Principal 123",
    "city": "Bogota",
    "shield": {
      "path": "var/storage/media/local/academies/01J.../shield/original/01K....png",
      "url": "https://api.playertech.test/media/academies/01J.../shield/01K....png",
      "mimeType": "image/png",
      "size": 184233,
      "checksum": "sha256:..."
    },
    "status": "ACTIVE",
    "audit": {
      "createdAt": "2026-07-11T00:00:00+00:00",
      "createdBy": "019f0000-0000-7000-8000-000000000000",
      "updatedAt": null,
      "updatedBy": null
    }
  },
  "meta": {}
}
```

### Users

```json
{
  "data": [
    {
      "id": "uuid",
      "fullName": "Root Admin",
      "email": "admin@playertech.com",
      "academyId": null,
      "roles": ["ROLE_ROOT", "ROLE_USER"],
      "role": "ROLE_ROOT",
      "status": "ACTIVE"
    }
  ],
  "meta": {
    "page": 1,
    "per_page": 20,
    "total": 1,
    "total_pages": 1,
    "has_next": false,
    "has_prev": false
  }
}
```

### Venues

Listado soporta paginación estándar y `sort` seguro.

- `sort=created_at`
- `sort=name`
- `sort=address`
- `sort=city`
- `sort=country`
- `sort=department`
- `sort=phone`
- `sort=status`

El backend normaliza estos valores antes de armar el `ORDER BY`, por lo que el frontend puede seguir enviando `created_at` como sort por defecto.

```json
{
  "data": [
    {
      "id": "uuid",
      "name": "Cancha Principal",
      "address": "Av. Principal 123",
      "city": "Bogota",
      "country": "Colombia",
      "department": "Cundinamarca",
      "phone": "+573125953354",
      "notes": "Canchas de futbol 11",
      "isPrimary": true,
      "status": "ACTIVE"
    }
  ],
  "meta": {
    "page": 1,
    "per_page": 20,
    "total": 1,
    "total_pages": 1,
    "has_next": false,
    "has_prev": false
  }
}
```

### Categories

Listado soporta paginación estándar y `sort` seguro.

- `sort=created_at`
- `sort=categoryKey`
- `sort=name`
- `sort=minAge`
- `sort=maxAge`
- `sort=description`
- `sort=status`

El backend genera `categoryKey` a partir del `name` y devuelve ese valor en create, list y show. El frontend no debe enviarlo en el payload.

```json
{
  "data": [
    {
      "id": "uuid",
      "academyId": "uuid",
      "categoryKey": "sub-12",
      "name": "Sub 12",
      "minAge": 11,
      "maxAge": 12,
      "description": "Categoria formativa",
      "status": "ACTIVE"
    }
  ],
  "meta": {
    "page": 1,
    "per_page": 20,
    "total": 1,
    "total_pages": 1,
    "has_next": false,
    "has_prev": false
  }
}
```

### Category Options

```http
GET /api/v1/academy/categories/options
```

### Access

* Tenant authenticated.

### Purpose

Exponer un listado liviano de categorías activas para selects y combos sin paginación.

### Response

```json
{
  "data": [
    {
      "id": "uuid",
      "categoryKey": "sub-12",
      "name": "Sub 12",
      "status": "ACTIVE"
    }
  ],
  "meta": {}
}
```

### Rules

* Devuelve solo categorías activas y no borradas lógicamente.
* El orden es alfabético por `name`.
* El frontend no debe enviar paginación para este endpoint.

### Teams

Listado soporta paginación estándar y `sort` seguro.

- `sort=created_at`
- `sort=name`
- `sort=category_id`
- `sort=status`

El backend normaliza estos valores antes de armar el `ORDER BY`, por lo que el frontend no necesita enviar paths internos de Doctrine.

```json
{
  "data": [
    {
      "id": "uuid",
      "categoryId": "uuid",
      "categoryName": "Category 1",
      "name": "Sub 12 A",
      "status": "ACTIVE"
    }
  ],
  "meta": {
    "page": 1,
    "per_page": 20,
    "total": 1,
    "total_pages": 1,
    "has_next": false,
    "has_prev": false
  }
}
```

### Team Create

```http
POST /api/v1/academy/teams
```

### Request DTO

`CreateTeamRequest`

```json
{
  "categoryId": "uuid",
  "name": "Sub 12 A"
}
```

### Success

`201 Created`

```json
{
  "data": {
    "id": "uuid",
    "academyId": "uuid",
    "categoryId": "uuid",
    "categoryName": "Category 1",
    "name": "Sub 12 A",
    "status": "ACTIVE"
  },
  "meta": {}
}
```

### Team Update

```http
PUT /api/v1/academy/teams/{teamId}
```

### Request DTO

`UpdateTeamRequest`

```json
{
  "categoryId": "uuid",
  "name": "Sub 12 A"
}
```

### Success

`200 OK`

```json
{
  "data": {
    "id": "uuid",
    "academyId": "uuid",
    "categoryId": "uuid",
    "categoryName": "Category 1",
    "name": "Sub 12 A",
    "status": "ACTIVE"
  },
  "meta": {}
}
```

### Team Activate / Inactivate

```http
PATCH /api/v1/academy/teams/{teamId}/inactivate
PATCH /api/v1/academy/teams/{teamId}/activate
```

### Success

`204 No Content`

### Players

```json
{
  "data": [
    {
      "id": "uuid",
      "categoryId": "uuid",
      "documentType": "DNI",
      "firstName": "Juan",
      "lastName": "Perez",
      "birthDate": "2013-05-12",
      "documentNumber": "12345678",
      "nationality": "Colombiana",
      "gender": "Masculino",
      "federationId": "F001",
      "dominantFoot": "Derecho",
      "status": "ACTIVE"
    }
  ],
  "meta": {
    "page": 1,
    "per_page": 20,
    "total": 1,
    "total_pages": 1,
    "has_next": false,
    "has_prev": false
  }
}
```

### Payment Concepts

```json
{
  "data": [
    {
      "id": "uuid",
      "code": "MATRICULA",
      "name": "Matrícula",
      "status": "ACTIVE"
    }
  ],
  "meta": {
    "page": 1,
    "per_page": 20,
    "total": 1,
    "total_pages": 1,
    "has_next": false,
    "has_prev": false
  }
}
```

### Pending Charges

```json
{
  "data": [
    {
      "id": "uuid",
      "membershipId": "uuid",
      "paymentConceptId": "uuid",
      "description": "Cuota pendiente de matrícula",
      "amount": "150000.00",
      "status": "PENDING"
    }
  ],
  "meta": {
    "page": 1,
    "per_page": 20,
    "total": 1,
    "total_pages": 1,
    "has_next": false,
    "has_prev": false
  }
}
```

### Team Assignments

```json
{
  "data": [
    {
      "id": "uuid",
      "playerId": "uuid",
      "teamId": "uuid",
      "startDate": "2026-07-08",
      "endDate": null,
      "isPrimary": false
    }
  ],
  "meta": {
    "page": 1,
    "per_page": 20,
    "total": 1,
    "total_pages": 1,
    "has_next": false,
    "has_prev": false
  }
}
```

### Team Staff

```json
{
  "data": [
    {
      "assignmentId": "uuid",
      "staffId": "uuid",
      "userId": "uuid",
      "teamId": "uuid",
      "role": "HEAD_COACH"
    }
  ],
  "meta": {}
}
```

### Membership History

```json
{
  "data": [
    {
      "id": "uuid",
      "status": "ACTIVE",
      "startedAt": "2026-07-07T00:00:00+00:00",
      "endedAt": null,
      "primaryGuardianId": "uuid"
    }
  ],
  "meta": {}
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

## Tenant Activation

```http
GET /api/v1/public/tenants/activate/{token}
```

### Access

* Público.

### Purpose

Activar la cuenta del usuario owner/admin inicial del tenant al seguir el enlace enviado por correo.

### Rules

* Si el token existe y sigue vigente, el backend activa la cuenta.
* Si el usuario vuelve a abrir el mismo enlace y el token sigue disponible, la respuesta incluye `alreadyActivated = true`.
* Si el token no existe o expiró, el backend responde `404 Not Found` con Problem Details.

### Success

```json
{
  "data": {
    "email": "admin@academiaplayertech.com",
    "status": "ACTIVE",
    "activated": true,
    "alreadyActivated": false
  },
  "meta": {}
}
```

## Public Onboarding Categories

```http
GET /api/v1/public/categories
```

### Access

* Público.

### Purpose

Exponer el catálogo global de categorías de onboarding para que el frontend pueda poblar el selector inicial del alta de tenant sin hardcodear valores.

### Response

```json
{
  "data": [
    {
      "id": "uuid",
      "code": "SUB-14",
      "name": "Sub 14",
      "minAge": 13,
      "maxAge": 14,
      "description": "Categoria formativa",
      "status": "ACTIVE"
    }
  ],
  "meta": {}
}
```

### Rules

* El catálogo público representa plantillas de onboarding, no categorías de academia.
* El identificador expuesto por este endpoint es el `onboardingCategoryId`.
* Las categorías del catálogo deben estar activas para poder seleccionarse.

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
  "country": "Colombia",
  "department": "Cundinamarca",
  "city": "Arequipa",
  "address": "Jr. Principal 123",
  "onboardingCategoryId": "uuid",
  "teamName": "Sub 12 A",
  "acceptedTerms": true,
  "acceptedDataProcessing": true
}
```

### Rules

* `onboardingCategoryId` es obligatorio y debe existir en el catálogo público.
* La categoría del catálogo debe estar activa.
* Durante el signup se crea una categoría nueva dentro de la academia usando la definición seleccionada.
* `teamName` es obligatorio y su longitud máxima es 80 caracteres.
* No puede existir otro equipo con el mismo nombre dentro de la misma categoría clonada de la academia.
* `phone`, `country`, `department`, `city` y `address` son obligatorios en el signup público.
* El signup crea también la sede principal en `venues`, marcándola como `isPrimary = true` y persistiendo `country`, `department`, `city` y `address`.
* El tenant queda en onboarding con correo de activación pendiente para el usuario owner/admin inicial.

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

* `404 Not Found` si el catálogo no existe.
* `409 Conflict` si el catálogo está inactivo o el equipo ya existe.
* `422 Unprocessable Entity` si el payload no pasa validación.

## Public User Activation

```http
GET /api/v1/public/users/activate/{token}
POST /api/v1/public/users/activate/{token}
```

### Access

* Público.

### Purpose

Separar la experiencia pública de activación en dos pasos:

* `GET` redirige al frontend público `APP_AUTH_FRONTEND_URL/activate-account/{token}`.
* `POST` confirma la contraseña y activa la cuenta.

### Rules

* El correo de invitación debe apuntar al frontend público, no al API.
* `POST` requiere `password` y `password_confirmation`.
* `POST` conserva la transición real del usuario de `PENDING_ACTIVATION` a `ACTIVE`.
* Si el token es inválido o expiró, el backend responde `404 Not Found` con Problem Details.

### Success

`302 Found` para `GET` con `Location` hacia el frontend.

`200 OK` para `POST` con el usuario activado.

--- 

# Platform Tenant Provisioning

## Create Platform Tenant

```http
POST /api/v1/platform/academies
```

### Access

* Sólo `ROLE_ROOT`.

### Purpose

Provisionar un tenant completo desde plataforma, creando academia, usuario owner/admin inicial, correo de bienvenida o activación y primer equipo inicial.

### Request DTO

`ProvisionTenantInput`

```json
{
  "name": "Academia PlayerTech",
  "contactEmail": "contacto@academiaplayertech.com",
  "phone": "+57 312 555 8888",
  "country": "Colombia",
  "department": "Cundinamarca",
  "city": "Bogota",
  "adminName": "Juan Perez",
  "adminEmail": "admin@academiaplayertech.com",
  "onboardingCategoryId": "uuid",
  "teamName": "Sub 12 A"
}
```

### Rules

* `name`, `contactEmail`, `adminName`, `adminEmail`, `onboardingCategoryId` y `teamName` son obligatorios.
* El `contactEmail` de la academia no puede existir previamente.
* El `adminEmail` no puede existir previamente en `users`.
* `onboardingCategoryId` debe existir en el catálogo público y estar activa.
* Durante la provision se crea una categoría nueva dentro de la academia usando la definición seleccionada.
* `teamName` debe ser único dentro de la academia y de la categoría clonada.
* `address` sigue disponible en provisionamiento de plataforma, pero no en signup público.
* El tenant queda listo para operar al finalizar la operación.

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

* `404 Not Found` si el catálogo no existe.
* `409 Conflict` si el correo de academia o el correo del admin inicial ya existen.
* `409 Conflict` si el equipo inicial ya existe.
* `422 Unprocessable Entity` si el payload no pasa validación.

---

# Academy Tax Profile

> UI label recomendado para frontend: `Información fiscal`.
> El backend mantiene el contrato técnico actual con `taxIdType`, `taxIdNumber`, `taxRegime` y `billingEmail`.
> A nivel de negocio, la academia maneja un único perfil fiscal principal o default.
> Para el MVP se agrega `taxCheckDigit` como dígito de verificación opcional.

## Tenant Context

```http
GET /api/v1/academy/context
```

### Access

* Usuario autenticado con contexto tenant.

### Purpose

Obtener el contexto operativo de la sesión.

### Success

`200 OK`

```json
{
  "data": {
    "mode": "tenant",
    "userId": "uuid",
    "academyId": "uuid",
    "role": "ROLE_ACADEMY_ADMIN",
    "roles": ["ROLE_ACADEMY_ADMIN", "ROLE_USER"]
  },
  "meta": {}
}
```

### Rules

* Este endpoint no devuelve el perfil de la academia.
* Se usa para resolver permisos, contexto y navegación.

## Show Academy Profile

```http
GET /api/v1/academy/me
```

### Access

* Usuario autenticado con contexto tenant.

### Purpose

Consultar el perfil general de la academia actual.

### Success

`200 OK`

Usa el contrato `Academy Profile` definido en la sección de ejemplos.

### Rules

* Este endpoint devuelve el perfil de la academia, no el contexto tenant.
* Debe ser el contrato que use el front para renderizar la pantalla de academia.

## Update Academy Profile

```http
PUT /api/v1/academy/me
```

### Access

* Usuario autenticado con contexto tenant.

### Purpose

Actualizar el perfil general de la academia actual.

### Success

`200 OK`

Usa el contrato `Academy Profile` definido en la sección de ejemplos.

### Rules

* Este endpoint actualiza el perfil de la academia.
* No debe usarse para leer `mode`, `userId` o `roles`.

## Update Academy Shield

```http
POST /api/v1/academy/me/shield
```

### Access

* Usuario autenticado con contexto tenant.

### Purpose

Subir o reemplazar el escudo institucional de la academia actual.

### Request

Enviar `multipart/form-data` con el campo `shield`.

### Success

`201 Created`

Usa el contrato `Academy Profile` definido en la sección de ejemplos.

### Rules

* El archivo `shield` se maneja como contenido binario, no como parte del JSON.
* Este endpoint actualiza sólo la imagen institucional de la academia.

## Delete Academy Shield

```http
DELETE /api/v1/academy/me/shield
```

### Access

* Usuario autenticado con contexto tenant.

### Purpose

Eliminar el escudo institucional actual de la academia.

### Success

`204 No Content`

### Rules

* Si la academia no tiene escudo, el backend debe responder igualmente `204 No Content`.
* Este endpoint no devuelve body.

## Show Academy Tax Profile

```http
GET /api/v1/academy/me/tax-profile
```

### Access

* Usuario autenticado con contexto tenant.

### Purpose

Consultar la información tributaria registrada para la academia actual.

### Field mapping recomendado

* `taxIdType` -> tipo de identificación
* `taxIdNumber` -> número de identificación
* `taxCheckDigit` -> dígito de verificación
* `taxRegime` -> condición o régimen tributario
* `billingEmail` -> correo para facturación

> Los campos `razón social`, `dirección fiscal`, `ciudad`, `país` y `dígito de verificación` forman parte del perfil fiscal funcional, pero pueden seguir evolucionando en el contrato técnico según el alcance de la academia.

## Update Academy Tax Profile

```http
PUT /api/v1/academy/me/tax-profile
```

### Access

* Usuario autenticado con contexto tenant.

### Purpose

Actualizar la información tributaria de la academia actual.

### Validations

* `taxIdType`, si se envía, debe ser una cadena no vacía y de longitud válida.
* `taxIdNumber`, si se envía, debe ser una cadena no vacía y de longitud válida.
* `taxCheckDigit`, si se envía, debe ser una cadena corta no vacía.
* `taxRegime`, si se envía, debe ser una cadena no vacía y de longitud válida.
* `billingEmail`, si se envía, debe ser un correo válido.
* El perfil sigue siendo único por academia.

### Request DTO

`UpdateAcademyTaxProfileRequest`

```json
{
  "taxIdType": "NIT",
  "taxIdNumber": "901234567-8",
  "taxCheckDigit": "8",
  "taxRegime": "RESPONSABLE_IVA",
  "billingEmail": "facturacion@academiaplayertech.com"
}
```

## Platform Academy Tax Profile

```http
GET /api/v1/platform/academies/{academyId}/tax-profile
PUT /api/v1/platform/academies/{academyId}/tax-profile
```

### Access

* Sólo `ROLE_ROOT`.

### Purpose

Consultar o actualizar el perfil tributario de una academia desde plataforma.

---

# Payment Receipts

## Show Payment Receipt

```http
GET /api/v1/academy/payments/{paymentId}/receipt
```

### Access

* Usuario autenticado con contexto tenant.

### Purpose

Obtener el comprobante operativo de un pago registrado.

### Success

`200 OK`

```json
{
  "data": {
    "receiptNumber": "RCPT-20260714-000050",
    "paymentId": "uuid",
    "academyId": "uuid",
    "academyTaxIdType": "NIT",
    "academyTaxIdNumber": "901234567-8",
    "academyTaxCheckDigit": "8",
    "academyTaxRegime": "RESPONSABLE_IVA",
    "academyBillingEmail": "facturacion@academiaplayertech.com",
    "academyAddress": "Av. Principal 123",
    "academyCity": "Bogota",
    "guardianId": "uuid",
    "playerId": "uuid",
    "paymentDate": "2026-07-14",
    "amount": "150000.00",
    "method": "CASH",
    "conceptCode": "MATRICULA",
    "conceptName": "Matrícula",
    "allocations": [
      {
        "chargeId": "uuid",
        "amount": 150000
      }
    ]
  },
  "meta": {}
}
```

## Link Fiscal Attachment

```http
POST /api/v1/academy/fiscal-attachments
```

### Access

* Usuario autenticado con contexto tenant.

### Purpose

Vincular un soporte fiscal en PDF descargado desde otra aplicación a un pago registrado.

### Request DTO

`LinkFiscalAttachmentRequest`

```json
{
  "paymentId": "uuid",
  "providerName": "App Externa",
  "documentNumber": "PDF-2026-000123",
  "documentUrl": "https://external-system.example/documents/PDF-2026-000123",
  "status": "ATTACHED"
}
```

### Success

`201 Created`

```json
{
  "data": {
    "id": "uuid",
    "academyId": "uuid",
    "paymentId": "uuid",
    "providerName": "App Externa",
    "documentNumber": "PDF-2026-000123",
    "documentUrl": "https://external-system.example/documents/PDF-2026-000123",
    "status": "ATTACHED"
  },
  "meta": {}
}
```

### Errors

* `404 Not Found` si el pago no existe.
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

---

# Guardians API

## List Guardians

```http
GET /api/v1/academy/guardians?page=1&per_page=20&sort=auditTrail.createdAt.value&direction=DESC
```

### Purpose

Consultar el listado paginado de acudientes de la academia actual.

## Show Guardian

```http
GET /api/v1/academy/guardians/{guardianId}
```

## Create Guardian

```http
POST /api/v1/academy/guardians
```

### Access

* Usuario autenticado con tenant context.

### Purpose

Registrar un acudiente dentro de la academia actual con su parentesco.

### Request DTO

`CreateLegalGuardianRequest`

```json
{
  "firstName": "Maria",
  "lastName": "Lopez",
  "phone": "+51 999 111 222",
  "email": "maria@example.com",
  "documentType": "DNI",
  "documentNumber": "12345678",
  "address": "Av. Central 123",
  "relationship": "Madre"
}
```

### Success

`201 Created`

```json
{
  "data": {
    "id": "uuid",
    "academyId": "uuid",
    "firstName": "Maria",
    "lastName": "Lopez",
    "phone": "+51 999 111 222",
    "email": "maria@example.com",
    "documentType": "DNI",
    "documentNumber": "12345678",
    "address": "Av. Central 123",
    "relationship": "Madre",
    "status": "ACTIVE"
  },
  "meta": {}
}
```

---

# Player Guardians API

## List Player Guardians

```http
GET /api/v1/academy/players/{playerId}/guardians
```

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

* Usar la colección de Postman como referencia operativa.

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

# Payment Concepts API

## Create Payment Concept

```http
POST /api/v1/academy/payment-concepts
```

### Request

```json
{
  "name": "Matrícula",
  "description": "Cobro inicial"
}
```

### Success

`201 Created`

```json
{
  "data": {
    "id": "uuid",
    "academyId": "uuid",
    "code": "MATRICULA",
    "name": "Matrícula",
    "description": "Cobro inicial",
    "status": "ACTIVE"
  },
  "meta": {}
}
```

### Rules

* `code` no se envía desde frontend.
* `code` se genera en backend desde `name`.
* Si hay colisión, el backend resuelve el código con sufijo determinístico.

## Update Payment Concept

```http
PUT /api/v1/academy/payment-concepts/{paymentConceptId}
```

### Request

```json
{
  "name": "Matrícula",
  "description": "Cobro inicial"
}
```

### Rules

* `code` no es editable desde frontend.
* El código original se conserva en la actualización.

---

# Staff API

## List Staff

```http
GET /api/v1/academy/staff?page=1&per_page=20&sort=created_at&direction=DESC
```

### Access

* Usuario autenticado con contexto tenant.

### Purpose

Listar el staff activo o histórico de la academia actual con paginación uniforme.

### Success

`200 OK`

```json
{
  "data": [
    {
      "id": "uuid",
      "academyId": "uuid",
      "userId": "uuid",
      "fullName": "Juan Perez",
      "email": "juan@academiaplayertech.com",
      "role": "ROLE_COACH",
      "status": "ACTIVE",
      "userStatus": "PENDING_ACTIVATION"
    }
  ],
  "meta": {
    "page": 1,
    "per_page": 20,
    "total": 1,
    "total_pages": 1,
    "has_next": false,
    "has_prev": false
  }
}
```

## Staff Options

```http
GET /api/v1/academy/staff/options?role=ROLE_COACH
```

### Access

* Usuario autenticado con contexto tenant.

### Purpose

Exponer un listado liviano para selects y combos del frontend, reutilizable como patrón para otros módulos.

### Success

`200 OK`

```json
{
  "data": [
    {
      "id": "uuid",
      "label": "Juan Perez"
    }
  ],
  "meta": {}
}
```

### Rules

* Sólo devuelve `id` y `label`.
* Filtra por `academy_id` del tenant actual.
* Excluye registros borrados lógicamente y usuarios inactivos.
* Si se envía `role`, el backend filtra por ese rol.
* Es el patrón base para futuros endpoints `/{entity}/options`.

## Resend Staff Activation

```http
POST /api/v1/academy/staff/{userId}/activation/resend
```

### Access

* Usuario autenticado con contexto tenant.

### Purpose

Reenviar el correo de activación de un staff pendiente, generando un nuevo token y dejando inválido el anterior.

### Rules

* El usuario debe existir dentro del tenant actual.
* El usuario debe estar en `PENDING_ACTIVATION`.
* Si el usuario ya está activo, el backend responde `400 Bad Request`.
* El correo reenviado debe apuntar a `APP_AUTH_FRONTEND_URL/activate-account/{token}`.

### Success

`200 OK`

```json
{
  "data": {
    "id": "uuid",
    "fullName": "Juan Perez",
    "email": "juan@academiaplayertech.com",
    "academyId": "uuid",
    "roles": ["ROLE_COACH", "ROLE_USER"],
    "role": "ROLE_COACH",
    "status": "PENDING_ACTIVATION"
  },
  "meta": {}
}
```

## Onboard Staff Member

```http
POST /api/v1/academy/staff/onboarding
```

### Access

* Usuario autenticado con contexto tenant.

### Purpose

Crear el usuario, registrar el staff y resolver el acceso por invitación o contraseña inicial en una sola operación.

### Request DTO

`CreateStaffMemberInput`

```json
{
  "fullName": "Juan Perez",
  "email": "juan@academiaplayertech.com",
  "role": "ROLE_COACH",
  "sendInvitation": true
}
```

### Rules

* `fullName`, `email` y `role` son obligatorios.
* `role` puede ser `ROLE_ACADEMY_ADMIN` o `ROLE_COACH`.
* Si `sendInvitation = true`, el sistema envía correo y deja la cuenta pendiente de activación.
* Si `sendInvitation = false`, `password` y `passwordConfirmation` son obligatorios.
* La respuesta devuelve el usuario creado, el staff y el modo de acceso aplicado.

### Success

`201 Created`

```json
{
  "data": {
    "user": {
      "id": "uuid",
      "fullName": "Juan Perez",
      "email": "juan@academiaplayertech.com",
      "academyId": "uuid",
      "roles": ["ROLE_COACH", "ROLE_USER"],
      "role": "ROLE_COACH",
      "status": "PENDING_ACTIVATION"
    },
    "staff": {
      "id": "uuid",
      "academyId": "uuid",
      "userId": "uuid",
      "status": "ACTIVE"
    },
    "accessMode": "INVITATION"
  },
  "meta": {}
}
```

### Errors

* `409 Conflict` si el correo ya existe.
* `422 Unprocessable Entity` si el payload no pasa validación.

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

### Business rules

* No se admite `markAsPrimary` en este contrato.
* Si el jugador ya tiene una asignación activa para el mismo equipo, el alta se rechaza.
* Si la asignación creada luego debe quedar como principal, el flujo recomendado es llamar después a `PATCH /primary`.

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

### Business rules

* Si la asignación finalizada era principal y existe otra asignación activa del mismo jugador, el backend la promueve automáticamente como principal.
* Si no existe otra asignación activa, el jugador queda sin principal hasta una nueva asignación o marca explícita.

## View Player Team Assignments

```http
GET /api/v1/academy/team-assignments/players/{playerId}
```

### Purpose

Consultar las asignaciones deportivas de un jugador.

### Success

`200 OK`

```json
{
  "data": [
    {
      "id": "uuid",
      "playerId": "uuid",
      "teamId": "uuid",
      "startDate": "2026-07-08",
      "endDate": null,
      "isPrimary": true,
      "team": {
        "id": "uuid",
        "name": "Sub 12 A",
        "categoryId": "uuid",
        "categoryName": "Sub 12"
      }
    }
  ],
  "meta": {}
}
```

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


