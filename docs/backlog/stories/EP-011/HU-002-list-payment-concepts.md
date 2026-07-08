# HU-002 Listar Conceptos de Pago

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-002 |
| Épica | EP-011 Gestión de Conceptos de Pago |
| Prioridad | Media |
| MVP | Sí |
| Estado | Draft |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir consultar los conceptos de pago disponibles en la academia.

---

# Historia de Usuario

Como administrador de academia

Quiero listar los conceptos de pago

Para seleccionar el concepto correcto al crear cargos o registrar pagos.

---

# Reglas de Negocio

* La lista debe mostrar solo conceptos del tenant actual.
* La lista debe excluir conceptos eliminados o desactivados según corresponda.

---

# Criterios de Aceptación

* Dado que existen conceptos activos, cuando los consulto, entonces el sistema los lista correctamente.

