# HU-001 Iniciar Sesión

## Información General

| Campo           | Valor                                |
| --------------- | ------------------------------------ |
| ID              | HU-001                               |
| Épica           | EP-003 Gestión de Usuarios y Accesos |
| Prioridad       | Alta                                 |
| MVP             | Sí                                   |
| Estado          | Draft                                |
| Actor Principal | Usuario Administrativo               |

---

# Objetivo

Permitir que un usuario autorizado acceda a la plataforma utilizando sus credenciales.

---

# Problema de Negocio

La plataforma requiere identificar al usuario que realiza las operaciones y restringir el acceso únicamente a personas autorizadas.

---

# Historia de Usuario

Como usuario administrativo de plataforma o tenant

Quiero iniciar sesión

Para acceder a las funcionalidades permitidas según mi rol.

---

# Valor de Negocio

Garantizar acceso seguro a la información de la plataforma o de la academia según el contexto del usuario.

---

# Dominios Involucrados

* User
* Role
* Academy

---

# Reglas de Negocio

## BR-001

Solo usuarios activos pueden iniciar sesión.

## BR-002

Los usuarios tenant pertenecen a una academia.

## BR-003

Todo usuario posee un rol asignado.

## BR-004

Si el usuario pertenece a un tenant, la academia debe encontrarse activa.

## BR-005

Los usuarios root no requieren `academy_id`.

---

# Datos Requeridos

## Obligatorios

* Correo electrónico
* Contraseña

---

# Flujo Principal

1. Usuario ingresa correo electrónico.
2. Usuario ingresa contraseña.
3. Sistema valida credenciales.
4. Sistema valida estado del usuario.
5. Sistema valida estado de la academia.
6. Sistema genera sesión autenticada.
7. Sistema muestra pantalla principal.

---

# Criterios de Aceptación

## CA-001

Dado un usuario activo

Cuando ingresa credenciales válidas

Entonces el sistema permite el acceso.

## CA-002

Dado credenciales inválidas

Cuando intenta autenticarse

Entonces el sistema rechaza la solicitud.

## CA-003

Dado un usuario inactivo

Cuando intenta autenticarse

Entonces el sistema deniega el acceso.

## CA-004

Dado una academia suspendida

Cuando un usuario intenta autenticarse

Entonces el sistema deniega el acceso.
