# HU-004 Adjuntar Evidencia de Pago

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-004 |
| Épica | EP-012 Gestión de Cargos y Pagos |
| Prioridad | Media |
| MVP | Sí |
| Estado | Done |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir adjuntar un comprobante a un pago.

---

# Historia de Usuario

Como administrador de academia

Quiero adjuntar una evidencia de pago

Para respaldar el abono realizado por el acudiente principal.

---

# Reglas de Negocio

* Una evidencia debe pertenecer a un pago existente.
* Un pago puede tener múltiples evidencias.
* El endpoint HTTP y la colección Postman ya existen.

---

# Criterios de Aceptación

* Dado un pago válido, cuando adjunto una evidencia, entonces el sistema la asocia correctamente.
