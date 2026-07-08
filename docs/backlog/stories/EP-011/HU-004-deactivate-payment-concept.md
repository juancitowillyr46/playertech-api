# HU-004 Desactivar Concepto de Pago

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-004 |
| Épica | EP-011 Gestión de Conceptos de Pago |
| Prioridad | Media |
| MVP | Sí |
| Estado | Done |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir desactivar un concepto de pago sin borrar su historial.

---

# Historia de Usuario

Como administrador de academia

Quiero desactivar un concepto de pago

Para evitar su uso en nuevos cargos sin perder trazabilidad histórica.

---

# Reglas de Negocio

* Un concepto desactivado no debe usarse en nuevos cargos.
* La desactivación no debe eliminar cargos históricos.

---

# Criterios de Aceptación

* Dado un concepto activo, cuando lo desactivo, entonces el sistema impide su uso en nuevos cargos.

