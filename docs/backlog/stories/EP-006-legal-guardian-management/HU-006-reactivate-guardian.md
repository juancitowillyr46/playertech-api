# HU-006 Reactivar Acudiente

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-006 |
| Épica | EP-006 Legal Guardian Management |
| Prioridad | Alta |
| MVP | Sí |
| Estado | Draft |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir volver a activar un acudiente previamente inactivado.

---

# Historia de Usuario

Como administrador de academia

Quiero reactivar un acudiente

Para reincorporarlo a la operación activa cuando vuelva a ser necesario.

---

# Reglas de Negocio

* Solo se puede reactivar un acudiente inactivo.
* La reactivación no debe alterar sus asociaciones históricas.
* La operación debe respetar la academia autenticada.

---

# Criterios de Aceptación

* Dado un acudiente inactivo, cuando lo reactivo, entonces el sistema cambia su estado a activo.
* Dado un acudiente activo, cuando intento reactivarlo, entonces el sistema mantiene el estado o informa que no aplica.

---

# Permisos Requeridos

* Guardian.Update
