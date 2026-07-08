# HU-002 Consultar Matrícula Activa

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-002 |
| Épica | EP-009 Gestión de Matrículas y Seguimiento de Pagos |
| Prioridad | Alta |
| MVP | Sí |
| Estado | Done |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir consultar la matrícula activa de un jugador para validar si pertenece actualmente a la academia.

---

# Historia de Usuario

Como administrador de academia

Quiero consultar la matrícula activa de un jugador

Para saber si está vinculado actualmente a la academia y quién es su acudiente principal responsable.

---

# Reglas de Negocio

* La consulta debe limitarse a la academia actual.
* Si el jugador no tiene matrícula activa, el sistema debe indicarlo claramente.
* La respuesta debe incluir el acudiente principal asociado.

---

# Criterios de Aceptación

* Dado un jugador con matrícula activa, cuando consulto, entonces el sistema devuelve su estado y su acudiente principal.
* Dado un jugador sin matrícula activa, cuando consulto, entonces el sistema informa que no existe matrícula vigente.

---

# Referencia Técnica

* Implementación futura sobre `Membership`.
* Debe usar DTO de salida para no exponer entidades de dominio o persistencia.
