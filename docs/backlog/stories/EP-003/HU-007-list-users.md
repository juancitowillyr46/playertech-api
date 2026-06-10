# HU-007 Consultar Usuarios Administrativos

## Historia de Usuario

Como administrador académico

Quiero consultar los usuarios registrados

Para administrar el acceso de la academia.

---

# Valor de Negocio

Tener visibilidad de quién tiene acceso al sistema.

---

# Reglas de Negocio

* Solo se visualizan usuarios de la academia actual.
* No se muestran usuarios de otras academias.

---

# Criterios de Aceptación

## CA-001

Dado un administrador autenticado

Cuando consulta usuarios

Entonces el sistema muestra únicamente usuarios de su academia.

## CA-002

Dado usuarios inactivos

Cuando consulta usuarios

Entonces el sistema muestra su estado correspondiente.
