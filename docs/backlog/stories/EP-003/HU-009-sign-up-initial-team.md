# HU-009 - Registrar el primer equipo durante el Sign Up

## Información General

| Campo | Valor |
|--------|-------|
| ID | HU-009 |
| Épica | EP-003 - Registro de Academia |
| Prioridad | Alta |
| Estado | Done |
| Actor | Administrador de Academia |

---

# Historia de Usuario

**Como** administrador de una nueva academia

**Quiero** registrar el primer equipo durante el proceso de creación de la academia

**Para** iniciar inmediatamente la gestión de mis jugadores sin tener que crear un equipo posteriormente.

---

# Objetivo

Extender el proceso de Sign Up para permitir la creación del primer equipo de la academia durante el registro inicial.

---

# Reglas de Negocio

## RN-001

El usuario deberá seleccionar una categoría existente.

---

## RN-002

El sistema recibirá el identificador de la categoría (`categoryId`).

---

## RN-003

La categoría debe existir.

---

## RN-004

La categoría debe encontrarse activa.

---

## RN-005

El usuario deberá ingresar el nombre del equipo.

---

## RN-006

El nombre del equipo es obligatorio.

---

## RN-007

El nombre del equipo tendrá una longitud máxima de 80 caracteres.

---

## RN-008

No podrá existir otro equipo con el mismo nombre dentro de la misma categoría de la academia.

---

## RN-009

Al finalizar exitosamente el Sign Up, el sistema deberá crear automáticamente:

- Academia
- Usuario Administrador
- Equipo asociado a la categoría seleccionada

---

# Flujo Principal

1. El usuario completa el formulario de registro.
2. Ingresa la información de la academia.
3. Selecciona una categoría.
4. Ingresa el nombre del primer equipo.
5. El sistema valida la información.
6. El sistema crea la academia.
7. El sistema crea el usuario administrador.
8. El sistema crea el primer equipo asociado a la categoría seleccionada.
9. El sistema retorna la respuesta de autenticación.

---

# Flujos Alternativos

## FA-001

Si la categoría no existe.

**Resultado**

Se retorna:

```http
404 Not Found
```

---

## FA-002

Si la categoría se encuentra inactiva.

**Resultado**

```http
409 Conflict
```

---

## FA-003

Si ya existe un equipo con el mismo nombre dentro de la categoría.

**Resultado**

```http
409 Conflict
```

---

## FA-004

Si el nombre del equipo es inválido.

**Resultado**

```http
400 Bad Request
```

---

# Cambios Funcionales

Se modifica el proceso de Sign Up para incluir:

- categoryId
- teamName

---

# Cambios Técnicos

Actualizar:

- Request DTO de Sign Up.
- Command de Sign Up.
- Handler de Sign Up.
- Caso de uso de registro.
- Controller.
- Validaciones.
- Persistencia del Team.
- Documentación de la API.
- Colección Postman.
- Pruebas unitarias.
- Pruebas funcionales.

---

# Endpoint Afectado

```http
POST /api/v1/public/tenants/signup
```

---

# Request

```json
{
    "name": "Academia PlayerTech",
    "contact_email": "academy@test.com",
    "contact_name": "Juan Rodas",
    "password": "Password123*",
    "phone": "3001234567",
    "address": "Av. Principal 123",
    "city": "Lima",
    "category_id": "uuid",
    "team_name": "Sub 12 A"
}
```

---

# Criterios de Aceptación

- [x] El Sign Up recibe categoryId.
- [x] El Sign Up recibe teamName.
- [x] La categoría existe.
- [x] La categoría está activa.
- [x] El nombre del equipo es obligatorio.
- [x] No existen equipos duplicados por categoría.
- [x] Se crea automáticamente el primer equipo.
- [x] El endpoint mantiene la compatibilidad con el resto del proceso de registro.
- [x] Se actualiza la documentación de la API.
- [x] Se actualiza la colección Postman.
- [x] Se implementan pruebas unitarias y funcionales.

---

# Definition of Done

- Funcionalidad implementada.
- Arquitectura DDD respetada.
- Sin deuda técnica.
- Tests aprobados.
- Colección Postman actualizada.
- Documentación actualizada.

