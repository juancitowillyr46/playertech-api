# HU-007 Consultar Deuda de Jugador

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-007 |
| Épica | EP-012 Gestión de Cargos y Pagos |
| Prioridad | Alta |
| MVP | Sí |
| Estado | Done |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir consultar cuánto debe un jugador mediante su matrícula y cargos pendientes.

---

# Historia de Usuario

Como administrador de academia

Quiero consultar la deuda de un jugador

Para saber cuánto falta por pagar y tomar seguimiento.

---

# Reglas de Negocio

* La deuda debe calcularse sobre cargos pendientes.
* La deuda debe reflejar solo datos de la academia actual.
* El endpoint HTTP y la colección Postman ya existen.

---

# Criterios de Aceptación

* Dado un jugador con cargos pendientes, cuando consulto su deuda, entonces el sistema muestra el saldo correcto.
