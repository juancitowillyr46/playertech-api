# HU-001 Crear Concepto de Pago

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-001 |
| Épica | EP-011 Gestión de Conceptos de Pago |
| Prioridad | Alta |
| MVP | Sí |
| Estado | Draft |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir crear conceptos de cobro que puedan usarse en matrícula, mensualidades y otros cargos.

---

# Historia de Usuario

Como administrador de academia

Quiero crear un concepto de pago

Para definir el motivo financiero de un cargo.

---

# Reglas de Negocio

* El concepto debe pertenecer a la academia actual.
* El nombre del concepto debe ser único dentro de la academia.
* El concepto debe poder quedar activo al crearse.

---

# Criterios de Aceptación

* Dado un nombre válido y único, cuando creo el concepto, entonces el sistema lo registra correctamente.
* Dado un nombre duplicado, cuando intento crear el concepto, entonces el sistema rechaza la operación.

