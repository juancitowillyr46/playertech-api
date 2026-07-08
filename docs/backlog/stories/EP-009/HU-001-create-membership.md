# HU-001 Crear Matrícula con Acudiente Principal

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-001 |
| Épica | EP-009 Gestión de Matrículas y Seguimiento de Pagos |
| Prioridad | Alta |
| MVP | Sí |
| Estado | Draft |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir crear la matrícula de un jugador asociándola a un acudiente principal responsable de los pagos.

---

# Historia de Usuario

Como administrador de academia

Quiero crear la matrícula de un jugador con su acudiente principal

Para dejar constancia oficial de su vínculo administrativo y generar los cargos iniciales pendientes.

---

# Reglas de Negocio

* La matrícula debe pertenecer a la academia actual.
* El jugador debe existir y pertenecer al tenant actual.
* La matrícula debe tener un acudiente principal.
* Solo puede existir una matrícula activa por jugador dentro de una academia.
* La matrícula se crea en estado activo salvo regla futura distinta.
* Al crear la matrícula se generan dos cargos iniciales: matrícula y primera mensualidad.

---

# Criterios de Aceptación

* Dado un jugador válido y un acudiente principal válido, cuando creo la matrícula, entonces el sistema la registra correctamente.
* Dado que el jugador ya tiene una matrícula activa, cuando intento crear otra, entonces el sistema rechaza la operación.
* Dado que no existe acudiente principal, cuando intento crear la matrícula, entonces el sistema rechaza la operación.

---

# Referencia Técnica

* Implementación futura sobre `Membership`.
* Debe respetar aislamiento por `academy_id`.
* Debe responder con Problem Details ante errores de validación o dominio.
