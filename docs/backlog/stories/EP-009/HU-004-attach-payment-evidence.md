# HU-004 Adjuntar Evidencia de Pago

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-004 |
| Épica | EP-009 Gestión de Matrículas y Seguimiento de Pagos |
| Prioridad | Media |
| MVP | Sí |
| Estado | Draft |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir adjuntar un comprobante a un pago para respaldar el registro financiero.

---

# Historia de Usuario

Como administrador de academia

Quiero adjuntar una evidencia a un pago

Para respaldar el registro realizado y validar el soporte del abono.

---

# Reglas de Negocio

* Una evidencia debe pertenecer a un pago existente.
* Un pago puede tener múltiples evidencias.
* La evidencia debe registrarse dentro de la academia actual.

---

# Criterios de Aceptación

* Dado un pago válido, cuando adjunto una evidencia, entonces el sistema la asocia correctamente.
* Dado un pago inexistente, cuando intento adjuntar una evidencia, entonces el sistema rechaza la operación.

---

# Referencia Técnica

* Implementación futura sobre `PaymentEvidence`.
* Debe respetar contratos de media y auditoría.
