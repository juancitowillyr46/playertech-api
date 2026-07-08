# HU-005 Desactivar Usuario Administrativo

## Historia de Usuario

Como administrador de plataforma o academia

Quiero desactivar un usuario

Para impedir que continúe accediendo a la plataforma.

---

# Reglas de Negocio

* No se eliminan usuarios.
* El historial debe conservarse.
* Un usuario desactivado no puede autenticarse.
* No se puede desactivar el último administrador activo del tenant.

---

# Criterios de Aceptación

## CA-001

Dado un usuario activo

Cuando lo desactivo

Entonces el sistema cambia su estado a INACTIVE.

## CA-002

Dado un usuario inactivo

Cuando intenta iniciar sesión

Entonces el sistema rechaza el acceso.

