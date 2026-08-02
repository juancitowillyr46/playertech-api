# HU-005 Inactivar Acudiente

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-005 |
| Épica | EP-006 Legal Guardian Management |
| Prioridad | Alta |
| MVP | Sí |
| Estado | Draft |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir marcar un acudiente como inactivo sin eliminar su historial.

---

# Historia de Usuario

Como administrador de academia

Quiero inactivar un acudiente

Para retirarlo de la operación activa sin perder trazabilidad ni asociaciones históricas.

---

# Reglas de Negocio

* La inactivación no debe borrar el acudiente.
* El acudiente inactivo no debe perder su historial.
* La operación debe respetar la academia autenticada.

---

# Criterios de Aceptación

* Dado un acudiente activo, cuando lo inactivo, entonces el sistema cambia su estado a inactivo.
* Dado un acudiente ya inactivo, cuando ejecuto la operación, entonces el sistema mantiene el estado consistente.

---

# Permisos Requeridos

* Guardian.Update
