# HU-003 Crear Usuario Administrativo

## Información General

| Campo           | Valor                  |
| --------------- | ---------------------- |
| ID              | HU-003                 |
| Épica           | EP-003                 |
| Prioridad       | Alta                   |
| MVP             | Sí                     |
| Actor Principal | Academy Admin |

---

# Historia de Usuario

Como administrador de plataforma o academia

Quiero registrar usuarios administrativos del tenant o de plataforma

Para delegar responsabilidades operativas dentro de la plataforma o del tenant.

---

# Valor de Negocio

Reducir la dependencia de una sola persona para administrar la academia y la plataforma.

---

# Dominios Involucrados

* Identity
* User
* Role
* Academy

---

# Reglas de Negocio

## BR-001

El correo electrónico debe ser único dentro de la plataforma.

## BR-002

Los usuarios tenant deben pertenecer a una academia.

## BR-002a

Los usuarios root no pertenecen a ninguna academia.

## BR-003

Todo usuario debe tener un rol asignado.

## BR-004

Los nuevos usuarios se crean inicialmente activos.

## BR-005

No se puede crear un usuario tenant sin `academy_id`.

---

# Datos Requeridos

## Obligatorios

* Nombre completo
* Correo electrónico
* Rol

---

# Flujo Principal

1. Administrador crea usuario.
2. Sistema valida información.
3. Sistema crea usuario.
4. Sistema genera contraseña temporal.
5. Sistema almacena el usuario.

---

# Criterios de Aceptación

## CA-001

Dado información válida

Cuando se registra un usuario

Entonces el sistema crea el usuario.

## CA-002

Dado un correo existente

Cuando intenta crear un usuario

Entonces el sistema rechaza la operación.

## CA-003

Dado un rol inválido

Cuando intenta registrar el usuario

Entonces el sistema informa el error.
