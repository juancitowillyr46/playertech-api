# HU-008 Crear usuario owner/admin inicial del tenant

## Información General

| Campo           | Valor                                |
| --------------- | ------------------------------------ |
| ID              | HU-008                               |
| Épica           | EP-003 Gestión de Usuarios y Accesos |
| Prioridad       | Alta                                 |
| MVP             | Sí                                   |
| Estado          | Draft                                |
| Actor Principal | Super Admin                          |

---

# Historia de Usuario

Como Super Admin

Quiero crear el usuario owner/admin inicial de una academia

Para habilitar la administración del tenant desde su contexto propio.

---

# Valor de Negocio

Asegurar que cada tenant nazca con un usuario responsable de la operación administrativa.

---

# Dominios Involucrados

* Identity
* User
* Role
* Academy

---

# Reglas de Negocio

* El usuario owner/admin inicial debe pertenecer a la academia creada.
* El usuario inicial del tenant no puede ser root.
* El usuario inicial puede quedar pendiente de activación por correo.
* La academia debe existir antes de crear el usuario.
* El tenant debe conservar al menos un administrador activo.

---

# Flujo Principal

1. Super Admin crea o confirma la academia.
2. Sistema crea el usuario owner/admin inicial del tenant.
3. Sistema asigna `academy_id`.
4. Sistema envía correo de activación si aplica.
5. Sistema deja el usuario listo para operar o pendiente de activación según configuración.

---

# Criterios de Aceptación

## CA-001

Dado una academia existente

Cuando el Super Admin crea el usuario owner/admin inicial

Entonces el sistema lo asocia a la academia correcta.

## CA-002

Dado un usuario inicial creado

Cuando se consulta el tenant

Entonces el sistema muestra al menos un usuario administrador responsable.


