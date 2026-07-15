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

Permitir registrar un acudiente dentro de la academia actual con sus datos de identificación y contacto básicos.

---

# Historia de Usuario

Como administrador de academia

Quiero crear un acudiente

Para asociarlo posteriormente a uno o varios jugadores y conservar sus datos de identificación y contacto útiles para comprobantes y gestión documental.

---

# Reglas de Negocio

* El acudiente debe pertenecer a la academia actual.
* El tipo y número de documento deben poder registrarse como información principal del acudiente.
* La dirección y el correo pueden registrarse como datos opcionales.
* El correo debe ser único dentro de la academia si aplica.
* El registro debe quedar disponible para asociación con jugadores.

---

# Criterios de Aceptación

* Dado datos válidos, cuando creo un acudiente, entonces el sistema lo registra correctamente.
* Dado un correo ya existente, cuando intento crear otro acudiente con el mismo correo, entonces el sistema rechaza la operación.

---

# Permisos Requeridos

* Guardian.Create
