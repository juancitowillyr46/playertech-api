# HU-004 Editar Acudiente

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-004 |
| Épica | EP-006 Legal Guardian Management |
| Prioridad | Alta |
| MVP | Sí |
| Estado | Draft |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir actualizar los datos de un acudiente existente dentro de la academia actual.

---

# Historia de Usuario

Como administrador de academia

Quiero editar un acudiente

Para corregir o completar información de contacto e identificación cuando sea necesario.

---

# Reglas de Negocio

* El acudiente debe pertenecer a la academia actual.
* No se debe permitir actualizar un acudiente de otra academia.
* El correo, si se actualiza, debe conservar unicidad por academia.
* Los cambios deben quedar auditados.

---

# Criterios de Aceptación

* Dado un acudiente existente, cuando actualizo sus datos válidos, entonces el sistema guarda los cambios.
* Dado un correo duplicado en la academia, cuando intento actualizar el acudiente, entonces el sistema rechaza la operación.
* Dado un acudiente inexistente, cuando intento editarlo, entonces el sistema informa el error.

---

# Permisos Requeridos

* Guardian.Update
