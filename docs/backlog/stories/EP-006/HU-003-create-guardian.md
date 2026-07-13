# HU-003 Crear Acudiente

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-003 |
| Épica | EP-006 Gestión de Acudientes |
| Prioridad | Alta |
| MVP | Sí |
| Estado | Draft |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir registrar un acudiente dentro de la academia actual.

---

# Historia de Usuario

Como administrador de academia

Quiero crear un acudiente

Para asociarlo posteriormente a uno o varios jugadores.

---

# Reglas de Negocio

* El acudiente debe pertenecer a la academia actual.
* El correo debe ser único dentro de la academia si aplica.
* El registro debe quedar disponible para asociación con jugadores.

---

# Criterios de Aceptación

* Dado datos válidos, cuando creo un acudiente, entonces el sistema lo registra correctamente.
* Dado un correo ya existente, cuando intento crear otro acudiente con el mismo correo, entonces el sistema rechaza la operación.

---

# Permisos Requeridos

* Guardian.Create
