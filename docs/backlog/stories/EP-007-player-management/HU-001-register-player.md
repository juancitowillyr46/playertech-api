# HU-001 Registrar Jugador

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-001 |
| Épica | EP-007 Gestión de Jugadores |
| Prioridad | Alta |
| MVP | Sí |
| Estado | Done |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir registrar un jugador dentro de la academia actual.

---

# Historia de Usuario

Como administrador de academia

Quiero registrar un jugador

Para centralizar su información administrativa y deportiva.

---

# Reglas de Negocio

* El jugador debe pertenecer a la academia actual.
* El número de documento debe ser válido.
* El jugador se crea en estado activo salvo regla futura distinta.
* El registro no debe permitir duplicados definidos por el dominio.

---

# Criterios de Aceptación

* Dado datos válidos, cuando registro un jugador, entonces el sistema lo crea correctamente.
* Dado un documento duplicado, cuando intento registrar, entonces el sistema rechaza la operación.

---

# Referencia Técnica

* Implementado con `POST /api/v1/academy/players`.
* Usa `PlayerId` como custom type UUID.
* Usa XML mapping puro para Doctrine.
* Responde con DTO JSON estándar.

