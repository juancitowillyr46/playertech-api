# HU-004 Actualizar Usuario Administrativo

## Historia de Usuario

Como administrador de plataforma o de academia

Quiero actualizar la información de un usuario

Para mantener la información actualizada.

---

# Reglas de Negocio

* El usuario debe existir.
* No se puede modificar la academia del usuario si ya pertenece a un tenant.
* No se puede cambiar un usuario root a tenant ni viceversa mediante este flujo.

---

# Criterios de Aceptación

## CA-001

Dado un usuario existente

Cuando actualizo su información

Entonces el sistema guarda los cambios.

## CA-002

Dado un usuario inexistente

Cuando intento actualizarlo

Entonces el sistema informa que no existe.

