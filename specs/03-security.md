# 03-security.md

# Security Overview

PlayerTech utilizará autenticación JWT stateless, autorización RBAC y aislamiento estricto por tenant para operar como SaaS multi-tenant.

---

# Security Principles

* Stateless authentication.
* RBAC simple y extensible.
* Tenant isolation obligatorio.
* No acceso cruzado entre academias.
* El tenant nunca se expone en la URL.

---

# Authentication Strategy

## Mechanism

La autenticación se realizará con:

* Email o username
* Password
* JWT

## Symfony Security

Symfony Security será la base de autenticación.

Configuración esperada:

* API stateless
* Sin sesiones en servidor
* JWT como fuente de identidad

---

# JWT Model

El token debe transportar al menos:

```json
{
  "user_id": "uuid",
  "academy_id": "uuid|null",
  "roles": ["ROLE_ROOT"],
  "iat": 1234567890,
  "exp": 1234567890
}
```

## Tenant Scope

* Para usuarios de academia, `academy_id` es obligatorio.
* Para `ROLE_ROOT`, el token puede operar sin tenant de academia o con un contexto de plataforma explícito.

---

# Authorization Model

## Roles Initials

El MVP utilizará estos roles iniciales:

* `ROLE_ROOT`
* `ROLE_ACADEMY_ADMIN`

## Future RBAC Structure

La arquitectura deberá soportar:

* `User`
* `Role`
* `Permission`
* `UserRole`
* `RolePermission`

Los roles iniciales no limitan la evolución futura del modelo.

---

# Role Responsibilities

## ROLE_ROOT

* Gestión global de academias.
* Activación y suspensión de academias.
* Operaciones de plataforma.

## ROLE_ACADEMY_ADMIN

* Operación diaria dentro de una academia.
* Gestión de sedes, categorías, equipos, jugadores, acudientes, matrículas y pagos.

---

# Tenant Security Model

## Resolution

El tenant se resolverá desde el JWT y se almacenará en un `TenantContext`.

## Enforcement Layers

1. Symfony Security valida credenciales y token.
2. Application layer usa el contexto de tenant.
3. Persistence layer aplica filtros por `academy_id`.

## Mandatory Rule

Toda consulta tenant-aware debe filtrar por:

```sql
academy_id = :academy_id
```

---

# Doctrine Enforcement

Se utilizará un filtro global de Doctrine para asegurar el aislamiento del tenant.

## Objective

* Reducir errores humanos.
* Evitar fugas de datos entre academias.
* Reforzar el aislamiento en repositorios y consultas.

---

# Password Security

## Storage

Las contraseñas se almacenarán con un hash seguro, preferentemente `argon2id`.

## Rules

* Nunca almacenar contraseñas en texto plano.
* Nunca exponer `password_hash` en la API.

---

# Audit Identity

Los campos de auditoría deben llenarse con el usuario autenticado que ejecuta la operación.

## Audit Fields

* `created_by`
* `updated_by`
* `deleted_by`

Si la operación la ejecuta `ROLE_ROOT`, igualmente se registra su `user_id`.

---

# API Security

## Versioning

Todas las rutas estarán bajo:

```text
/api/v1
```

## Required Checks

Cada endpoint deberá validar:

* Token válido
* Rol autorizado
* Tenant correcto

---

# Error Handling

## Standard Codes

* `401 Unauthorized` cuando el token falta o es inválido.
* `403 Forbidden` cuando el rol no tiene permiso o hay mismatch de tenant.

## Logging

Se deben registrar eventos relevantes:

* Login exitoso
* Login fallido
* Acceso denegado
* Token inválido

---

# Future Enhancements

No forman parte del MVP:

* Refresh tokens
* Revocación de tokens
* MFA
* Session management
* Device tracking
